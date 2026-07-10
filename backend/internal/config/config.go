// Package config memuat konfigurasi aplikasi dari environment (dua-DB, PRD §5).
package config

import "os"

// Config menampung seluruh setting runtime backend Budes.
type Config struct {
	Port      string // port HTTP API
	AppDSN    string // App DB (writable) — transaksional
	RefDSN    string // Reference DB (read-only) — dataset KDMP
	JWTSecret string // kunci penandatangan JWT (HS256)
	WA        WAConfig
}

// WAConfig = konfigurasi gateway notifikasi WhatsApp (OpenWA).
type WAConfig struct {
	BaseURL     string // mis. http://localhost:3000 (tanpa trailing slash)
	Session     string // <id> sesi pada path /api/sessions/<id>/messages/send-text
	APIKey      string // header X-API-Key (RAHASIA — hanya dari env)
	GroupChatID string // chatId grup tujuan broadcast (mis. 1203...@g.us)
}

// Enabled: WA aktif bila base URL & API key terisi.
func (w WAConfig) Enabled() bool { return w.BaseURL != "" && w.APIKey != "" }

// Load membaca konfigurasi dari environment dengan fallback default dev lokal.
func Load() Config {
	return Config{
		Port:      getenv("PORT", "8080"),
		AppDSN:    getenv("APP_DATABASE_URL", "postgres://budes:budes@localhost:5434/budes_app"),
		RefDSN:    getenv("REF_DATABASE_URL", "postgres://budes:budes@localhost:5433/hackathon_2026"),
		JWTSecret: getenv("JWT_SECRET", "budes-dev-secret-change-me"),
		WA: WAConfig{
			BaseURL:     getenv("WA_BASE_URL", ""),
			Session:     getenv("WA_SESSION", "default"),
			APIKey:      getenv("WA_API_KEY", ""), // jangan hardcode: set via env
			GroupChatID: getenv("WA_GROUP_CHAT_ID", "120363428225078710@g.us"),
		},
	}
}

func getenv(key, def string) string {
	if v := os.Getenv(key); v != "" {
		return v
	}
	return def
}
