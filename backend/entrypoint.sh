#!/bin/sh
# Migrasi App DB (idempoten) → seed dari KDMP (opsional, dilewati bila sudah ada) → jalankan API.
# Skip migrate/seed dengan SKIP_MIGRATE=1 / SKIP_SEED=1 (mis. bila dijalankan sbagai Cloud Run Job terpisah).
set -e

if [ "${SKIP_MIGRATE:-0}" != "1" ]; then
  echo "[entrypoint] migrate..."
  /app/migrate                       # WAJIB sukses: skema App DB esensial
fi

if [ "${SKIP_SEED:-0}" != "1" ]; then
  echo "[entrypoint] seed..."
  /app/seed || echo "[entrypoint] WARN: seed gagal (lanjut) — cek REF DB / kredensial"
fi

echo "[entrypoint] start API di :${PORT:-8080}..."
exec /app/api
