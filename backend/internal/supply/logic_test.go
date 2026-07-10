package supply

import "testing"

func TestOrderTransitions(t *testing.T) {
	if !contains(orderTransitions["PENDING"], "CONFIRMED") {
		t.Error("PENDING→CONFIRMED harus valid")
	}
	if !contains(orderTransitions["PENDING"], "CANCELLED") {
		t.Error("PENDING→CANCELLED harus valid")
	}
	// HANDED_OVER hanya via verifikasi, bukan PUT generik
	if contains(orderTransitions["CONFIRMED"], "HANDED_OVER") {
		t.Error("CONFIRMED→HANDED_OVER harus DITOLAK di PUT generik")
	}
}

func TestListingTransitions(t *testing.T) {
	if !contains(listingTransitions["DRAFT"], "POSTED") {
		t.Error("DRAFT→POSTED harus valid")
	}
	if contains(listingTransitions["DRAFT"], "SOLD_OUT") {
		t.Error("DRAFT→SOLD_OUT tidak boleh langsung")
	}
}
