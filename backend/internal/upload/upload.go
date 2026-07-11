// Package upload menangani unggah berkas (foto KTP KYC) ke direktori lokal & penyajiannya.
package upload

import (
	"crypto/rand"
	"encoding/hex"
	"io"
	"net/http"
	"os"
	"path/filepath"

	"budes/internal/httpx"
)

const maxUpload = 5 << 20 // 5 MB

// Handler menyimpan berkas unggahan & menyajikannya kembali.
type Handler struct{ dir string }

// NewHandler membuat handler upload; memastikan direktori ada.
func NewHandler(dir string) *Handler {
	_ = os.MkdirAll(dir, 0o755)
	return &Handler{dir: dir}
}

// Upload: POST /api/upload (auth, multipart field "file") — hanya gambar JPG/JPEG/PNG, maks 5 MB.
func (h *Handler) Upload(w http.ResponseWriter, r *http.Request) {
	r.Body = http.MaxBytesReader(w, r.Body, maxUpload+(1<<20))
	if err := r.ParseMultipartForm(maxUpload); err != nil {
		httpx.Error(w, http.StatusBadRequest, "berkas terlalu besar (maks 5 MB) atau tidak valid")
		return
	}
	file, _, err := r.FormFile("file")
	if err != nil {
		httpx.Error(w, http.StatusBadRequest, "field 'file' wajib diisi")
		return
	}
	defer file.Close()

	head := make([]byte, 512)
	n, _ := io.ReadFull(file, head)
	ext := map[string]string{"image/jpeg": ".jpg", "image/png": ".png"}[http.DetectContentType(head[:n])]
	if ext == "" {
		httpx.Error(w, http.StatusBadRequest, "format tidak didukung — hanya JPG/JPEG/PNG")
		return
	}
	if _, err := file.Seek(0, io.SeekStart); err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}

	name := randName() + ext
	dst, err := os.Create(filepath.Join(h.dir, name))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	defer dst.Close()
	if _, err := io.Copy(dst, io.LimitReader(file, maxUpload)); err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": map[string]string{"url": "/uploads/" + name}})
}

// Serve menyajikan berkas statis di /uploads/*.
func (h *Handler) Serve() http.Handler {
	return http.StripPrefix("/uploads/", http.FileServer(http.Dir(h.dir)))
}

func randName() string {
	b := make([]byte, 16)
	_, _ = rand.Read(b)
	return hex.EncodeToString(b)
}
