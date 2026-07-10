// Command api adalah entrypoint REST API Budes (arsitektur dua-DB, PRD §5).
package main

import (
	"context"
	"log"
	"net/http"
	"os/signal"
	"syscall"
	"time"

	"budes/internal/config"
	"budes/internal/db"
	"budes/internal/notify"
	"budes/internal/scheduler"
	"budes/internal/server"
)

func main() {
	cfg := config.Load()

	ctx, stop := signal.NotifyContext(context.Background(), syscall.SIGINT, syscall.SIGTERM)
	defer stop()

	pools, err := db.Open(ctx, cfg.AppDSN, cfg.RefDSN)
	if err != nil {
		log.Fatalf("gagal koneksi database: %v", err)
	}
	defer pools.Close()
	log.Println("terkoneksi: App DB + Reference DB")

	notifier := notify.New(cfg.WA)

	srv := &http.Server{
		Addr:              ":" + cfg.Port,
		Handler:           server.New(pools, cfg, notifier),
		ReadHeaderTimeout: 10 * time.Second,
	}

	// Tugas latar belakang: pengingat tenggat (H-24).
	go scheduler.New(pools.App, notifier).Start(ctx)

	go func() {
		log.Printf("API mendengarkan di :%s", cfg.Port)
		if err := srv.ListenAndServe(); err != nil && err != http.ErrServerClosed {
			log.Fatalf("server error: %v", err)
		}
	}()

	<-ctx.Done()
	log.Println("mematikan server...")
	shutCtx, cancel := context.WithTimeout(context.Background(), 5*time.Second)
	defer cancel()
	if err := srv.Shutdown(shutCtx); err != nil {
		log.Printf("shutdown error: %v", err)
	}
}
