package settlement

import "testing"

func TestFee(t *testing.T) {
	cases := []struct {
		gross            float64
		wantFee, wantNet float64
	}{
		{900_000, 45_000, 855_000},   // pledge 60 × 15000, komisi 5%
		{400_000, 20_000, 380_000},   // order 10 × 40000
		{0, 0, 0},                    // batas bawah
		{1_000_000, 50_000, 950_000}, // 5%
	}
	for _, c := range cases {
		f, net := fee(c.gross)
		if f != c.wantFee || net != c.wantNet {
			t.Errorf("fee(%.0f) = (fee=%.2f, net=%.2f), mau (fee=%.2f, net=%.2f)",
				c.gross, f, net, c.wantFee, c.wantNet)
		}
		if f+net != c.gross {
			t.Errorf("fee+net (%.2f) != gross (%.2f)", f+net, c.gross)
		}
	}
}
