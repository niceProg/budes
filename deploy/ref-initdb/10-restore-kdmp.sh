#!/bin/bash
# Dijalankan Postgres HANYA saat inisialisasi pertama (volume kosong).
# Merestore dump dataset KDMP ke Reference DB bila dump tersedia.
set -e
DUMP=/dumps/hackathon_2026.dump
if [ -f "$DUMP" ]; then
  echo "[ref-initdb] merestore KDMP dari $DUMP ..."
  pg_restore -U "$POSTGRES_USER" -d "$POSTGRES_DB" --no-owner --no-privileges "$DUMP" \
    || echo "[ref-initdb] pg_restore selesai (sebagian warning diabaikan)"
  echo "[ref-initdb] restore KDMP selesai."
else
  echo "[ref-initdb] $DUMP tidak ada — lewati (sediakan via deploy/sync-ref-db.sh)."
fi
