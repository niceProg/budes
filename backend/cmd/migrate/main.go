// Command migrate menerapkan skema App DB (idempoten: dilewati bila tabel sudah ada).
// Jalankan: go run ./cmd/migrate
package main

import (
	"context"
	"log"
	"os"
	"time"

	"budes/internal/config"

	"github.com/jackc/pgx/v5/pgxpool"
)

const upFile = "migrations/000001_init_schema.up.sql"

func main() {
	cfg := config.Load()
	ctx, cancel := context.WithTimeout(context.Background(), 2*time.Minute)
	defer cancel()

	pool, err := pgxpool.New(ctx, cfg.AppDSN)
	if err != nil {
		log.Fatalf("koneksi App DB: %v", err)
	}
	defer pool.Close()

	var exists bool
	if err := pool.QueryRow(ctx,
		`SELECT EXISTS(SELECT 1 FROM information_schema.tables WHERE table_schema='public' AND table_name='koperasi')`).
		Scan(&exists); err != nil {
		log.Fatalf("cek skema: %v", err)
	}
	if exists {
		log.Println("migrate: skema sudah ada — dilewati")
		return
	}

	sql, err := os.ReadFile(upFile)
	if err != nil {
		log.Fatalf("baca %s: %v", upFile, err)
	}
	if _, err := pool.Exec(ctx, string(sql)); err != nil {
		log.Fatalf("terapkan migrasi: %v", err)
	}
	log.Println("migrate: skema diterapkan")
}
