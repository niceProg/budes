// Package server merakit router HTTP + middleware dasar.
package server

import (
	"log"
	"net/http"
	"time"

	"budes/internal/auth"
	"budes/internal/config"
	"budes/internal/db"
	"budes/internal/demand"
	"budes/internal/health"
	"budes/internal/match"
	"budes/internal/notify"
	"budes/internal/payment"
	"budes/internal/reference"
	"budes/internal/riwayat"
	"budes/internal/settings"
	"budes/internal/settlement"
	"budes/internal/supply"
	"budes/internal/verification"
)

// New membangun handler HTTP lengkap dengan rute & middleware.
func New(pools *db.Pools, cfg config.Config, notifier *notify.Notifier) http.Handler {
	mux := http.NewServeMux()

	h := health.New(pools)
	mux.HandleFunc("GET /health", h.Check)

	refRepo := reference.NewRepository(pools.Ref)
	ref := reference.NewHandler(refRepo)
	mux.HandleFunc("GET /api/ref/koperasi", ref.SearchKoperasi)
	mux.HandleFunc("GET /api/ref/anggota", ref.SearchAnggota)
	mux.HandleFunc("GET /api/match/kandidat", ref.MatchKandidat)

	// --- Auth (Modul A) ---
	jwtMgr := auth.NewManager(cfg.JWTSecret)
	aRepo := auth.NewRepository(pools.App)
	aH := auth.NewHandler(auth.NewService(aRepo, jwtMgr, refRepo), aRepo)
	mux.HandleFunc("POST /api/auth/register", aH.Register)
	mux.HandleFunc("POST /api/auth/login", aH.Login)
	mux.HandleFunc("POST /api/auth/logout", aH.Logout)
	mux.Handle("GET /api/me", jwtMgr.Middleware(http.HandlerFunc(aH.Me)))

	// helper: proteksi + batasan peran
	auth1 := func(h http.HandlerFunc) http.Handler { return jwtMgr.Middleware(h) }
	role := func(h http.HandlerFunc, roles ...string) http.Handler {
		return jwtMgr.Middleware(auth.RequireRole(roles...)(http.HandlerFunc(h)))
	}

	// --- Demand (Modul B, alur A) ---
	demandRepo := demand.NewRepository(pools.App)
	dH := demand.NewHandler(demand.NewService(demandRepo, notifier), demandRepo)
	mux.HandleFunc("GET /api/demands", dH.List)        // publik
	mux.HandleFunc("GET /api/demands/{id}", dH.Detail) // publik
	mux.Handle("POST /api/demands", role(dH.Create, "BUYER"))
	mux.Handle("POST /api/demands/{id}/dp", role(dH.PayDP, "BUYER"))
	mux.Handle("POST /api/demands/{id}/cancel", role(dH.Cancel, "BUYER"))
	mux.Handle("POST /api/demands/{id}/expire", role(dH.Expire, "ADMIN_KOPERASI"))
	mux.Handle("POST /api/demands/{id}/pledges", role(dH.CreatePledge, "WARGA"))
	mux.Handle("GET /api/pledges", auth1(dH.MyPledges))
	mux.Handle("PUT /api/pledges/{id}", auth1(dH.UpdatePledge))

	// --- Supply (Modul C, alur B) ---
	supplyRepo := supply.NewRepository(pools.App)
	sH := supply.NewHandler(supplyRepo, notifier)
	mux.HandleFunc("GET /api/listings", sH.List)        // publik
	mux.HandleFunc("GET /api/listings/{id}", sH.Detail) // publik
	mux.Handle("POST /api/listings", role(sH.CreateListing, "WARGA", "ADMIN_KOPERASI"))
	mux.Handle("PUT /api/listings/{id}", auth1(sH.SetListingStatus))
	mux.Handle("GET /api/my/listings", auth1(sH.MyListings))
	mux.Handle("POST /api/orders", role(sH.CreateOrder, "BUYER"))
	mux.Handle("GET /api/orders", role(sH.MyOrders, "BUYER"))
	mux.Handle("GET /api/orders/{id}", auth1(sH.OrderDetail))
	mux.Handle("PUT /api/orders/{id}", auth1(sH.SetOrderStatus))

	// --- Settlement (Modul D+E: serah-terima, transaksi/komisi, sengketa) ---
	settleRepo := settlement.NewRepository(pools.App)
	stH := settlement.NewHandler(settleRepo, notifier)
	mux.Handle("POST /api/pledges/{id}/verifikasi", role(stH.VerifyPledge, "BUYER"))
	mux.Handle("POST /api/orders/{id}/verifikasi", role(stH.VerifyOrder, "BUYER"))
	mux.Handle("POST /api/pledges/{id}/lapor", auth1(stH.LaporPledge))
	mux.Handle("POST /api/orders/{id}/lapor", auth1(stH.LaporOrder))
	mux.Handle("GET /api/transactions", auth1(stH.List))
	mux.Handle("PUT /api/transactions/{kind}/{id}", role(stH.UpdatePayment, "ADMIN_KOPERASI"))
	mux.Handle("GET /api/pembukuan", role(stH.Pembukuan, "ADMIN_KOPERASI"))
	mux.Handle("GET /api/insights", role(stH.Insights, "ADMIN_KOPERASI"))
	mux.HandleFunc("GET /api/stats", stH.Stats) // publik (landing)
	mux.Handle("GET /api/disputes", role(stH.ListDisputes, "ADMIN_KOPERASI"))
	mux.Handle("PUT /api/disputes/{id}", role(stH.ResolveDispute, "ADMIN_KOPERASI"))

	// --- Pembayaran (gateway Mayar): DP demand & pelunasan transaksi ---
	mayar := payment.NewMayar(cfg.Mayar.BaseURL, cfg.Mayar.APIKey)
	payH := payment.NewHandler(mayar, payment.NewRepository(pools.App), demandRepo, settleRepo, notifier, cfg.FrontendURL)
	mux.Handle("POST /api/demands/{id}/dp/pay", role(payH.PayDP, "BUYER"))
	mux.Handle("POST /api/transactions/{kind}/{id}/pay", role(payH.PayTxn, "BUYER"))
	mux.HandleFunc("POST /api/webhooks/mayar", payH.Webhook) // publik (webhook Mayar)

	// --- Verifikasi KYC (Modul A) ---
	vH := verification.NewHandler(verification.NewRepository(pools.App), notifier)
	mux.Handle("POST /api/verifikasi", auth1(vH.Submit))
	mux.Handle("GET /api/verifikasi", role(vH.List, "ADMIN_KOPERASI"))
	mux.Handle("PUT /api/verifikasi/{id}", role(vH.Review, "ADMIN_KOPERASI"))

	// --- Matching grounded (Modul G) ---
	mH := match.NewHandler(demandRepo, refRepo)
	mux.HandleFunc("GET /api/demands/{id}/kandidat", mH.ByDemand) // publik

	// --- Pengaturan (komisi koperasi, editable admin) ---
	setH := settings.NewHandler(settings.NewRepository(pools.App))
	mux.Handle("GET /api/pengaturan/komisi", auth1(setH.GetKomisi))
	mux.Handle("PUT /api/pengaturan/komisi", role(setH.SetKomisi, "ADMIN_KOPERASI"))
	mux.Handle("GET /api/pengaturan/harga", auth1(setH.GetHarga))
	mux.Handle("PUT /api/pengaturan/harga", role(setH.SetHarga, "ADMIN_KOPERASI"))

	// --- Riwayat pengguna (Modul A) ---
	rH := riwayat.NewHandler(demandRepo, supplyRepo, settleRepo)
	mux.Handle("GET /api/me/riwayat", auth1(rH.Me))

	// --- Notifikasi WhatsApp (uji manual) ---
	mux.Handle("POST /api/notify/test", role(notifier.TestHandler(), "ADMIN_KOPERASI"))

	return chain(mux, recoverMW, logger, cors)
}

// chain membungkus handler dengan middleware (urutan luar → dalam).
func chain(h http.Handler, mws ...func(http.Handler) http.Handler) http.Handler {
	for i := len(mws) - 1; i >= 0; i-- {
		h = mws[i](h)
	}
	return h
}

func logger(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		start := time.Now()
		next.ServeHTTP(w, r)
		log.Printf("%s %s %s", r.Method, r.URL.Path, time.Since(start))
	})
}

func recoverMW(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		defer func() {
			if rec := recover(); rec != nil {
				log.Printf("panic: %v", rec)
				http.Error(w, `{"error":"internal server error"}`, http.StatusInternalServerError)
			}
		}()
		next.ServeHTTP(w, r)
	})
}

func cors(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("Access-Control-Allow-Origin", "*")
		w.Header().Set("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
		w.Header().Set("Access-Control-Allow-Headers", "Content-Type, Authorization")
		if r.Method == http.MethodOptions {
			w.WriteHeader(http.StatusNoContent)
			return
		}
		next.ServeHTTP(w, r)
	})
}
