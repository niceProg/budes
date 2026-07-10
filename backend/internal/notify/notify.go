// Package notify mengirim notifikasi WhatsApp lewat gateway OpenWA.
// Endpoint: POST {BaseURL}/api/sessions/{Session}/messages/send-text
// Header:   X-API-Key: {APIKey}
// Body:     {"chatId": "...", "text": "..."}
package notify

import (
	"bytes"
	"context"
	"encoding/json"
	"fmt"
	"io"
	"log"
	"net/http"
	"time"

	"budes/internal/config"
)

// Notifier mengirim pesan ke gateway WhatsApp. Aman dipakai walau nonaktif (no-op).
type Notifier struct {
	cfg    config.WAConfig
	client *http.Client
}

// New membuat notifier. Bila WA tidak dikonfigurasi, semua kirim jadi no-op.
func New(cfg config.WAConfig) *Notifier {
	if !cfg.Enabled() {
		log.Println("notify: WhatsApp gateway NONAKTIF (WA_BASE_URL/WA_API_KEY kosong) — pesan di-skip")
	} else {
		log.Printf("notify: WhatsApp gateway aktif (session=%s, grup=%s)", cfg.Session, cfg.GroupChatID)
	}
	return &Notifier{cfg: cfg, client: &http.Client{Timeout: 10 * time.Second}}
}

// SendText mengirim teks ke sebuah chatId (blocking). Kembalikan error bila gagal.
func (n *Notifier) SendText(ctx context.Context, chatID, text string) error {
	if !n.cfg.Enabled() {
		return nil
	}
	url := fmt.Sprintf("%s/api/sessions/%s/messages/send-text", n.cfg.BaseURL, n.cfg.Session)
	payload, _ := json.Marshal(map[string]string{"chatId": chatID, "text": text})

	req, err := http.NewRequestWithContext(ctx, http.MethodPost, url, bytes.NewReader(payload))
	if err != nil {
		return err
	}
	req.Header.Set("Content-Type", "application/json")
	req.Header.Set("X-API-Key", n.cfg.APIKey)

	resp, err := n.client.Do(req)
	if err != nil {
		return err
	}
	defer resp.Body.Close()
	if resp.StatusCode >= 300 {
		body, _ := io.ReadAll(io.LimitReader(resp.Body, 512))
		return fmt.Errorf("gateway WA status %d: %s", resp.StatusCode, string(body))
	}
	return nil
}

// TestHandler: POST /api/notify/test body {"chat_id":"(opsional)","text":"..."} — kirim SINKRON
// agar respons/kesalahan gateway langsung terlihat saat pengujian.
func (n *Notifier) TestHandler() http.HandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {
		var in struct {
			ChatID string `json:"chat_id"`
			Text   string `json:"text"`
		}
		_ = json.NewDecoder(r.Body).Decode(&in)
		if in.Text == "" {
			in.Text = "Tes notifikasi Bursa Desa ✅"
		}
		chat := in.ChatID
		if chat == "" {
			chat = n.cfg.GroupChatID
		}
		w.Header().Set("Content-Type", "application/json; charset=utf-8")
		if !n.cfg.Enabled() {
			w.WriteHeader(http.StatusServiceUnavailable)
			_ = json.NewEncoder(w).Encode(map[string]any{"error": "WA gateway nonaktif (set WA_BASE_URL & WA_API_KEY)"})
			return
		}
		if err := n.SendText(r.Context(), chat, in.Text); err != nil {
			w.WriteHeader(http.StatusBadGateway)
			_ = json.NewEncoder(w).Encode(map[string]any{"error": err.Error()})
			return
		}
		_ = json.NewEncoder(w).Encode(map[string]any{"data": "terkirim", "chat_id": chat})
	}
}

// Broadcast mengirim teks ke grup default secara fire-and-forget (tak memblok request).
func (n *Notifier) Broadcast(text string) {
	n.fire(n.cfg.GroupChatID, text)
}

// NotifyPhone mengirim ke nomor pribadi (fire-and-forget). No-op bila phone kosong.
// chatId WA pribadi = "<digit>@c.us".
func (n *Notifier) NotifyPhone(phone, text string) {
	digits := onlyDigits(phone)
	if digits == "" {
		return
	}
	n.fire(digits+"@c.us", text)
}

func (n *Notifier) fire(chatID, text string) {
	if !n.cfg.Enabled() || chatID == "" {
		return
	}
	go func() {
		ctx, cancel := context.WithTimeout(context.Background(), 10*time.Second)
		defer cancel()
		if err := n.SendText(ctx, chatID, text); err != nil {
			log.Printf("notify: gagal kirim WA ke %s: %v", chatID, err)
		}
	}()
}

func onlyDigits(s string) string {
	out := make([]byte, 0, len(s))
	for i := 0; i < len(s); i++ {
		if s[i] >= '0' && s[i] <= '9' {
			out = append(out, s[i])
		}
	}
	return string(out)
}
