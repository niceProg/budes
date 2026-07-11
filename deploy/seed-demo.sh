#!/usr/bin/env bash
# Seed data demo ke API Budes live (idempoten per bagian).
# Pakai: API=https://api-budes.yum-dev.com ./deploy/seed-demo.sh
set -euo pipefail
API="${API:-https://api-budes.yum-dev.com}"
PASS="budes123"

j() { python3 -c "import sys,json;print(json.load(sys.stdin).get('$1',''))"; }
jdata() { python3 -c "import sys,json;print(json.load(sys.stdin).get('data',{}).get('$1',''))"; }
count_demands() { curl -s "$API/api/demands?limit=100" | python3 -c "import sys,json;print(len(json.load(sys.stdin).get('data',[])))"; }

auth() { # $1 name $2 email $3 role -> token
  local tok
  tok=$(curl -s -X POST "$API/api/auth/login" -H 'Content-Type: application/json' \
    -d "{\"email\":\"$2\",\"password\":\"$PASS\"}" | j token)
  if [ -z "$tok" ]; then
    tok=$(curl -s -X POST "$API/api/auth/register" -H 'Content-Type: application/json' \
      -d "{\"name\":\"$1\",\"email\":\"$2\",\"password\":\"$PASS\",\"role\":\"$3\"}" | j token)
  fi
  echo "$tok"
}

echo "== akun demo =="
BUYER=$(auth "Budi Santoso" "budi.demo@budes.id" "BUYER")
WARGA=$(auth "Wati Suharti" "wati.demo@budes.id" "WARGA")
ADMIN=$(auth "Pak Darto" "admin.demo@budes.id" "ADMIN_KOPERASI")
[ -n "$BUYER" ] && [ -n "$WARGA" ] && [ -n "$ADMIN" ] || { echo "gagal auth demo"; exit 1; }
echo "buyer/warga/admin siap."

# ---------- demands & listings ----------
if [ "$(count_demands)" -gt 0 ]; then
  echo "== demands/listings sudah ada — dilewati =="
else
  mkdemand() { # item satuan qty harga deadline payDP(1/0) -> id
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
  mkdemand "Gula Aren Cetak" "kg" 300 21000 "2026-09-01" 0 >/dev/null   # DRAFT
  mklisting() { curl -s -X POST "$API/api/listings" -H "Authorization: Bearer $WARGA" -H 'Content-Type: application/json' \
      -d "{\"item_name\":\"$1\",\"satuan\":\"$2\",\"qty_available\":$3,\"price_per_item\":$4}" >/dev/null; }
  echo "== listings =="
  mklisting "Beras Organik Mentik Wangi" "kg" 250 16000
  mklisting "Madu Hutan Asli" "botol" 60 55000
  mklisting "Pisang Kepok" "sisir" 120 9000
  mklisting "Keripik Singkong Balado" "bungkus" 200 12000
  [ -n "$D_BERAS" ] && curl -s -X POST "$API/api/demands/$D_BERAS/pledges" -H "Authorization: Bearer $WARGA" \
    -H 'Content-Type: application/json' -d '{"qty_pledged":120}' >/dev/null
  echo "demands/listings/pledge dibuat."
fi

# ---------- pengajuan KYC ----------
KYC_N=$(curl -s "$API/api/verifikasi" -H "Authorization: Bearer $ADMIN" | python3 -c "import sys,json;print(len(json.load(sys.stdin).get('data',[])))" 2>/dev/null || echo 0)
if [ "$KYC_N" -gt 0 ]; then
  echo "== KYC sudah ada ($KYC_N) — dilewati =="
else
  echo "== pengajuan KYC demo =="
  kyc_submit() { curl -s -X POST "$API/api/verifikasi" -H "Authorization: Bearer $1" -H 'Content-Type: application/json' \
      -d "{\"nik\":\"$2\",\"id_card_file\":\"$3\",\"support_doc_file\":\"$4\"}" | jdata id; }
  kyc_review() { curl -s -X PUT "$API/api/verifikasi/$1" -H "Authorization: Bearer $ADMIN" -H 'Content-Type: application/json' \
      -d "{\"status\":\"$2\"}" >/dev/null; }
  # Wati (warga) — biarkan PENDING
  kyc_submit "$WARGA" "3402014507870087" "ktp-wati-suharti.jpg" "Kartu Keluarga" >/dev/null
  # Pemohon baru: Slamet (warga) -> VERIFIED; Toko Manis (buyer) -> REJECTED
  SLAMET=$(auth "Slamet Riyadi" "slamet.demo@budes.id" "WARGA")
  TOKO=$(auth "Toko Manis Jaya" "tokomanis.demo@budes.id" "BUYER")
  V1=$(kyc_submit "$SLAMET" "3402011203920012" "ktp-slamet-riyadi.png" "Surat keterangan domisili")
  V2=$(kyc_submit "$TOKO" "3273068811930041" "ktp-toko-manis.jpg" "SIUP")
  [ -n "$V1" ] && kyc_review "$V1" "VERIFIED"
  [ -n "$V2" ] && kyc_review "$V2" "REJECTED"
  echo "3 pengajuan KYC (1 pending, 1 verified, 1 rejected)."
fi

# ---------- transaksi selesai (mengisi pembukuan komisi) ----------
TXN_N=$(curl -s "$API/api/transactions" -H "Authorization: Bearer $ADMIN" | python3 -c "import sys,json;print(len(json.load(sys.stdin).get('data',[])))" 2>/dev/null || echo 0)
if [ "$TXN_N" -gt 0 ]; then
  echo "== transaksi sudah ada ($TXN_N) — dilewati =="
else
  echo "== transaksi selesai (alur demand: pledge Beras) =="
  PID=$(curl -s "$API/api/pledges" -H "Authorization: Bearer $WARGA" | python3 -c "
import sys,json
d=json.load(sys.stdin).get('data',[])
p=[x for x in d if x.get('status')=='PLEDGED']
print(p[0]['id'] if p else '')")
  if [ -n "$PID" ]; then
    curl -s -X PUT "$API/api/pledges/$PID" -H "Authorization: Bearer $WARGA" -H 'Content-Type: application/json' -d '{"status":"CONFIRMED"}' >/dev/null
    curl -s -X POST "$API/api/pledges/$PID/verifikasi" -H "Authorization: Bearer $BUYER" -H 'Content-Type: application/json' -d '{}' >/dev/null
    echo "  demand txn (Beras) dibuat."
  fi
  echo "== transaksi selesai (alur etalase: order Madu) =="
  LID=$(curl -s "$API/api/listings?limit=100" | python3 -c "
import sys,json
d=json.load(sys.stdin).get('data',[])
m=[x for x in d if 'Madu' in x.get('item_name','')]
print(m[0]['id'] if m else (d[0]['id'] if d else ''))")
  if [ -n "$LID" ]; then
    OID=$(curl -s -X POST "$API/api/orders" -H "Authorization: Bearer $BUYER" -H 'Content-Type: application/json' -d "{\"listing_id\":\"$LID\",\"qty_ordered\":5}" | jdata id)
    if [ -n "$OID" ]; then
      curl -s -X PUT "$API/api/orders/$OID" -H "Authorization: Bearer $BUYER" -H 'Content-Type: application/json' -d '{"status":"CONFIRMED"}' >/dev/null
      curl -s -X POST "$API/api/orders/$OID/verifikasi" -H "Authorization: Bearer $BUYER" -H 'Content-Type: application/json' -d '{}' >/dev/null
      echo "  supply txn (Madu) dibuat."
    fi
  fi
fi

# ---------- batas harga komoditas ----------
PC_N=$(curl -s "$API/api/pengaturan/harga" -H "Authorization: Bearer $ADMIN" | python3 -c "import sys,json;print(len(json.load(sys.stdin).get('data',[])))" 2>/dev/null || echo 0)
if [ "$PC_N" -gt 0 ]; then
  echo "== batas harga sudah ada ($PC_N) — dilewati =="
else
  echo "== seed batas harga komoditas =="
  curl -s -X PUT "$API/api/pengaturan/harga" -H "Authorization: Bearer $ADMIN" -H 'Content-Type: application/json' -d '{"caps":[
    {"komoditas":"Beras","satuan":"kg","max_jual":13000,"max_beli":15000},
    {"komoditas":"Jagung Pipil","satuan":"kg","max_jual":5500,"max_beli":6500},
    {"komoditas":"Cabai Merah","satuan":"kg","max_jual":40000,"max_beli":45000},
    {"komoditas":"Kopi Robusta","satuan":"kg","max_jual":68000,"max_beli":75000},
    {"komoditas":"Kelapa","satuan":"butir","max_jual":3800,"max_beli":4500}
  ]}' >/dev/null && echo "5 batas harga dibuat."
fi

echo "== ringkasan =="
echo "demands publik: $(count_demands) | listings: $(curl -s "$API/api/listings?limit=100" | python3 -c "import sys,json;print(len(json.load(sys.stdin).get('data',[])))")"
echo "KYC: $(curl -s "$API/api/verifikasi" -H "Authorization: Bearer $ADMIN" | python3 -c "import sys,json;print(len(json.load(sys.stdin).get('data',[])))")"
echo "Akun demo (password $PASS): budi.demo@budes.id · wati.demo@budes.id · admin.demo@budes.id"
