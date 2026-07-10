-- Pengaturan aplikasi (key-value) — mis. komisi koperasi yang bisa diedit admin.
BEGIN;

CREATE TABLE IF NOT EXISTS settings (
    key        text PRIMARY KEY,
    value      text NOT NULL,
    updated_at timestamptz NOT NULL DEFAULT now(),
    user_update text
);

-- Default komisi koperasi 5% (persen).
INSERT INTO settings (key, value) VALUES ('koperasi_fee_percent', '5')
ON CONFLICT (key) DO NOTHING;

COMMIT;
