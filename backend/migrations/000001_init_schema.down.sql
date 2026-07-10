-- Rollback skema hub-koperasi dua-alur.
BEGIN;

DROP TABLE IF EXISTS disputes            CASCADE;
DROP TABLE IF EXISTS supply_transactions CASCADE;
DROP TABLE IF EXISTS demand_transactions CASCADE;
DROP TABLE IF EXISTS orders              CASCADE;
DROP TABLE IF EXISTS supply_listings     CASCADE;
DROP TABLE IF EXISTS demand_pledges      CASCADE;
DROP TABLE IF EXISTS demands             CASCADE;
DROP TABLE IF EXISTS komoditas           CASCADE;
DROP TABLE IF EXISTS users               CASCADE;
DROP TABLE IF EXISTS koperasi            CASCADE;

DROP FUNCTION IF EXISTS set_tanggal_update();

COMMIT;
