package demand

import "testing"

func TestComputeDP(t *testing.T) {
	cases := []struct {
		qty                        int
		price                      float64
		wantTotal, wantDP, wantRem float64
	}{
		{500, 20000, 10_000_000, 3_000_000, 7_000_000},
		{100, 15000, 1_500_000, 450_000, 1_050_000},
		{1, 33333, 33333, 9999.9, 23333.1}, // pembulatan 2 desimal
	}
	for _, c := range cases {
		total, dp, rem := computeDP(c.qty, c.price)
		if total != c.wantTotal || dp != c.wantDP || rem != c.wantRem {
			t.Errorf("computeDP(%d,%.0f) = (%.2f,%.2f,%.2f), mau (%.2f,%.2f,%.2f)",
				c.qty, c.price, total, dp, rem, c.wantTotal, c.wantDP, c.wantRem)
		}
	}
}

func TestPledgeTransitions(t *testing.T) {
	valid := [][2]string{{"PENDING", "ACCEPTED"}, {"PENDING", "CANCELLED"}, {"ACCEPTED", "DELIVERED_TO_KOPERASI"}}
	for _, v := range valid {
		if !allowed(forwardTransitions[v[0]], v[1]) {
			t.Errorf("transisi %s→%s seharusnya valid", v[0], v[1])
		}
	}
	// HANDED_TO_BUYER tidak boleh via PUT generik (hanya via verifikasi)
	invalid := [][2]string{{"PENDING", "HANDED_TO_BUYER"}, {"DELIVERED_TO_KOPERASI", "HANDED_TO_BUYER"}, {"ACCEPTED", "HANDED_TO_BUYER"}}
	for _, v := range invalid {
		if allowed(forwardTransitions[v[0]], v[1]) {
			t.Errorf("transisi %s→%s seharusnya DITOLAK di PUT generik", v[0], v[1])
		}
	}
}
