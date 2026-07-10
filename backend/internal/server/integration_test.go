//go:build integration

// Integration test alur bisnis inti lawan App DB + Reference DB nyata.
// Jalankan: go test -tags=integration ./internal/server/
// (butuh docker DB hidup; WA dimatikan agar tak mengirim pesan nyata).
package server_test

import (
	"bytes"
	"context"
	"encoding/json"
	"fmt"
	"net/http"
	"net/http/httptest"
	"testing"
	"time"

	"budes/internal/config"
	"budes/internal/db"
	"budes/internal/notify"
	"budes/internal/server"
)

type client struct {
	t    *testing.T
	base string
	http *http.Client
}

func (c *client) do(method, path, token string, body any) (int, map[string]any) {
	var buf *bytes.Buffer = bytes.NewBuffer(nil)
	if body != nil {
		_ = json.NewEncoder(buf).Encode(body)
	}
	req, _ := http.NewRequest(method, c.base+path, buf)
	req.Header.Set("Content-Type", "application/json")
	if token != "" {
		req.Header.Set("Authorization", "Bearer "+token)
	}
	resp, err := c.http.Do(req)
	if err != nil {
		c.t.Fatalf("%s %s: %v", method, path, err)
	}
	defer resp.Body.Close()
	var out map[string]any
	_ = json.NewDecoder(resp.Body).Decode(&out)
	return resp.StatusCode, out
}

func TestIntegrationDemandFlow(t *testing.T) {
	cfg := config.Load()
	cfg.WA = config.WAConfig{} // matikan WA untuk test

	ctx := context.Background()
	pools, err := db.Open(ctx, cfg.AppDSN, cfg.RefDSN)
	if err != nil {
		t.Skipf("DB tak tersedia, lewati integration test: %v", err)
	}
	defer pools.Close()

	ts := httptest.NewServer(server.New(pools, cfg, notify.New(cfg.WA)))
	defer ts.Close()
	c := &client{t: t, base: ts.URL, http: ts.Client()}

	uniq := time.Now().UnixNano()
	buyerEmail := fmt.Sprintf("itest_buyer_%d@budes.test", uniq)
	wargaEmail := fmt.Sprintf("itest_warga_%d@budes.test", uniq)

	// register buyer & warga
	code, out := c.do("POST", "/api/auth/register", "", map[string]any{
		"name": "ITest Buyer", "email": buyerEmail, "password": "rahasia123", "role": "BUYER"})
	if code != 201 {
		t.Fatalf("register buyer: %d %v", code, out)
	}
	buyerTok, _ := out["token"].(string)

	code, out = c.do("POST", "/api/auth/register", "", map[string]any{
		"name": "ITest Warga", "email": wargaEmail, "password": "rahasia123", "role": "WARGA"})
	if code != 201 {
		t.Fatalf("register warga: %d %v", code, out)
	}
	wargaTok, _ := out["token"].(string)

	// buat demand 100 @10000 → dp 30% = 300000
	code, out = c.do("POST", "/api/demands", buyerTok, map[string]any{
		"item_name": "ITest Beras", "satuan": "kg", "total_qty": 100, "target_price_per_item": 10000})
	if code != 201 {
		t.Fatalf("create demand: %d %v", code, out)
	}
	d := out["data"].(map[string]any)
	demandID := d["id"].(string)
	if d["demand_status"] != "DRAFT" || d["dp_amount"].(float64) != 300000 {
		t.Fatalf("DP salah: status=%v dp=%v", d["demand_status"], d["dp_amount"])
	}

	// bayar DP → OPEN
	code, out = c.do("POST", "/api/demands/"+demandID+"/dp", buyerTok, map[string]any{"dp_payment_method": "CASH"})
	if code != 200 || out["data"].(map[string]any)["demand_status"] != "OPEN" {
		t.Fatalf("pay DP: %d %v", code, out)
	}

	// warga pledge penuh → CLOSED
	code, out = c.do("POST", "/api/demands/"+demandID+"/pledges", wargaTok, map[string]any{"qty_pledged": 100})
	if code != 201 {
		t.Fatalf("pledge: %d %v", code, out)
	}
	pledgeID := out["data"].(map[string]any)["id"].(string)

	// over-pledge → 409
	if code, _ = c.do("POST", "/api/demands/"+demandID+"/pledges", wargaTok, map[string]any{"qty_pledged": 1}); code != 409 {
		t.Fatalf("over-pledge harus 409, dapat %d", code)
	}

	// transisi → DELIVERED_TO_KOPERASI
	c.do("PUT", "/api/pledges/"+pledgeID, wargaTok, map[string]any{"status": "ACCEPTED"})
	c.do("PUT", "/api/pledges/"+pledgeID, wargaTok, map[string]any{"status": "DELIVERED_TO_KOPERASI"})

	// verifikasi terima → transaksi + komisi
	code, out = c.do("POST", "/api/pledges/"+pledgeID+"/verifikasi", buyerTok, map[string]any{"qty_received": 100})
	if code != 201 {
		t.Fatalf("verifikasi: %d %v", code, out)
	}
	tx := out["data"].(map[string]any)
	gross := tx["gross_amount"].(float64)
	fee := tx["koperasi_fee"].(float64)
	net := tx["net_amount"].(float64)
	if gross != 1_000_000 || fee+net != gross {
		t.Fatalf("transaksi tak konsisten: gross=%v fee=%v net=%v", gross, fee, net)
	}

	// demand detail → CLOSED
	code, out = c.do("GET", "/api/demands/"+demandID, "", nil)
	if code != 200 || out["data"].(map[string]any)["demand_status"] != "CLOSED" {
		t.Fatalf("demand harus CLOSED: %d %v", code, out)
	}

	// RBAC: warga buat demand → 403
	if code, _ = c.do("POST", "/api/demands", wargaTok, map[string]any{"item_name": "x", "total_qty": 1, "target_price_per_item": 1}); code != 403 {
		t.Fatalf("warga create demand harus 403, dapat %d", code)
	}
}
