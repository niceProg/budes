@extends('parja::alumni.layouts.app')

@section('title', 'Isi Survei')
@section('page-title', 'Isi Survei')

@push('styles')
<style>
    .survey-wrap {
        max-width: 880px;
        margin: 0 auto;
    }

    .survey-header {
        background: #fff;
        border: 1px solid var(--parja-border);
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 16px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
    }

    .question-card {
        background: #fff;
        border: 1px solid var(--parja-border);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .question-no {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        border-radius: 999px;
        background: rgba(191, 0, 80, 0.12);
        color: var(--parja-magenta);
        font-weight: 700;
        font-size: 0.8rem;
        margin-right: 8px;
    }

    .question-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--parja-text);
        margin: 0;
    }

    .question-desc {
        margin: 6px 0 0;
        color: var(--parja-muted);
        font-size: 0.82rem;
    }

    .option-list {
        margin-top: 12px;
        display: grid;
        gap: 8px;
    }

    .option-item {
        border: 1px solid var(--parja-border);
        border-radius: 10px;
        padding: 10px 12px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
        background: #fff;
    }

    .option-item input {
        margin-top: 2px;
    }

    .survey-progress-card {
        background: #fff;
        border: 1px solid var(--parja-border);
        border-radius: 12px;
        padding: 12px 14px;
        margin-top: 12px;
    }

    .survey-progress-bar {
        width: 100%;
        height: 10px;
        border-radius: 999px;
        background: #efeaf4;
        overflow: hidden;
    }

    .survey-progress-fill {
        height: 100%;
        width: 0;
        border-radius: 999px;
        background: linear-gradient(90deg, #bf0050 0%, #41174b 100%);
        transition: width 0.2s ease;
    }
</style>
@endpush

@section('content')
<div class="survey-wrap">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <a href="{{ route('alumni.survei.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-arrow-left-line"></i> Kembali ke Daftar Survei
        </a>
    </div>

    <section class="survey-header">
        <h5 class="mb-1 fw-bold text-maroon">{{ $survei->judul }}</h5>
        <p class="mb-2 text-muted">{{ $survei->deskripsi ?: 'Silakan isi semua pertanyaan di bawah ini.' }}</p>
        <small class="text-muted d-block">
            <i class="ri-question-answer-line"></i>
            {{ $kuesioner->count() }} pertanyaan
        </small>

        <div class="survey-progress-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">Progress Pengisian</small>
                <small class="fw-semibold" id="survey-progress-text">0/{{ $kuesioner->count() }} (0%)</small>
            </div>
            <div class="survey-progress-bar">
                <div class="survey-progress-fill" id="survey-progress-fill"></div>
            </div>
        </div>

        @if($isSubmitted)
            <div class="alert alert-success mb-0 mt-3">
                Survei ini sudah Anda kirim. Anda masih bisa melihat jawaban yang telah tersimpan.
            </div>
            <div class="mt-2">
                <a href="{{ route('alumni.survei.result', $survei->id) }}" class="btn btn-sm btn-maroon">
                    <i class="ri-bar-chart-box-line"></i> Lihat Ringkasan Hasil
                </a>
            </div>
        @endif
    </section>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Jawaban belum lengkap.</strong> Mohon cek kembali pertanyaan yang wajib diisi.
        </div>
    @endif

    <form method="POST" action="{{ route('alumni.survei.submit', $survei->id) }}">
        @csrf

        @foreach($kuesioner as $index => $item)
            @php
                $field = 'jawaban_' . $item->id;
                $selectedOpsi = old($field, $filledAnswers['opsi'][$item->id] ?? null);
                $selectedCheckbox = old($field, $filledAnswers['checkbox'][$item->id] ?? []);
                $filledIsian = old($field, $filledAnswers['isian'][$item->id] ?? '');
            @endphp
            <article class="question-card js-question" data-question-id="{{ $item->id }}" data-question-type="{{ $item->tipe_jawaban }}">
                <div class="d-flex align-items-start">
                    <span class="question-no">{{ $index + 1 }}</span>
                    <div>
                        <p class="question-title">{{ $item->pertanyaan }}</p>
                        @if($item->deskripsi)
                            <p class="question-desc">{{ $item->deskripsi }}</p>
                        @endif
                    </div>
                </div>

                @if($item->tipe_jawaban === 'isian')
                    <div class="mt-3">
                        <textarea
                            name="{{ $field }}"
                            data-question-input="{{ $item->id }}"
                            class="form-control @error($field) is-invalid @enderror"
                            rows="4"
                            placeholder="Tulis jawaban Anda..."
                            {{ $isSubmitted ? 'disabled' : '' }}
                        >{{ $filledIsian }}</textarea>
                        @error($field)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @elseif($item->tipe_jawaban === 'checkbox')
                    <div class="option-list">
                        @foreach($item->opsiJawaban as $opsi)
                            <label class="option-item">
                                <input
                                    type="checkbox"
                                    name="{{ $field }}[]"
                                    data-question-input="{{ $item->id }}"
                                    value="{{ $opsi->id }}"
                                    {{ in_array((string) $opsi->id, collect((array) $selectedCheckbox)->map(fn ($value) => (string) $value)->all(), true) ? 'checked' : '' }}
                                    {{ $isSubmitted ? 'disabled' : '' }}
                                >
                                <span>{{ $opsi->teks_opsi }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error($field)
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                @else
                    <div class="option-list">
                        @foreach($item->opsiJawaban as $opsi)
                            <label class="option-item">
                                <input
                                    type="radio"
                                    name="{{ $field }}"
                                    data-question-input="{{ $item->id }}"
                                    value="{{ $opsi->id }}"
                                    {{ (string) $selectedOpsi === (string) $opsi->id ? 'checked' : '' }}
                                    {{ $isSubmitted ? 'disabled' : '' }}
                                >
                                <span>{{ $opsi->teks_opsi }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error($field)
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                @endif
            </article>
        @endforeach

        @unless($isSubmitted)
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-maroon px-4">
                    <i class="ri-send-plane-2-line"></i> Kirim Jawaban
                </button>
            </div>
        @endunless
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const progressText = document.getElementById('survey-progress-text');
        const progressFill = document.getElementById('survey-progress-fill');
        const questionCards = Array.from(document.querySelectorAll('.js-question'));

        if (!progressText || !progressFill || questionCards.length === 0) {
            return;
        }

        const total = questionCards.length;

        function isAnswered(card) {
            const type = card.getAttribute('data-question-type');
            const inputs = Array.from(card.querySelectorAll('[data-question-input]'));

            if (type === 'isian') {
                const textarea = inputs[0];
                return !!textarea && textarea.value.trim() !== '';
            }

            if (type === 'checkbox') {
                return inputs.some((item) => item.checked);
            }

            return inputs.some((item) => item.checked);
        }

        function renderProgress() {
            const answered = questionCards.filter((card) => isAnswered(card)).length;
            const percent = total > 0 ? Math.round((answered / total) * 100) : 0;

            progressText.textContent = `${answered}/${total} (${percent}%)`;
            progressFill.style.width = `${percent}%`;
        }

        document.querySelectorAll('[data-question-input]').forEach((input) => {
            input.addEventListener('input', renderProgress);
            input.addEventListener('change', renderProgress);
        });

        renderProgress();
    })();
</script>
@endpush
