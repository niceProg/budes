#!/usr/bin/env bash
# Seed data demo ke API Budes live (idempoten: lewati bila sudah ada demand).
# Pakai: API=https://api-budes.yum-dev.com ./deploy/seed-demo.sh
set -euo pipefail
API="${API:-https://api-budes.yum-dev.com}"
PASS="budes123"

j() { python3 -c "import sys,json;print(json.load(sys.stdin).get('$1',''))"; }
jdata() { python3 -c "import sys,json;print(json.load(sys.stdin).get('data',{}).get('$1',''))"; }

# login (email,pass) -> token; kalau gagal, register (name,email,pass,role) lalu token.
auth() { # $1 name $2 email $3 role
  local tok
  tok=$(curl -s -X POST "$API/api/auth/login" -H 'Content-Type: application/json' \
    -d "{\"email\":\"$2\",\"password\":\"$PASS\"}" | j token)
  if [ -z "$tok" ]; then
    tok=$(curl -s -X POST "$API/api/auth/register" -H 'Content-Type: application/json' \
      -d "{\"name\":\"$1\",\"email\":\"$2\",\"password\":\"$PASS\",\"role\":\"$3\"}" | j token)
  fi
  echo "$tok"
}

echo "== cek data existing =="
COUNT=$(curl -s "$API/api/demands?limit=100" | python3 -c "import sys,json;print(len(json.load(sys.stdin).get('data',[])))")
if [ "$COUNT" -gt 0 ]; then echo "Sudah ada $COUNT demand — seed dilewati."; exit 0; fi

echo "== akun demo =="
BUYER=$(auth "Budi Santoso" "budi.demo@budes.id" "BUYER")
WARGA=$(auth "Wati Suharti" "wati.demo@budes.id" "WARGA")
ADMIN=$(auth "Pak Darto" "admin.demo@budes.id" "ADMIN_KOPERASI")
[ -n "$BUYER" ] && [ -n "$WARGA" ] || { echo "gagal auth demo"; exit 1; }
echo "buyer/warga/admin siap."

mkdemand() { # $1 item $2 satuan $3 qty $4 harga $5 deadline(YYYY-MM-DD) $6 payDP(1/0)
  local id
  id=$(curl -s -X POST "$API/api/demands" -H "Authorization: Bearer $BUYER" -H 'Content-Type: application/json' \
    -d "{\"item_name\":\"$1\",\"satuan\":\"$2\",\"total_qty\":$3,\"target_price_per_item\":$4,\"deadline\":\"${5}T00:00:00Z\"}" | jdata id)
  if [ -n "$id" ] && [ "$6" = "1" ]; then
    curl -s -X POST "$API/api/demands/$id/dp" -H "Authorization: Bearer $BUYER" -H 'Content-Type: application/json' \
      -d '{"dp_payment_method":"TRANSFER"}' >/dev/null
  fi
  echo "$id"
}

echo "== demands =="
D_BERAS=$(mkdemand "Beras Medium IR64" "kg" 500 12500 "2026-08-25" 1)
mkdemand "Cabai Merah Keriting" "kg" 200 38000 "2026-08-18" 1 >/dev/null
mkdemand "Kopi Robusta Petik Merah" "kg" 150 65000 "2026-09-15" 1 >/dev/null
mkdemand "Gula Aren Cetak" "kg" 300 21000 "2026-09-01" 0 >/dev/null   # DRAFT (belum DP)
echo "4 demand dibuat."

echo "== listings (warga) =="
mklisting() { # $1 item $2 satuan $3 qty $4 harga
  curl -s -X POST "$API/api/listings" -H "Authorization: Bearer $WARGA" -H 'Content-Type: application/json' \
    -d "{\"item_name\":\"$1\",\"satuan\":\"$2\",\"qty_available\":$3,\"price_per_item\":$4}" >/dev/null
}
mklisting "Beras Organik Mentik Wangi" "kg" 250 16000
mklisting "Madu Hutan Asli" "botol" 60 55000
mklisting "Pisang Kepok" "sisir" 120 9000
mklisting "Keripik Singkong Balado" "bungkus" 200 12000
echo "4 listing dibuat."

echo "== pledge (warga menyanggupi Beras, progres parsial) =="
if [ -n "$D_BERAS" ]; then
  curl -s -X POST "$API/api/demands/$D_BERAS/pledges" -H "Authorization: Bearer $WARGA" -H 'Content-Type: application/json' \
    -d '{"qty_pledged":120}' >/dev/null && echo "pledge 120 kg pada Beras."
fi

echo "== selesai. ringkasan =="
echo "demands: $(curl -s "$API/api/demands?limit=100" | python3 -c "import sys,json;print(len(json.load(sys.stdin).get('data',[])))")"
echo "listings: $(curl -s "$API/api/listings?limit=100" | python3 -c "import sys,json;print(len(json.load(sys.stdin).get('data',[])))")"
echo
echo "Akun demo (password $PASS): budi.demo@budes.id · wati.demo@budes.id · admin.demo@budes.id"
