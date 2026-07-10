-- Budes App DB — skema hub-koperasi dua-alur (PRD §6 + ERD erd_marketplace_koperasi.mermaid).
-- Kolom *_ref = SOFT REFERENCE ke Reference DB KDMP (bukan FK lintas-DB; di-seed & divalidasi di aplikasi).
-- gen_random_uuid() tersedia di core PostgreSQL 13+.

BEGIN;

-- Auto-update tanggal_update pada setiap UPDATE.
CREATE OR REPLACE FUNCTION set_tanggal_update() RETURNS trigger AS $$
BEGIN
    NEW.tanggal_update = now();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- koperasi (hub) — seed dari KDMP profil_koperasi × wilayah -------------
CREATE TABLE IF NOT EXISTS koperasi (
    id           uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    koperasi_ref text UNIQUE,                 -- soft ref → KDMP referensi_koperasi_wilayah.koperasi_ref
    nama         text NOT NULL,
    desa         text,
    wilayah      text,
    kontak       text,
    status       smallint NOT NULL DEFAULT 1, -- 1=aktif, 9=hapus
    created_at   timestamptz NOT NULL DEFAULT now()
);

-- users (3 peran) — WARGA di-seed/ditaut dari KDMP anggota_koperasi -----
CREATE TABLE IF NOT EXISTS users (
    id            uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    koperasi_id   uuid REFERENCES koperasi(id) ON DELETE SET NULL, -- null bila buyer eksternal
    anggota_ref   text,                        -- soft ref → KDMP anggota_koperasi.anggota_ref
    name          text NOT NULL,
    phone         text,
    email         text UNIQUE,
    password_hash text,
    role          text NOT NULL CHECK (role IN ('BUYER','WARGA','ADMIN_KOPERASI')),
    verification_status text NOT NULL DEFAULT 'UNVERIFIED' CHECK (verification_status IN ('UNVERIFIED','PENDING','VERIFIED','REJECTED')),
    verified_at   timestamptz,
    status        smallint NOT NULL DEFAULT 1,
    created_at    timestamptz NOT NULL DEFAULT now()
);

-- verifications (KYC) — diajukan user, ditinjau ADMIN_KOPERASI ----------
CREATE TABLE IF NOT EXISTS verifications (
    id               uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id          uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    nik              text,
    id_card_file     text,                        -- path/URL foto KTP
    support_doc_file text,                        -- dokumen pendukung (opsional)
    status           text NOT NULL DEFAULT 'PENDING' CHECK (status IN ('PENDING','VERIFIED','REJECTED')),
    reviewed_by      uuid REFERENCES users(id) ON DELETE SET NULL, -- ADMIN_KOPERASI
    review_note      text,
    reviewed_at      timestamptz,
    user_input       text, tanggal_input  timestamptz NOT NULL DEFAULT now(),
    user_update      text, tanggal_update timestamptz NOT NULL DEFAULT now()
);

-- komoditas (master) — seed dari KDMP referensi_komoditas_desa ---------
CREATE TABLE IF NOT EXISTS komoditas (
    id           uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    komoditas_ref text,                        -- soft ref → KDMP referensi_komoditas_desa.komoditas_ref
    nama         text NOT NULL,
    kategori     text,                          -- diderivasi dari nama saat seed
    satuan       text
);

-- ====================== ALUR A: DEMAND ======================
CREATE TABLE IF NOT EXISTS demands (
    id                     uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    buyer_id               uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    koperasi_id            uuid REFERENCES koperasi(id) ON DELETE SET NULL,
    komoditas_id           uuid REFERENCES komoditas(id) ON DELETE SET NULL,
    item_name              text NOT NULL,
    satuan                 text,
    total_qty              integer NOT NULL CHECK (total_qty > 0),
    fulfilled_qty          integer NOT NULL DEFAULT 0 CHECK (fulfilled_qty >= 0),
    target_price_per_item  numeric(18,2) CHECK (target_price_per_item >= 0),
    -- uang muka (DP) --
    total_price            numeric(18,2) CHECK (total_price >= 0),
    dp_percent             numeric(5,2) NOT NULL DEFAULT 30 CHECK (dp_percent >= 0 AND dp_percent <= 100),
    dp_amount              numeric(18,2) CHECK (dp_amount >= 0),
    remaining_amount       numeric(18,2) CHECK (remaining_amount >= 0),
    dp_payment_method      text CHECK (dp_payment_method IN ('CASH','TRANSFER')),
    dp_status              text NOT NULL DEFAULT 'UNPAID' CHECK (dp_status IN ('UNPAID','PAID','FORFEITED','REFUNDED')),
    dp_paid_at             timestamptz,
    deadline               timestamptz,
    demand_status          text NOT NULL DEFAULT 'DRAFT' CHECK (demand_status IN ('DRAFT','OPEN','PARTIAL','CLOSED','EXPIRED')),
    user_input     text, tanggal_input  timestamptz NOT NULL DEFAULT now(),
    user_update    text, tanggal_update timestamptz NOT NULL DEFAULT now(),
    CONSTRAINT demands_fulfilled_not_over CHECK (fulfilled_qty <= total_qty)
);

CREATE TABLE IF NOT EXISTS demand_pledges (
    id             uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    demand_id      uuid NOT NULL REFERENCES demands(id) ON DELETE CASCADE,
    warga_id       uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    qty_pledged    integer NOT NULL CHECK (qty_pledged > 0),
    qty_delivered  integer NOT NULL DEFAULT 0 CHECK (qty_delivered >= 0),
    price_per_item numeric(18,2) CHECK (price_per_item >= 0),
    pledge_status  text NOT NULL DEFAULT 'PENDING' CHECK (pledge_status IN ('PENDING','ACCEPTED','DELIVERED_TO_KOPERASI','HANDED_TO_BUYER','CANCELLED')),
    user_input     text, tanggal_input  timestamptz NOT NULL DEFAULT now(),
    user_update    text, tanggal_update timestamptz NOT NULL DEFAULT now(),
    CONSTRAINT pledges_delivered_not_over CHECK (qty_delivered <= qty_pledged)
);

-- ====================== ALUR B: SUPPLY ======================
CREATE TABLE IF NOT EXISTS supply_listings (
    id             uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    koperasi_id    uuid REFERENCES koperasi(id) ON DELETE SET NULL,
    warga_id       uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    komoditas_id   uuid REFERENCES komoditas(id) ON DELETE SET NULL,
    item_name      text NOT NULL,
    satuan         text,
    qty_available  integer NOT NULL CHECK (qty_available >= 0),
    qty_sold       integer NOT NULL DEFAULT 0 CHECK (qty_sold >= 0),
    price_per_item numeric(18,2) NOT NULL CHECK (price_per_item >= 0),
    listing_status text NOT NULL DEFAULT 'DRAFT' CHECK (listing_status IN ('DRAFT','POSTED','SOLD_OUT','CLOSED')),
    user_input     text, tanggal_input  timestamptz NOT NULL DEFAULT now(),
    user_update    text, tanggal_update timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS orders (
    id             uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    listing_id     uuid NOT NULL REFERENCES supply_listings(id) ON DELETE CASCADE,
    buyer_id       uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    qty_ordered    integer NOT NULL CHECK (qty_ordered > 0),
    price_per_item numeric(18,2) NOT NULL CHECK (price_per_item >= 0),
    total_amount   numeric(18,2) NOT NULL CHECK (total_amount >= 0),
    order_status   text NOT NULL DEFAULT 'PENDING' CHECK (order_status IN ('PENDING','CONFIRMED','HANDED_OVER','CANCELLED')),
    user_input     text, tanggal_input  timestamptz NOT NULL DEFAULT now(),
    user_update    text, tanggal_update timestamptz NOT NULL DEFAULT now()
);

-- ============ CATATAN TRANSAKSI OFFLINE (per alur) ============
CREATE TABLE IF NOT EXISTS demand_transactions (
    id               uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    demand_pledge_id uuid NOT NULL REFERENCES demand_pledges(id) ON DELETE CASCADE,
    gross_amount     numeric(18,2) NOT NULL CHECK (gross_amount >= 0),
    koperasi_fee     numeric(18,2) NOT NULL DEFAULT 0 CHECK (koperasi_fee >= 0),
    net_amount       numeric(18,2) NOT NULL CHECK (net_amount >= 0),
    payment_method   text CHECK (payment_method IN ('CASH','TRANSFER')),
    payment_status   text NOT NULL DEFAULT 'UNPAID' CHECK (payment_status IN ('UNPAID','PAID','SETTLED')),
    paid_at          timestamptz,
    user_input       text, tanggal_input  timestamptz NOT NULL DEFAULT now(),
    user_update      text, tanggal_update timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS supply_transactions (
    id             uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id       uuid NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    gross_amount   numeric(18,2) NOT NULL CHECK (gross_amount >= 0),
    koperasi_fee   numeric(18,2) NOT NULL DEFAULT 0 CHECK (koperasi_fee >= 0),
    net_amount     numeric(18,2) NOT NULL CHECK (net_amount >= 0),
    payment_method text CHECK (payment_method IN ('CASH','TRANSFER')),
    payment_status text NOT NULL DEFAULT 'UNPAID' CHECK (payment_status IN ('UNPAID','PAID','SETTLED')),
    paid_at        timestamptz,
    user_input     text, tanggal_input  timestamptz NOT NULL DEFAULT now(),
    user_update    text, tanggal_update timestamptz NOT NULL DEFAULT now()
);

-- ============ SENGKETA (lintas alur) ============
CREATE TABLE IF NOT EXISTS disputes (
    id               uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    demand_pledge_id uuid REFERENCES demand_pledges(id) ON DELETE CASCADE,
    order_id         uuid REFERENCES orders(id) ON DELETE CASCADE,
    source_type      text NOT NULL CHECK (source_type IN ('DEMAND','SUPPLY')),
    reported_by      uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    reason           text NOT NULL,
    dispute_status   text NOT NULL DEFAULT 'OPEN' CHECK (dispute_status IN ('OPEN','REVIEW','RESOLVED')),
    resolution       text,
    resolved_at      timestamptz,
    user_input       text, tanggal_input  timestamptz NOT NULL DEFAULT now(),
    user_update      text, tanggal_update timestamptz NOT NULL DEFAULT now(),
    CONSTRAINT disputes_exactly_one_source CHECK ((demand_pledge_id IS NOT NULL) <> (order_id IS NOT NULL))
);

-- indexes ---------------------------------------------------------------
CREATE INDEX IF NOT EXISTS idx_users_koperasi     ON users (koperasi_id);
CREATE INDEX IF NOT EXISTS idx_verifications_user ON verifications (user_id);
CREATE INDEX IF NOT EXISTS idx_verifications_status ON verifications (status);
CREATE INDEX IF NOT EXISTS idx_komoditas_nama     ON komoditas (nama);
CREATE INDEX IF NOT EXISTS idx_demands_status     ON demands (demand_status);
CREATE INDEX IF NOT EXISTS idx_demands_koperasi   ON demands (koperasi_id);
CREATE INDEX IF NOT EXISTS idx_demands_komoditas  ON demands (komoditas_id);
CREATE INDEX IF NOT EXISTS idx_pledges_demand     ON demand_pledges (demand_id);
CREATE INDEX IF NOT EXISTS idx_pledges_warga      ON demand_pledges (warga_id);
CREATE INDEX IF NOT EXISTS idx_listings_status    ON supply_listings (listing_status);
CREATE INDEX IF NOT EXISTS idx_listings_komoditas ON supply_listings (komoditas_id);
CREATE INDEX IF NOT EXISTS idx_orders_listing     ON orders (listing_id);
CREATE INDEX IF NOT EXISTS idx_orders_buyer       ON orders (buyer_id);
CREATE INDEX IF NOT EXISTS idx_dtx_pledge         ON demand_transactions (demand_pledge_id);
CREATE INDEX IF NOT EXISTS idx_stx_order          ON supply_transactions (order_id);
CREATE INDEX IF NOT EXISTS idx_disputes_pledge    ON disputes (demand_pledge_id);
CREATE INDEX IF NOT EXISTS idx_disputes_order     ON disputes (order_id);

-- tanggal_update triggers ----------------------------------------------
CREATE TRIGGER trg_verifications_upd BEFORE UPDATE ON verifications      FOR EACH ROW EXECUTE FUNCTION set_tanggal_update();
CREATE TRIGGER trg_demands_upd      BEFORE UPDATE ON demands             FOR EACH ROW EXECUTE FUNCTION set_tanggal_update();
CREATE TRIGGER trg_pledges_upd      BEFORE UPDATE ON demand_pledges      FOR EACH ROW EXECUTE FUNCTION set_tanggal_update();
CREATE TRIGGER trg_listings_upd     BEFORE UPDATE ON supply_listings     FOR EACH ROW EXECUTE FUNCTION set_tanggal_update();
CREATE TRIGGER trg_orders_upd       BEFORE UPDATE ON orders              FOR EACH ROW EXECUTE FUNCTION set_tanggal_update();
CREATE TRIGGER trg_dtx_upd          BEFORE UPDATE ON demand_transactions FOR EACH ROW EXECUTE FUNCTION set_tanggal_update();
CREATE TRIGGER trg_stx_upd          BEFORE UPDATE ON supply_transactions FOR EACH ROW EXECUTE FUNCTION set_tanggal_update();
CREATE TRIGGER trg_disputes_upd     BEFORE UPDATE ON disputes            FOR EACH ROW EXECUTE FUNCTION set_tanggal_update();

COMMIT;
