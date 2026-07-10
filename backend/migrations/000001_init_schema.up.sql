-- Budes App DB — skema transaksional (PRD §6 + §6b).
-- Kolom *_ref / *_sample_id = SOFT REFERENCE ke Reference DB KDMP (bukan FK lintas-DB;
-- divalidasi & di-resolve di aplikasi lewat Reference Data Service).
-- gen_random_uuid() tersedia di core PostgreSQL 13+ (tak perlu extension).

BEGIN;

-- Auto-update updated_at pada setiap UPDATE.
CREATE OR REPLACE FUNCTION set_updated_at() RETURNS trigger AS $$
BEGIN
    NEW.updated_at = now();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- users -----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id            uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    name          text NOT NULL,
    email         text NOT NULL UNIQUE,
    password_hash text NOT NULL,
    role          text NOT NULL CHECK (role IN ('BUYER','PRODUCER','KOPERASI')),
    wallet_balance numeric(18,2) NOT NULL DEFAULT 0 CHECK (wallet_balance >= 0),
    koperasi_ref  text,   -- soft ref → KDMP referensi_koperasi_wilayah.koperasi_ref
    anggota_ref   text,   -- soft ref → KDMP anggota_koperasi.anggota_ref
    created_at    timestamptz NOT NULL DEFAULT now(),
    updated_at    timestamptz NOT NULL DEFAULT now()
);

-- demands (kebutuhan pembeli) -------------------------------------------
CREATE TABLE IF NOT EXISTS demands (
    id             uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    buyer_id       uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    item_name      text NOT NULL,
    total_qty      integer NOT NULL CHECK (total_qty > 0),
    fulfilled_qty  integer NOT NULL DEFAULT 0 CHECK (fulfilled_qty >= 0),
    price_per_item numeric(18,2) NOT NULL CHECK (price_per_item >= 0),
    deadline       timestamptz,
    status         text NOT NULL DEFAULT 'OPEN' CHECK (status IN ('OPEN','PARTIAL','CLOSED')),
    kode_wilayah   text,   -- soft ref → KDMP referensi_wilayah.kode_wilayah
    komoditas_ref  text,   -- soft ref → KDMP referensi_komoditas_desa.komoditas_ref
    created_at     timestamptz NOT NULL DEFAULT now(),
    updated_at     timestamptz NOT NULL DEFAULT now(),
    CONSTRAINT demands_fulfilled_not_over CHECK (fulfilled_qty <= total_qty)
);

-- fulfillments (sanggupan produsen) ------------------------------------
CREATE TABLE IF NOT EXISTS fulfillments (
    id               uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    demand_id        uuid NOT NULL REFERENCES demands(id) ON DELETE CASCADE,
    producer_id      uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    qty_pledged      integer NOT NULL CHECK (qty_pledged > 0),
    qty_received     integer NOT NULL DEFAULT 0 CHECK (qty_received >= 0),
    status           text NOT NULL DEFAULT 'PENDING' CHECK (status IN ('PENDING','SHIPPED','RECEIVED','DISPUTED')),
    koperasi_ref     text,   -- soft ref → koperasi penjamin/pemfasilitasi
    produk_sample_id text,   -- soft ref → KDMP produk_koperasi.produk_sample_id
    created_at       timestamptz NOT NULL DEFAULT now(),
    updated_at       timestamptz NOT NULL DEFAULT now(),
    CONSTRAINT fulfillments_received_not_over CHECK (qty_received <= qty_pledged)
);

-- transactions (escrow ledger) -----------------------------------------
CREATE TABLE IF NOT EXISTS transactions (
    id             uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    fulfillment_id uuid NOT NULL REFERENCES fulfillments(id) ON DELETE CASCADE,
    amount         numeric(18,2) NOT NULL CHECK (amount >= 0),
    koperasi_fee   numeric(18,2) NOT NULL DEFAULT 0 CHECK (koperasi_fee >= 0),
    net_amount     numeric(18,2) NOT NULL CHECK (net_amount >= 0),
    status         text NOT NULL DEFAULT 'ON_HOLD' CHECK (status IN ('ON_HOLD','RELEASED','REFUNDED')),
    created_at     timestamptz NOT NULL DEFAULT now(),
    updated_at     timestamptz NOT NULL DEFAULT now()
);

-- disputes (sengketa / lapor masalah) ----------------------------------
CREATE TABLE IF NOT EXISTS disputes (
    id             uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    fulfillment_id uuid NOT NULL REFERENCES fulfillments(id) ON DELETE CASCADE,
    reported_by    uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    reason         text NOT NULL,
    status         text NOT NULL DEFAULT 'OPEN' CHECK (status IN ('OPEN','RESOLVED')),
    resolution     text,
    created_at     timestamptz NOT NULL DEFAULT now(),
    updated_at     timestamptz NOT NULL DEFAULT now()
);

-- indexes ---------------------------------------------------------------
CREATE INDEX IF NOT EXISTS idx_demands_status       ON demands (status);
CREATE INDEX IF NOT EXISTS idx_demands_kode_wilayah ON demands (kode_wilayah);
CREATE INDEX IF NOT EXISTS idx_demands_buyer        ON demands (buyer_id);
CREATE INDEX IF NOT EXISTS idx_fulfillments_demand  ON fulfillments (demand_id);
CREATE INDEX IF NOT EXISTS idx_fulfillments_producer ON fulfillments (producer_id);
CREATE INDEX IF NOT EXISTS idx_fulfillments_status  ON fulfillments (status);
CREATE INDEX IF NOT EXISTS idx_transactions_fulfillment ON transactions (fulfillment_id);
CREATE INDEX IF NOT EXISTS idx_disputes_fulfillment ON disputes (fulfillment_id);

-- updated_at triggers ---------------------------------------------------
CREATE TRIGGER trg_users_updated        BEFORE UPDATE ON users        FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_demands_updated      BEFORE UPDATE ON demands      FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_fulfillments_updated BEFORE UPDATE ON fulfillments FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_transactions_updated BEFORE UPDATE ON transactions FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_disputes_updated     BEFORE UPDATE ON disputes     FOR EACH ROW EXECUTE FUNCTION set_updated_at();

COMMIT;
