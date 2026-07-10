// Package httpx menyediakan helper response JSON dengan envelope konsisten.
package httpx

import (
	"encoding/json"
	"net/http"
	"strconv"
)

// JSON menulis payload apa pun sebagai JSON dengan status kode tertentu.
func JSON(w http.ResponseWriter, status int, payload any) {
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	w.WriteHeader(status)
	_ = json.NewEncoder(w).Encode(payload)
}

// OK membungkus data sukses: {"data": ...}.
func OK(w http.ResponseWriter, data any) {
	JSON(w, http.StatusOK, map[string]any{"data": data})
}

// Error membungkus pesan error: {"error": "..."}.
func Error(w http.ResponseWriter, status int, msg string) {
	JSON(w, status, map[string]any{"error": msg})
}

// Paginate membaca ?limit=&offset= dengan default limit 50 (maks 100) & offset 0.
func Paginate(r *http.Request) (limit, offset int) {
	limit, offset = 50, 0
	if n, err := strconv.Atoi(r.URL.Query().Get("limit")); err == nil && n > 0 {
		limit = n
		if limit > 100 {
			limit = 100
		}
	}
	if n, err := strconv.Atoi(r.URL.Query().Get("offset")); err == nil && n > 0 {
		offset = n
	}
	return limit, offset
}

// Decode membaca body JSON request ke dst. Kembalikan false + tulis 400 bila gagal.
func Decode(w http.ResponseWriter, r *http.Request, dst any) bool {
	if err := json.NewDecoder(r.Body).Decode(dst); err != nil {
		Error(w, http.StatusBadRequest, "body JSON tidak valid: "+err.Error())
		return false
	}
	return true
}
