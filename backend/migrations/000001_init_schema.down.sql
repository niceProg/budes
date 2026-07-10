-- Rollback skema transaksional Budes App DB.
BEGIN;

DROP TABLE IF EXISTS disputes;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS fulfillments;
DROP TABLE IF EXISTS demands;
DROP TABLE IF EXISTS users;

DROP FUNCTION IF EXISTS set_updated_at();

COMMIT;
