// Package db mengelola dua connection pool: App DB (read-write) & Reference DB (read-only).
package db

import (
	"context"
	"fmt"

	"github.com/jackc/pgx/v5"
	"github.com/jackc/pgx/v5/pgxpool"
)

// Pools menampung kedua pool Postgres sesuai arsitektur dua-DB (PRD §5).
type Pools struct {
	App *pgxpool.Pool // writable — users/demands/fulfillments/transactions/disputes
	Ref *pgxpool.Pool // read-only — dataset KDMP
}

// Open membuka & mem-ping kedua pool. Pool Ref dipaksa read-only di level koneksi
// (SET default_transaction_read_only) sebagai pengaman ekstra terhadap tulis tak sengaja.
func Open(ctx context.Context, appDSN, refDSN string) (*Pools, error) {
	app, err := pgxpool.New(ctx, appDSN)
	if err != nil {
		return nil, fmt.Errorf("app pool: %w", err)
	}

	refCfg, err := pgxpool.ParseConfig(refDSN)
	if err != nil {
		app.Close()
		return nil, fmt.Errorf("ref config: %w", err)
	}
	refCfg.AfterConnect = func(ctx context.Context, c *pgx.Conn) error {
		_, err := c.Exec(ctx, "SET default_transaction_read_only = on")
		return err
	}
	ref, err := pgxpool.NewWithConfig(ctx, refCfg)
	if err != nil {
		app.Close()
		return nil, fmt.Errorf("ref pool: %w", err)
	}

	if err := app.Ping(ctx); err != nil {
		app.Close()
		ref.Close()
		return nil, fmt.Errorf("app ping: %w", err)
	}
	if err := ref.Ping(ctx); err != nil {
		app.Close()
		ref.Close()
		return nil, fmt.Errorf("ref ping: %w", err)
	}
	return &Pools{App: app, Ref: ref}, nil
}

// Close menutup kedua pool.
func (p *Pools) Close() {
	p.App.Close()
	p.Ref.Close()
}
