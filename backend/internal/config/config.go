// Package config memuat konfigurasi aplikasi dari environment (dua-DB, PRD §5).
package config

import "os"

// Config menampung seluruh setting runtime backend Budes.
type Config struct {
	Port   string // port HTTP API
	AppDSN string // App DB (writable) — transaksional
	RefDSN string // Reference DB (read-only) — dataset KDMP
}

// Load membaca konfigurasi dari environment dengan fallback default dev lokal.
func Load() Config {
	return Config{
		Port:   getenv("PORT", "8080"),
		AppDSN: getenv("APP_DATABASE_URL", "postgres://budes:budes@localhost:5434/budes_app"),
		RefDSN: getenv("REF_DATABASE_URL", "postgres://budes:budes@localhost:5433/hackathon_2026"),
	}
}

func getenv(key, def string) string {
	if v := os.Getenv(key); v != "" {
		return v
	}
	return def
}
