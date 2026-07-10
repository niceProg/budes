#!/usr/bin/env bash
# Clone/refresh Reference DB KDMP (read-only) dari remote hackathon ke Postgres lokal (budes_ref_db).
# Kredensial remote TIDAK di-hardcode — set lewat environment.
#
# Contoh:
#   REMOTE_PASSWORD='***' ./deploy/sync-ref-db.sh
#
set -euo pipefail

REMOTE_HOST="${REMOTE_HOST:-34.101.155.200}"
REMOTE_PORT="${REMOTE_PORT:-5432}"
REMOTE_USER="${REMOTE_USER:-hackathon_participant_2026}"
REMOTE_DB="${REMOTE_DB:-hackathon_2026}"
: "${REMOTE_PASSWORD:?set REMOTE_PASSWORD (password DB hackathon remote)}"

LOCAL_HOST="${LOCAL_HOST:-localhost}"
LOCAL_PORT="${LOCAL_PORT:-5433}"     # port host budes_ref_db
LOCAL_USER="${LOCAL_USER:-budes}"
LOCAL_PASSWORD="${LOCAL_PASSWORD:-budes}"
LOCAL_DB="${LOCAL_DB:-hackathon_2026}"

DIR="$(cd "$(dirname "$0")" && pwd)/dumps"
mkdir -p "$DIR"
DUMP="$DIR/hackathon_2026.dump"

echo ">> pg_dump dari remote (SELECT-only, schema=public)..."
PGPASSWORD="$REMOTE_PASSWORD" pg_dump \
  -h "$REMOTE_HOST" -p "$REMOTE_PORT" -U "$REMOTE_USER" -d "$REMOTE_DB" \
  --schema=public --no-owner --no-privileges --no-acl -Fc -f "$DUMP"

echo ">> recreate DB lokal $LOCAL_DB..."
PGPASSWORD="$LOCAL_PASSWORD" psql -h "$LOCAL_HOST" -p "$LOCAL_PORT" -U "$LOCAL_USER" -d postgres \
  -c "DROP DATABASE IF EXISTS $LOCAL_DB;" -c "CREATE DATABASE $LOCAL_DB OWNER $LOCAL_USER;"

echo ">> pg_restore ke lokal..."
PGPASSWORD="$LOCAL_PASSWORD" pg_restore \
  -h "$LOCAL_HOST" -p "$LOCAL_PORT" -U "$LOCAL_USER" -d "$LOCAL_DB" \
  --no-owner --no-privileges "$DUMP" || true   # abaikan 1 warning "schema public already exists"

echo ">> selesai. Verifikasi jumlah tabel:"
PGPASSWORD="$LOCAL_PASSWORD" psql -h "$LOCAL_HOST" -p "$LOCAL_PORT" -U "$LOCAL_USER" -d "$LOCAL_DB" \
  -tAc "select count(*) || ' tabel' from information_schema.tables where table_schema='public' and table_type='BASE TABLE';"
