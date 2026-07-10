// Command migrate menerapkan seluruh migrasi App DB (migrations/*.up.sql, berurutan).
// Semua migrasi ditulis idempoten (IF NOT EXISTS / CREATE OR REPLACE / ON CONFLICT),
// sehingga aman dijalankan berulang. Jalankan: go run ./cmd/migrate
package main

import (
	"context"
	"log"
	"os"
	"path/filepath"
	"sort"
	"time"

	"budes/internal/config"

	"github.com/jackc/pgx/v5/pgxpool"
)

func main() {
	cfg := config.Load()
	ctx, cancel := context.WithTimeout(context.Background(), 2*time.Minute)
	defer cancel()

	pool, err := pgxpool.New(ctx, cfg.AppDSN)
	if err != nil {
		log.Fatalf("koneksi App DB: %v", err)
	}
	defer pool.Close()

	files, err := filepath.Glob("migrations/*.up.sql")
	if err != nil {
		log.Fatalf("cari migrasi: %v", err)
	}
	sort.Strings(files)
	if len(files) == 0 {
		log.Println("migrate: tidak ada file migrasi")
		return
	}
	for _, f := range files {
		sql, err := os.ReadFile(f)
		if err != nil {
			log.Fatalf("baca %s: %v", f, err)
		}
		if _, err := pool.Exec(ctx, string(sql)); err != nil {
			log.Fatalf("terapkan %s: %v", f, err)
		}
		log.Printf("migrate: %s diterapkan", filepath.Base(f))
	}
}
