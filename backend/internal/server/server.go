// Package server merakit router HTTP + middleware dasar.
package server

import (
	"log"
	"net/http"
	"time"

	"budes/internal/db"
	"budes/internal/health"
	"budes/internal/reference"
)

// New membangun handler HTTP lengkap dengan rute & middleware.
func New(pools *db.Pools) http.Handler {
	mux := http.NewServeMux()

	h := health.New(pools)
	mux.HandleFunc("GET /health", h.Check)

	ref := reference.NewHandler(reference.NewRepository(pools.Ref))
	mux.HandleFunc("GET /api/ref/koperasi", ref.SearchKoperasi)
	mux.HandleFunc("GET /api/ref/anggota", ref.SearchAnggota)
	mux.HandleFunc("GET /api/match/kandidat", ref.MatchKandidat)

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
