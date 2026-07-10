// Command seed mengisi App DB dari dataset KDMP (Reference DB, read-only).
// Jalankan: go run ./cmd/seed
package main

import (
	"context"
	"log"
	"time"

	"budes/internal/config"
	"budes/internal/db"
	"budes/internal/seed"
)

func main() {
	cfg := config.Load()

	ctx, cancel := context.WithTimeout(context.Background(), 5*time.Minute)
	defer cancel()

	pools, err := db.Open(ctx, cfg.AppDSN, cfg.RefDSN)
	if err != nil {
		log.Fatalf("koneksi database: %v", err)
	}
	defer pools.Close()

	log.Println("mulai seed dari KDMP → App DB...")
	res, err := seed.Run(ctx, pools)
	if err != nil {
		log.Fatalf("seed gagal: %v", err)
	}
	log.Printf("seed selesai: koperasi=%d komoditas=%d warga=%d (login warga demo: warga1..%d@budes.desa / budes123)",
		res.Koperasi, res.Komoditas, res.Warga, res.Warga)
}
