// Package config memuat konfigurasi aplikasi dari environment (dua-DB, PRD §5).
package config

import (
	"bufio"
	"os"
	"strings"
)

// Config menampung seluruh setting runtime backend Budes.
type Config struct {
	Port      string // port HTTP API
	AppDSN    string // App DB (writable) — transaksional
	RefDSN    string // Reference DB (read-only) — dataset KDMP
	JWTSecret   string // kunci penandatangan JWT (HS256)
	FrontendURL string // base URL frontend (untuk redirect pembayaran)
	WA          WAConfig
	Mayar       MayarConfig
}

// MayarConfig = konfigurasi gateway pembayaran Mayar.
type MayarConfig struct {
	BaseURL string // mis. https://api.mayar.club/hl/v1 (sandbox)
	APIKey  string // RAHASIA — hanya dari env
}

// Enabled: gateway aktif bila API key terisi.
func (m MayarConfig) Enabled() bool { return m.APIKey != "" }

// WAConfig = konfigurasi gateway notifikasi WhatsApp (OpenWA).
type WAConfig struct {
	BaseURL     string // mis. http://localhost:3000 (tanpa trailing slash)
	Session     string // <id> sesi pada path /api/sessions/<id>/messages/send-text
	APIKey      string // header X-API-Key (RAHASIA — hanya dari env)
	GroupChatID string // chatId grup tujuan broadcast (mis. 1203...@g.us)
}

// Enabled: WA aktif bila base URL & API key terisi.
func (w WAConfig) Enabled() bool { return w.BaseURL != "" && w.APIKey != "" }

// Load membaca konfigurasi dari environment (memuat .env bila ada) + fallback dev lokal.
func Load() Config {
	loadDotEnv(".env")
	return Config{
		Port:      getenv("PORT", "8080"),
		AppDSN:    getenv("APP_DATABASE_URL", "postgres://budes:budes@localhost:5434/budes_app"),
		RefDSN:    getenv("REF_DATABASE_URL", "postgres://budes:budes@localhost:5433/hackathon_2026"),
		JWTSecret:   getenv("JWT_SECRET", "budes-dev-secret-change-me"),
		FrontendURL: strings.TrimRight(getenv("FRONTEND_BASE_URL", "https://budes.yum-dev.com"), "/"),
		Mayar: MayarConfig{
			BaseURL: strings.TrimRight(getenv("MAYAR_BASE_URL", "https://api.mayar.club/hl/v1"), "/"),
			APIKey:  getenv("MAYAR_API_KEY", ""),
		},
		WA: WAConfig{
			BaseURL:     strings.TrimRight(getenv("WA_BASE_URL", ""), "/"), // buang trailing slash
			Session:     getenv("WA_SESSION", "default"),
			APIKey:      getenv("WA_API_KEY", ""), // jangan hardcode: set via env
			GroupChatID: getenv("WA_GROUP_CHAT_ID", "120363428225078710@g.us"),
		},
	}
}

// loadDotEnv memuat file .env (KEY=VALUE) tanpa menimpa env yang sudah ada. No-op bila tak ada.
func loadDotEnv(path string) {
	f, err := os.Open(path)
	if err != nil {
		return
	}
	defer f.Close()
	sc := bufio.NewScanner(f)
	for sc.Scan() {
		line := strings.TrimSpace(sc.Text())
		if line == "" || strings.HasPrefix(line, "#") {
			continue
		}
		k, v, ok := strings.Cut(line, "=")
		if !ok {
			continue
		}
		k = strings.TrimSpace(k)
		v = strings.Trim(strings.TrimSpace(v), `"'`)
		if _, exists := os.LookupEnv(k); !exists {
			_ = os.Setenv(k, v)
		}
	}
}

func getenv(key, def string) string {
	if v := os.Getenv(key); v != "" {
		return v
	}
	return def
}
