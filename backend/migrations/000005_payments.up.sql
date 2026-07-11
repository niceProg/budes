-- Tabel pembayaran gateway (Mayar) untuk DP demand & pelunasan transaksi.
CREATE TABLE IF NOT EXISTS payments (
    id                   uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    kind                 text NOT NULL CHECK (kind IN ('DP','DEMAND_TXN','SUPPLY_TXN')),
    ref_id               uuid NOT NULL,                 -- demand id / transaction id
    amount               numeric(14,2) NOT NULL,
    provider             text NOT NULL DEFAULT 'MAYAR',
    mayar_transaction_id text,
    mayar_link_id        text,
    link                 text,
    customer_email       text,
    status               text NOT NULL DEFAULT 'PENDING' CHECK (status IN ('PENDING','PAID','EXPIRED')),
    paid_at              timestamptz,
    created_by           uuid,
    tanggal_input        timestamptz NOT NULL DEFAULT now(),
    tanggal_update       timestamptz
);
CREATE INDEX IF NOT EXISTS idx_payments_mayar_txn  ON payments(mayar_transaction_id);
CREATE INDEX IF NOT EXISTS idx_payments_mayar_link ON payments(mayar_link_id);
CREATE INDEX IF NOT EXISTS idx_payments_ref        ON payments(kind, ref_id);
