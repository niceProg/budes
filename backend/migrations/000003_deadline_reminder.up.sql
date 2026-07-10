-- Penanda agar pengingat tenggat (H-24) hanya dikirim sekali per demand.
ALTER TABLE demands ADD COLUMN IF NOT EXISTS deadline_reminded_at timestamptz;
