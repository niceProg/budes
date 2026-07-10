package supply

import "testing"

func TestOrderTransitions(t *testing.T) {
	if !contains(orderTransitions["BARU"], "CONFIRMED") {
		t.Error("BARU→CONFIRMED harus valid")
	}
	if !contains(orderTransitions["BARU"], "CANCELLED") {
		t.Error("BARU→CANCELLED harus valid")
	}
	// DONE hanya via verifikasi, bukan PUT generik
	if contains(orderTransitions["CONFIRMED"], "DONE") {
		t.Error("CONFIRMED→DONE harus DITOLAK di PUT generik")
	}
}

func TestListingTransitions(t *testing.T) {
	if !contains(listingTransitions["ACTIVE"], "INACTIVE") {
		t.Error("ACTIVE→INACTIVE harus valid")
	}
	if !contains(listingTransitions["SOLD_OUT"], "ACTIVE") {
		t.Error("SOLD_OUT→ACTIVE harus valid (reaktivasi)")
	}
	if contains(listingTransitions["INACTIVE"], "SOLD_OUT") {
		t.Error("INACTIVE→SOLD_OUT tidak boleh langsung")
	}
}
