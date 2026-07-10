package demand

import (
	"context"
	"errors"
	"fmt"
	"math"
	"strings"

	"budes/internal/notify"
)

const defaultDPPercent = 30.0

// computeDP menghitung total harga, uang muka (30%), dan sisa pelunasan.
func computeDP(qty int, price float64) (total, dp, remaining float64) {
	total = float64(qty) * price
	dp = math.Round(total*defaultDPPercent) / 100 // 30% dibulatkan 2 desimal
	remaining = total - dp
	return
}

// Service berisi logika bisnis alur Demand.
type Service struct {
	repo     *Repository
	notifier *notify.Notifier
}

// NewService membuat service demand.
func NewService(repo *Repository, notifier *notify.Notifier) *Service {
	return &Service{repo: repo, notifier: notifier}
}

// Create memvalidasi input, menghitung DP (30%), dan menyimpan demand DRAFT.
func (s *Service) Create(ctx context.Context, buyerID string, in CreateInput) (*Demand, error) {
	in.ItemName = strings.TrimSpace(in.ItemName)
	if in.ItemName == "" || in.TotalQty <= 0 || in.TargetPricePerItem <= 0 {
		return nil, errors.New("item_name, total_qty > 0, dan target_price_per_item > 0 wajib")
	}
	totalPrice, dpAmount, remaining := computeDP(in.TotalQty, in.TargetPricePerItem)

	id, err := s.repo.Create(ctx, buyerID, in, totalPrice, dpAmount, remaining)
	if err != nil {
		return nil, err
	}
	return s.repo.Get(ctx, id)
}

// PayDP mencatat pembayaran uang muka & mendorong demand ke OPEN.
func (s *Service) PayDP(ctx context.Context, id, buyerID, method string) (*Demand, error) {
	if method != "CASH" && method != "TRANSFER" {
		return nil, errors.New("dp_payment_method harus CASH atau TRANSFER")
	}
	n, err := s.repo.MarkDPPaid(ctx, id, buyerID, method)
	if err != nil {
		return nil, err
	}
	if n == 0 {
		return nil, errors.New("DP tidak bisa dibayar (bukan milik Anda, atau bukan status DRAFT/UNPAID)")
	}
	d, err := s.repo.Get(ctx, id)
	if err == nil && d != nil {
		s.notifier.Broadcast(newDemandMessage(d)) // Broadcast Kebutuhan (fire-and-forget)
	}
	return d, err
}

// newDemandMessage merangkai teks broadcast "Kebutuhan Baru".
func newDemandMessage(d *Demand) string {
	satuan := ""
	if d.Satuan != nil {
		satuan = " " + *d.Satuan
	}
	harga := ""
	if d.TargetPricePerItem != nil {
		harga = fmt.Sprintf("\nHarga: Rp%.0f/item", *d.TargetPricePerItem)
	}
	return fmt.Sprintf("🛒 *Kebutuhan Baru di Bursa Desa*\nBarang: %s\nJumlah: %d%s%s\n\nAyo warga desa menyanggupi! 🌾",
		d.ItemName, d.TotalQty, satuan, harga)
}

// Pledge menyanggupi demand (gotong royong).
func (s *Service) Pledge(ctx context.Context, demandID, wargaID string, qty int, price *float64) (*Pledge, error) {
	if qty <= 0 {
		return nil, errors.New("qty_pledged harus > 0")
	}
	return s.repo.CreatePledge(ctx, demandID, wargaID, qty, price)
}
