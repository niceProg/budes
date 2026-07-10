package demand

import (
	"context"
	"errors"
	"math"
	"strings"
)

const defaultDPPercent = 30.0

// Service berisi logika bisnis alur Demand.
type Service struct{ repo *Repository }

// NewService membuat service demand.
func NewService(repo *Repository) *Service { return &Service{repo: repo} }

// Create memvalidasi input, menghitung DP (30%), dan menyimpan demand DRAFT.
func (s *Service) Create(ctx context.Context, buyerID string, in CreateInput) (*Demand, error) {
	in.ItemName = strings.TrimSpace(in.ItemName)
	if in.ItemName == "" || in.TotalQty <= 0 || in.TargetPricePerItem <= 0 {
		return nil, errors.New("item_name, total_qty > 0, dan target_price_per_item > 0 wajib")
	}
	totalPrice := float64(in.TotalQty) * in.TargetPricePerItem
	dpAmount := math.Round(totalPrice*defaultDPPercent) / 100 // 30% dibulatkan 2 desimal
	remaining := totalPrice - dpAmount

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
	return s.repo.Get(ctx, id)
}

// Pledge menyanggupi demand (gotong royong).
func (s *Service) Pledge(ctx context.Context, demandID, wargaID string, qty int, price *float64) (*Pledge, error) {
	if qty <= 0 {
		return nil, errors.New("qty_pledged harus > 0")
	}
	return s.repo.CreatePledge(ctx, demandID, wargaID, qty, price)
}
