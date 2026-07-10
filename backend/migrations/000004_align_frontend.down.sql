-- Kembalikan enum ke nilai lama (drop → update → add).
BEGIN;

ALTER TABLE demands DROP CONSTRAINT IF EXISTS demands_demand_status_check;
UPDATE demands SET demand_status='CLOSED' WHERE demand_status='FULFILLED';
UPDATE demands SET demand_status='EXPIRED' WHERE demand_status='CANCELLED';
ALTER TABLE demands ADD CONSTRAINT demands_demand_status_check
  CHECK (demand_status IN ('DRAFT','OPEN','PARTIAL','CLOSED','EXPIRED'));

ALTER TABLE demand_pledges DROP CONSTRAINT IF EXISTS demand_pledges_pledge_status_check;
UPDATE demand_pledges SET pledge_status='PENDING'   WHERE pledge_status='PLEDGED';
UPDATE demand_pledges SET pledge_status='ACCEPTED'  WHERE pledge_status='CONFIRMED';
UPDATE demand_pledges SET pledge_status='DELIVERED_TO_KOPERASI' WHERE pledge_status='DELIVERED';
ALTER TABLE demand_pledges ADD CONSTRAINT demand_pledges_pledge_status_check
  CHECK (pledge_status IN ('PENDING','ACCEPTED','DELIVERED_TO_KOPERASI','HANDED_TO_BUYER','CANCELLED'));
ALTER TABLE demand_pledges ALTER COLUMN pledge_status SET DEFAULT 'PENDING';

ALTER TABLE supply_listings DROP CONSTRAINT IF EXISTS supply_listings_listing_status_check;
UPDATE supply_listings SET listing_status='POSTED' WHERE listing_status='ACTIVE';
UPDATE supply_listings SET listing_status='CLOSED' WHERE listing_status='INACTIVE';
ALTER TABLE supply_listings ADD CONSTRAINT supply_listings_listing_status_check
  CHECK (listing_status IN ('DRAFT','POSTED','SOLD_OUT','CLOSED'));
ALTER TABLE supply_listings ALTER COLUMN listing_status SET DEFAULT 'DRAFT';

ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_order_status_check;
UPDATE orders SET order_status='PENDING' WHERE order_status='BARU';
UPDATE orders SET order_status='HANDED_OVER' WHERE order_status='DONE';
ALTER TABLE orders ADD CONSTRAINT orders_order_status_check
  CHECK (order_status IN ('PENDING','CONFIRMED','HANDED_OVER','CANCELLED'));
ALTER TABLE orders ALTER COLUMN order_status SET DEFAULT 'PENDING';

COMMIT;
