#!/bin/sh
# Migrasi App DB (idempoten) → seed dari KDMP (dilewati bila sudah ada) → jalankan API.
set -e
echo "[entrypoint] migrate..."
/app/migrate
echo "[entrypoint] seed..."
/app/seed
echo "[entrypoint] start API..."
exec /app/api
