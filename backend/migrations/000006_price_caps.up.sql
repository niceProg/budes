-- Batas harga komoditas (anti mark-up) yang diatur ADMIN_KOPERASI.
CREATE TABLE IF NOT EXISTS price_caps (
    id            uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    komoditas     text NOT NULL,
    satuan        text NOT NULL DEFAULT 'kg',
    max_jual      numeric(14,2) NOT NULL DEFAULT 0,
    max_beli      numeric(14,2) NOT NULL DEFAULT 0,
    tanggal_input timestamptz NOT NULL DEFAULT now(),
    user_input    uuid
);
