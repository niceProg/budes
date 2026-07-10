// Package scheduler menjalankan tugas periodik latar belakang (mis. pengingat tenggat).
package scheduler

import (
	"context"
	"fmt"
	"log"
	"time"

	"budes/internal/notify"

	"github.com/jackc/pgx/v5/pgxpool"
)

// Scheduler menjalankan loop pengingat tenggat demand (H-24).
type Scheduler struct {
	pool     *pgxpool.Pool
	notifier *notify.Notifier
	interval time.Duration
}

// New membuat scheduler dengan interval cek default 1 jam.
func New(pool *pgxpool.Pool, notifier *notify.Notifier) *Scheduler {
	return &Scheduler{pool: pool, notifier: notifier, interval: time.Hour}
}

// Start menjalankan loop sampai ctx dibatalkan (jalan sekali di awal, lalu tiap interval).
func (s *Scheduler) Start(ctx context.Context) {
	log.Printf("scheduler: pengingat tenggat aktif (interval %s)", s.interval)
	t := time.NewTicker(s.interval)
	defer t.Stop()
	s.remindDeadlines(ctx)
	for {
		select {
		case <-ctx.Done():
			return
		case <-t.C:
			s.remindDeadlines(ctx)
		}
	}
}

// remindDeadlines mengirim pengingat untuk demand OPEN/PARTIAL yang tenggatnya <24 jam & belum diingatkan.
func (s *Scheduler) remindDeadlines(ctx context.Context) {
	rows, err := s.pool.Query(ctx, `
		SELECT id::text, item_name, total_qty, fulfilled_qty, deadline
		FROM demands
		WHERE demand_status IN ('OPEN','PARTIAL')
		  AND deadline IS NOT NULL
		  AND deadline BETWEEN now() AND now() + interval '24 hours'
		  AND deadline_reminded_at IS NULL`)
	if err != nil {
		log.Printf("scheduler: query tenggat gagal: %v", err)
		return
	}
	var ids []string
	for rows.Next() {
		var id, item string
		var total, fulfilled int
		var deadline time.Time
		if err := rows.Scan(&id, &item, &total, &fulfilled, &deadline); err != nil {
			log.Printf("scheduler: scan gagal: %v", err)
			continue
		}
		s.notifier.Broadcast(fmt.Sprintf(
			"⏰ *Pengingat Tenggat* — kebutuhan '%s' tinggal %d lagi (dari %d) dan tenggatnya %s. Ayo warga desa sanggupi sebelum terlambat!",
			item, total-fulfilled, total, deadline.Format("02 Jan 15:04")))
		ids = append(ids, id)
	}
	rows.Close()
	if err := rows.Err(); err != nil {
		log.Printf("scheduler: iterasi gagal: %v", err)
	}
	if len(ids) > 0 {
		if _, err := s.pool.Exec(ctx, `UPDATE demands SET deadline_reminded_at=now() WHERE id = ANY($1)`, ids); err != nil {
			log.Printf("scheduler: tandai reminded gagal: %v", err)
		}
		log.Printf("scheduler: %d pengingat tenggat dikirim", len(ids))
	}
}
