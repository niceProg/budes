// Package payment mengintegrasikan gateway pembayaran Mayar (DP demand & pelunasan transaksi).
package payment

import (
	"bytes"
	"context"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"time"
)

// Mayar adalah klien tipis untuk API Mayar (https://docs.mayar.id).
type Mayar struct {
	baseURL string // mis. https://api.mayar.club/hl/v1 (sandbox)
	apiKey  string
	http    *http.Client
}

// NewMayar membuat klien Mayar.
func NewMayar(baseURL, apiKey string) *Mayar {
	return &Mayar{baseURL: baseURL, apiKey: apiKey, http: &http.Client{Timeout: 20 * time.Second}}
}

// Enabled: gateway aktif bila API key terisi.
func (m *Mayar) Enabled() bool { return m.apiKey != "" }

// CreateInput = payload pembuatan permintaan bayar.
type CreateInput struct {
	Name        string
	Email       string
	Mobile      string
	Amount      int64
	Description string
	RedirectURL string
}

// CreateResult = hasil (id link, id transaksi, URL bayar).
type CreateResult struct {
	LinkID        string
	TransactionID string
	Link          string
}

// CreatePayment membuat permintaan bayar (POST /payment/create) & kembalikan URL bayar.
func (m *Mayar) CreatePayment(ctx context.Context, in CreateInput) (*CreateResult, error) {
	body := map[string]any{
		"name":        in.Name,
		"email":       in.Email,
		"mobile":      in.Mobile,
		"amount":      in.Amount,
		"description": in.Description,
		"redirectUrl": in.RedirectURL,
		"expiredAt":   time.Now().Add(24 * time.Hour).UTC().Format(time.RFC3339),
	}
	buf, _ := json.Marshal(body)
	req, err := http.NewRequestWithContext(ctx, http.MethodPost, m.baseURL+"/payment/create", bytes.NewReader(buf))
	if err != nil {
		return nil, err
	}
	req.Header.Set("Authorization", "Bearer "+m.apiKey)
	req.Header.Set("Content-Type", "application/json")

	resp, err := m.http.Do(req)
	if err != nil {
		return nil, err
	}
	defer resp.Body.Close()
	raw, _ := io.ReadAll(resp.Body)
	if resp.StatusCode < 200 || resp.StatusCode >= 300 {
		return nil, fmt.Errorf("mayar create %d: %s", resp.StatusCode, string(raw))
	}
	var out struct {
		StatusCode int    `json:"statusCode"`
		Messages   string `json:"messages"`
		Data       struct {
			ID            string `json:"id"`
			PaymentLinkID string `json:"paymentLinkId"`
			TransactionID string `json:"transactionId"`
			Link          string `json:"link"`
		} `json:"data"`
	}
	if err := json.Unmarshal(raw, &out); err != nil {
		return nil, fmt.Errorf("mayar decode: %w (%s)", err, string(raw))
	}
	linkID := out.Data.PaymentLinkID
	if linkID == "" {
		linkID = out.Data.ID
	}
	return &CreateResult{LinkID: linkID, TransactionID: out.Data.TransactionID, Link: out.Data.Link}, nil
}
