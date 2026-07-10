-- Selaraskan nilai enum status dengan kontrak frontend (flow frontend = sumber kebenaran).
-- Urutan: DROP constraint lama → UPDATE data → ADD constraint baru (agar UPDATE tak ditolak).
BEGIN;

-- demands: CLOSED→FULFILLED, EXPIRED→CANCELLED ------------------------
ALTER TABLE demands DROP CONSTRAINT IF EXISTS demands_demand_status_check;
UPDATE demands SET demand_status='FULFILLED' WHERE demand_status='CLOSED';
UPDATE demands SET demand_status='CANCELLED' WHERE demand_status='EXPIRED';
ALTER TABLE demands ADD CONSTRAINT demands_demand_status_check
  CHECK (demand_status IN ('DRAFT','OPEN','PARTIAL','FULFILLED','CANCELLED'));

-- demand_pledges: PENDING→PLEDGED, ACCEPTED→CONFIRMED, delivered→DELIVERED
ALTER TABLE demand_pledges DROP CONSTRAINT IF EXISTS demand_pledges_pledge_status_check;
UPDATE demand_pledges SET pledge_status='PLEDGED'   WHERE pledge_status='PENDING';
UPDATE demand_pledges SET pledge_status='CONFIRMED' WHERE pledge_status='ACCEPTED';
UPDATE demand_pledges SET pledge_status='DELIVERED' WHERE pledge_status IN ('DELIVERED_TO_KOPERASI','HANDED_TO_BUYER');
ALTER TABLE demand_pledges ADD CONSTRAINT demand_pledges_pledge_status_check
  CHECK (pledge_status IN ('PLEDGED','CONFIRMED','DELIVERED','CANCELLED'));
ALTER TABLE demand_pledges ALTER COLUMN pledge_status SET DEFAULT 'PLEDGED';

-- supply_listings: DRAFT+POSTED→ACTIVE, CLOSED→INACTIVE ---------------
ALTER TABLE supply_listings DROP CONSTRAINT IF EXISTS supply_listings_listing_status_check;
UPDATE supply_listings SET listing_status='ACTIVE'   WHERE listing_status IN ('DRAFT','POSTED');
UPDATE supply_listings SET listing_status='INACTIVE' WHERE listing_status='CLOSED';
ALTER TABLE supply_listings ADD CONSTRAINT supply_listings_listing_status_check
  CHECK (listing_status IN ('ACTIVE','SOLD_OUT','INACTIVE'));
ALTER TABLE supply_listings ALTER COLUMN listing_status SET DEFAULT 'ACTIVE';

-- orders: PENDING→BARU, HANDED_OVER→DONE ------------------------------
ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_order_status_check;
UPDATE orders SET order_status='BARU' WHERE order_status='PENDING';
UPDATE orders SET order_status='DONE' WHERE order_status='HANDED_OVER';
ALTER TABLE orders ADD CONSTRAINT orders_order_status_check
  CHECK (order_status IN ('BARU','CONFIRMED','DONE','CANCELLED'));
ALTER TABLE orders ALTER COLUMN order_status SET DEFAULT 'BARU';

COMMIT;
