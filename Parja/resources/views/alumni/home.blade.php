@extends('parja::alumni.layouts.app')

@section('title', 'Beranda Parja Alumni')
@section('page-title', 'Beranda Parja Alumni')

@push('styles')
<style>
    .feed-post-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        margin-bottom: 16px;
    }

    .feed-post-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap;
        position: relative;
    }

    /* ── Post Settings Dropdown ── */
    .post-settings-btn {
        background: none;
        border: none;
        padding: 4px 8px;
        border-radius: 8px;
        color: var(--parja-muted);
        cursor: pointer;
        font-size: 1.15rem;
        line-height: 1;
        transition: background 0.15s, color 0.15s;
        flex-shrink: 0;
    }
    .post-settings-btn:hover { background: rgba(65,23,75,0.07); color: var(--parja-purple); }

    .post-settings-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        right: 0;
        min-width: 190px;
        background: #fff;
        border: 1px solid var(--parja-border);
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(65,23,75,0.14);
        z-index: 200;
        overflow: hidden;
        display: none;
    }
    .post-settings-dropdown.open { display: block; }

    .post-settings-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--parja-text);
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        transition: background 0.12s;
    }
    .post-settings-item:hover { background: rgba(65,23,75,0.05); }
    .post-settings-item.danger { color: #c0392b; }
    .post-settings-item.danger:hover { background: rgba(192,57,43,0.06); }
    .post-settings-item i { font-size: 1rem; width: 18px; text-align: center; }
    .post-settings-divider { height: 1px; background: var(--parja-border); margin: 4px 0; }

    /* ── Edit Post Modal ── */
    .post-edit-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(30,10,40,0.38);
        z-index: 1100;
        align-items: center;
        justify-content: center;
    }
    .post-edit-modal-backdrop.open { display: flex; }
    .post-edit-modal {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 16px 48px rgba(65,23,75,0.18);
        width: 100%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 28px 28px 20px;
        position: relative;
    }
    .post-edit-modal-title {
        font-weight: 700;
        font-size: 1rem;
        color: var(--parja-purple);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .post-edit-modal-close {
        position: absolute;
        top: 16px;
        right: 18px;
        background: none;
        border: none;
        font-size: 1.4rem;
        color: var(--parja-muted);
        cursor: pointer;
        line-height: 1;
        padding: 4px;
        border-radius: 50%;
        transition: background 0.15s;
    }
    .post-edit-modal-close:hover { background: rgba(65,23,75,0.08); }
    .post-edit-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--parja-purple);
        margin-bottom: 5px;
        display: block;
    }
    .post-edit-input, .post-edit-textarea, .post-edit-select {
        width: 100%;
        border: 1.5px solid var(--parja-border);
        border-radius: 10px;
        padding: 9px 13px;
        font-size: 0.88rem;
        color: var(--parja-text);
        background: #fafafd;
        transition: border-color 0.15s;
        outline: none;
        resize: none;
    }
    .post-edit-input:focus, .post-edit-textarea:focus, .post-edit-select:focus {
        border-color: var(--parja-magenta);
        background: #fff;
    }
    .post-edit-textarea { min-height: 110px; }
    .post-edit-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 18px;
    }
    .post-edit-btn-cancel {
        background: none;
        border: 1.5px solid var(--parja-border);
        border-radius: 50px;
        padding: 7px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--parja-muted);
        cursor: pointer;
    }
    .post-edit-btn-save {
        background: var(--parja-magenta);
        border: none;
        border-radius: 50px;
        padding: 7px 22px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    .post-edit-btn-save:hover { opacity: 0.88; }

    .feed-post-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--parja-border);
        background: var(--parja-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-weight: 700;
        font-size: 1rem;
        color: var(--parja-purple);
    }

    .feed-post-meta .name {
        font-weight: 700;
        color: var(--parja-purple);
        font-size: 0.95rem;
        margin: 0;
    }

    .feed-post-meta .sub {
        font-size: 0.8rem;
        color: var(--parja-muted);
        margin: 0;
    }

    .tipe-badge {
        font-size: 0.72rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .tipe-badge.freepost {
        background: rgba(191, 0, 80, 0.1);
        color: var(--parja-magenta);
    }

    .tipe-badge.artikel {
        background: rgba(65, 23, 75, 0.1);
        color: var(--parja-purple);
    }

    .feed-post-title {
        font-weight: 700;
        color: var(--parja-text);
        font-size: 1.05rem;
        margin-bottom: 8px;
    }

    .feed-post-content {
        color: var(--parja-text);
        font-size: 0.93rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .feed-photo-grid {
        display: grid;
        gap: 4px;
        margin-top: 14px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--parja-border);
    }
    .feed-photo-grid.count-1 { grid-template-columns: 1fr; border: none; border-radius: 0; }
    .feed-photo-grid.count-2 { grid-template-columns: 1fr 1fr; }
    .feed-photo-grid.count-3 { grid-template-columns: 1fr 1fr; }
    .feed-photo-grid.count-4 { grid-template-columns: 1fr 1fr; }
    .feed-photo-grid.count-5 { grid-template-columns: repeat(6, 1fr); }

    .feed-photo-grid img {
        width: 100%;
        display: block;
        object-fit: cover;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    .feed-photo-grid img:hover { opacity: 0.9; }

    .feed-photo-grid.count-1 img {
        height: auto;
        max-height: 550px;
        border-radius: 12px;
        object-fit: contain;
        background: #f8f9fa;
        border: 1px solid var(--parja-border);
    }
    .feed-photo-grid.count-2 img { height: 300px; }
    .feed-photo-grid.count-3 img:first-child { grid-column: 1 / span 2; height: 350px; }
    .feed-photo-grid.count-3 img:not(:first-child) { height: 180px; }
    .feed-photo-grid.count-4 img { height: 250px; }
    .feed-photo-grid.count-5 img:nth-child(1),
    .feed-photo-grid.count-5 img:nth-child(2) { grid-column: span 3; height: 250px; }
    .feed-photo-grid.count-5 img:nth-child(3),
    .feed-photo-grid.count-5 img:nth-child(4),
    .feed-photo-grid.count-5 img:nth-child(5) { grid-column: span 2; height: 180px; }

    /* Lightbox */
    .pf-lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.88);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .pf-lightbox.show { display: flex; }

    .pf-lightbox img {
        max-width: 90vw;
        max-height: 88vh;
        border-radius: 10px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
    }

    .pf-lightbox-close {
        position: absolute;
        top: 18px;
        right: 22px;
        color: #fff;
        font-size: 2rem;
        cursor: pointer;
        line-height: 1;
    }

    .empty-feed {
        text-align: center;
        padding: 48px 20px;
        color: var(--parja-muted);
    }

    .empty-feed i {
        font-size: 3rem;
        color: var(--parja-border);
        display: block;
        margin-bottom: 12px;
    }

    /* Like & Komentar */
    .post-action-bar {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--parja-border);
    }

    .post-action-btn {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--parja-muted);
        transition: color 0.15s;
    }

    .post-action-btn:hover { color: var(--parja-magenta); }
    .post-action-btn.liked  { color: var(--parja-magenta); }
    .post-action-btn i { font-size: 1rem; }

    .comment-section {
        margin-top: 12px;
        border-top: 1px dashed var(--parja-border);
        padding-top: 10px;
    }

    .comment-item {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        align-items: flex-start;
    }

    .comment-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--parja-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--parja-purple);
        flex-shrink: 0;
        object-fit: cover;
    }

    .comment-bubble {
        background: #f8f4fc;
        border-radius: 12px;
        padding: 8px 12px;
        flex: 1;
        min-width: 0;
    }

    .comment-name {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--parja-purple);
        margin: 0 0 2px;
    }

    .comment-text {
        font-size: 0.85rem;
        color: var(--parja-text);
        margin: 0;
        word-break: break-word;
    }

    .comment-meta {
        font-size: 0.72rem;
        color: var(--parja-muted);
        margin-top: 3px;
    }

    .comment-form {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-top: 10px;
    }

    .comment-form input {
        flex: 1;
        border: 1px solid var(--parja-border);
        border-radius: 50px;
        padding: 6px 14px;
        font-size: 0.84rem;
        outline: none;
        transition: border-color 0.15s;
    }

    .comment-form input:focus {
        border-color: var(--parja-magenta);
    }

    .comment-form button {
        background: var(--parja-magenta);
        border: none;
        color: #fff;
        border-radius: 50px;
        padding: 6px 14px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        flex-shrink: 0;
    }

    .sidebar-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        margin-bottom: 16px;
    }

    .home-hero-banner {
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid var(--parja-border);
        box-shadow: 0 10px 24px rgba(65, 23, 75, 0.12);
        background: #fff;
        margin-bottom: 16px;
    }

    .home-hero-banner .carousel-item {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 6;
        min-height: 200px;
        max-height: 460px;
        overflow: hidden;
        background: #1a1a2e;
    }

    .home-hero-banner .carousel-item img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center center;
        display: block;
    }

    .home-hero-banner .carousel-caption {
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.08), rgba(0, 0, 0, 0.62));
        left: 0;
        right: 0;
        bottom: 0;
        padding: 16px 22px;
        text-align: left;
    }

    .home-hero-banner .carousel-caption h5 {
        margin: 0;
        color: #fff;
        font-weight: 700;
        font-size: clamp(1.1rem, 2vw, 1.45rem);
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.45);
    }

    .home-reminder-board {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 18px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        margin-bottom: 16px;
    }

    .home-reminder-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .home-reminder-item {
        border: 1px solid var(--parja-border);
        border-radius: 14px;
        padding: 12px;
        background: linear-gradient(180deg, #fff 0%, rgba(255, 248, 252, 0.9) 100%);
    }

    .home-reminder-meta {
        font-size: 0.74rem;
        color: var(--parja-muted);
        margin-bottom: 5px;
    }

    .home-reminder-deadline-badge {
        font-size: 0.66rem;
        border-radius: 999px;
        padding: 2px 8px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .home-reminder-deadline-badge.critical {
        background: rgba(220, 53, 69, 0.22);
        border: 1px solid rgba(220, 53, 69, 0.6);
        color: #8f1422;
    }

    .home-reminder-deadline-badge.warning {
        background: rgba(220, 53, 69, 0.16);
        border: 1px solid rgba(255, 140, 0, 0.5);
        color: #8a4a00;
    }

    .home-reminder-deadline-badge.expired {
        background: rgba(108, 117, 125, 0.16);
        border: 1px solid rgba(108, 117, 125, 0.45);
        color: #4f5962;
    }

    .home-reminder-title {
        font-size: 0.86rem;
        color: var(--parja-purple);
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 8px;
    }

    .home-reminder-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .home-reminder-btn {
        border: 1px solid rgba(65,23,75,0.2);
        border-radius: 999px;
        background: #fff;
        color: var(--parja-purple);
        padding: 4px 10px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .home-reminder-btn.is-on {
        border-color: rgba(191,0,80,0.35);
        background: rgba(191,0,80,0.08);
        color: var(--parja-magenta);
    }

    .home-reminder-link {
        font-size: 0.74rem;
        font-weight: 700;
        color: var(--parja-magenta);
        text-decoration: none;
    }

    .home-reminder-modal-item {
        border: 1px solid var(--parja-border);
        border-radius: 14px;
        padding: 10px 12px;
        background: #fff;
        margin-bottom: 9px;
    }

    .home-reminder-modal-item:last-child {
        margin-bottom: 0;
    }

    .home-reminder-bell {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .home-reminder-bell.critical {
        color: #8f1422;
        background: rgba(220, 53, 69, 0.2);
    }

    .home-reminder-bell.warning {
        color: #8a4a00;
        background: rgba(255, 140, 0, 0.2);
    }

    .home-reminder-bell.expired {
        color: #4f5962;
        background: rgba(108, 117, 125, 0.2);
    }

    .home-reminder-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .home-reminder-tab-count {
        min-width: 22px;
        height: 22px;
        border-radius: 999px;
        padding: 0 7px;
        font-size: 0.68rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(65, 23, 75, 0.1);
        color: var(--parja-purple);
    }

    .home-reminder-tab.active .home-reminder-tab-count {
        background: rgba(191, 0, 80, 0.18);
        color: var(--parja-magenta);
    }

    .home-feed-stats {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 18px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
    }

    .home-feed-stats-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--parja-purple);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .home-feed-stats-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .home-feed-stat-item {
        border: 1px solid var(--parja-border);
        border-radius: 14px;
        padding: 12px;
        background: linear-gradient(180deg, #fff 0%, rgba(253, 249, 255, 0.8) 100%);
        min-height: 116px;
        transition: transform 0.15s ease, background 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }

    .home-feed-stat-item[role="button"] {
        cursor: pointer;
    }

    .home-feed-stat-item[role="button"]:hover {
        background: #fff;
        border-color: var(--parja-magenta);
        box-shadow: 0 4px 12px rgba(191, 0, 80, 0.08);
        transform: translateY(-1px);
    }

    .home-feed-stat-item[role="button"]:hover .action-icon {
        color: var(--parja-magenta) !important;
    }

    .home-feed-stat-label {
        font-size: 0.74rem;
        color: var(--parja-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 4px;
    }

    .home-feed-stat-value {
        font-size: 1.55rem;
        font-weight: 800;
        color: var(--parja-magenta);
        line-height: 1.1;
        margin-bottom: 2px;
    }

    .home-feed-stat-note {
        font-size: 0.77rem;
        color: var(--parja-muted);
        margin: 0;
    }

    .home-feed-trending-title {
        font-size: 0.86rem;
        font-weight: 700;
        color: var(--parja-purple);
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .home-feed-trending-title-row {
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    .home-feed-trending-title-row i {
        margin-top: 1px;
        flex-shrink: 0;
    }

    .home-feed-trending-meta {
        font-size: 0.76rem;
        color: var(--parja-muted);
    }

    .home-feed-modal-filter.active {
        background: var(--parja-magenta);
        color: #fff;
        border-color: var(--parja-magenta);
    }

    .home-feed-modal-item {
        border: 1px solid var(--parja-border);
        border-radius: 14px;
        padding: 10px 12px;
        background: #fff;
        margin-bottom: 9px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
    }

    .home-feed-modal-item:last-child {
        margin-bottom: 0;
    }

    .home-feed-modal-item.is-entering {
        animation: homeFeedItemIn 0.24s ease both;
    }

    @keyframes homeFeedItemIn {
        from {
            opacity: 0;
            transform: translateY(8px) scale(0.985);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .home-feed-rank {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(191, 0, 80, 0.12);
        color: var(--parja-magenta);
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .home-feed-rank.rank-1 {
        background: linear-gradient(135deg, #ffd55a, #f6b100);
        color: #694800;
        box-shadow: 0 0 0 2px rgba(246, 177, 0, 0.18);
    }

    .home-feed-rank.rank-2 {
        background: linear-gradient(135deg, #edf2f7, #cfd8e3);
        color: #425466;
        box-shadow: 0 0 0 2px rgba(120, 138, 160, 0.15);
    }

    .home-feed-rank.rank-3 {
        background: linear-gradient(135deg, #f2c6a0, #d9925e);
        color: #5f3418;
        box-shadow: 0 0 0 2px rgba(181, 108, 56, 0.15);
    }

    .home-feed-privacy-badge {
        font-size: 0.66rem;
        border-radius: 999px;
        padding: 2px 8px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        line-height: 1.2;
        text-transform: uppercase;
    }

    .home-feed-privacy-badge.privacy-alumni {
        background: rgba(65, 23, 75, 0.11);
        color: var(--parja-purple);
    }

    .home-feed-privacy-badge.privacy-public {
        background: rgba(191, 0, 80, 0.11);
        color: var(--parja-magenta);
    }

    .home-feed-modal-title {
        font-size: 0.87rem;
        color: var(--parja-purple);
        font-weight: 700;
        margin: 0;
        line-height: 1.25;
    }

    .home-feed-modal-meta {
        font-size: 0.74rem;
        color: var(--parja-muted);
        margin-top: 3px;
    }

    .home-info-type-badge {
        font-size: 0.66rem;
        border-radius: 999px;
        padding: 2px 8px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        line-height: 1.2;
        text-transform: uppercase;
    }

    .home-info-type-badge.type-berita {
        background: rgba(13, 110, 253, 0.13);
        color: #0b5ed7;
    }

    .home-info-type-badge.type-kegiatan {
        background: rgba(25, 135, 84, 0.13);
        color: #157347;
    }

    .home-info-type-badge.type-pengumuman {
        background: rgba(255, 193, 7, 0.22);
        color: #8a5a00;
    }

    .home-info-modal-filter.active {
        background: var(--parja-magenta);
        color: #fff;
        border-color: var(--parja-magenta);
    }

    .home-info-modal-item {
        border: 1px solid var(--parja-border);
        border-radius: 14px;
        padding: 10px 12px;
        background: #fff;
        margin-bottom: 9px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
    }

    .home-info-modal-item:last-child {
        margin-bottom: 0;
    }

    .home-info-modal-item.is-entering {
        animation: homeInfoItemIn 0.24s ease both;
    }

    @keyframes homeInfoItemIn {
        from {
            opacity: 0;
            transform: translateY(8px) scale(0.985);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .home-chat-stats-list {
        margin-top: 10px;
    }

    .home-chat-trending-item {
        border: 1px solid var(--parja-border);
        border-radius: 12px;
        padding: 9px 10px;
        margin-bottom: 8px;
        background: #fff;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
    }

    .home-chat-trending-rank {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(191, 0, 80, 0.12);
        color: var(--parja-magenta);
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .home-chat-trending-rank.rank-1 {
        background: linear-gradient(135deg, #ffd55a, #f6b100);
        color: #694800;
        box-shadow: 0 0 0 2px rgba(246, 177, 0, 0.18);
    }

    .home-chat-trending-rank.rank-2 {
        background: linear-gradient(135deg, #edf2f7, #cfd8e3);
        color: #425466;
        box-shadow: 0 0 0 2px rgba(120, 138, 160, 0.15);
    }

    .home-chat-trending-rank.rank-3 {
        background: linear-gradient(135deg, #f2c6a0, #d9925e);
        color: #5f3418;
        box-shadow: 0 0 0 2px rgba(181, 108, 56, 0.15);
    }

    .home-chat-trending-name {
        font-size: 0.86rem;
        color: var(--parja-purple);
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .home-chat-trending-meta {
        font-size: 0.74rem;
        color: var(--parja-muted);
        margin-top: 2px;
    }

    .home-chat-trending-score {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--parja-magenta);
        white-space: nowrap;
    }

    .home-chat-modal-filter.active {
        background: var(--parja-magenta);
        color: #fff;
        border-color: var(--parja-magenta);
    }

    .home-chat-modal-item {
        border: 1px solid var(--parja-border);
        border-radius: 14px;
        padding: 10px 12px;
        background: #fff;
        margin-bottom: 9px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
    }

    .home-chat-modal-item:last-child {
        margin-bottom: 0;
    }

    .home-chat-modal-item.is-entering {
        animation: homeChatItemIn 0.24s ease both;
    }

    @keyframes homeChatItemIn {
        from {
            opacity: 0;
            transform: translateY(8px) scale(0.985);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @media (max-width: 768px) {
        .home-feed-stats-grid {
            grid-template-columns: 1fr;
        }

        .home-reminder-grid {
            grid-template-columns: 1fr;
        }
    }

</style>
@endpush

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        @include('parja::alumni.partials.home-hero-banner')

<div class="home-reminder-board">
    @php
        $personalReminderInfos = collect($reminderAgendaInfos ?? []);
        $personalReminderPreview = $personalReminderInfos->take(3);
        $activeReminderInfos = $personalReminderInfos->filter(function ($agenda) {
            if (!$agenda->tanggal_berakhir) {
                return true;
            }
            return now()->startOfDay()->lte($agenda->tanggal_berakhir->copy()->startOfDay());
        })->values();
        $historyReminderInfos = $personalReminderInfos->filter(function ($agenda) {
            if (!$agenda->tanggal_berakhir) {
                return false;
            }
            return now()->startOfDay()->gt($agenda->tanggal_berakhir->copy()->startOfDay());
        })->values();
    @endphp

    <div class="d-flex align-items-center justify-content-between" style="gap:10px;">
        <div class="home-feed-stats-title" style="margin-bottom:10px;">
            <i class="ri-notification-3-line" style="color: var(--parja-magenta);"></i>
            Reminder Agenda Alumni Kamu
        </div>
        @if($personalReminderInfos->isNotEmpty())
            <button class="btn btn-sm btn-outline-secondary rounded-pill"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#homeReminderAgendaModal"
                    style="font-size:0.7rem; padding:3px 10px;">
                Lihat selengkapnya
            </button>
        @endif
    </div>
    <p class="home-feed-stat-note mb-2">Agenda yang kamu tandai sendiri akan muncul di sini.</p>

    @if($personalReminderPreview->isNotEmpty())
        <div class="home-reminder-grid">
            @foreach($personalReminderPreview as $agenda)
                @php
                    $isAgendaOpen = $agenda->isInteractionOpen();
                    $isRemindedAgenda = $alumni && isset($remindedAgendaIds) ? $remindedAgendaIds->contains($agenda->id) : false;
                    $daysToDeadline = $agenda->tanggal_berakhir ? now()->startOfDay()->diffInDays($agenda->tanggal_berakhir->copy()->startOfDay(), false) : null;
                @endphp
                <div class="home-reminder-item">
                    <div class="home-reminder-meta">
                        <i class="ri-calendar-event-line"></i>
                        {{ $agenda->tanggal_publikasi?->format('d M Y') }}
                        @if($agenda->tanggal_berakhir)
                            • Hingga {{ $agenda->tanggal_berakhir->format('d M Y') }}
                        @endif
                    </div>
                    @if(!is_null($daysToDeadline))
                        @if($daysToDeadline < 0)
                            <div class="home-reminder-deadline-badge expired mb-1">
                                <i class="ri-history-line"></i> Deadline lewat
                            </div>
                        @elseif($daysToDeadline <= 1)
                            <div class="home-reminder-deadline-badge critical mb-1">
                                <i class="ri-alarm-warning-line"></i>
                                {{ $daysToDeadline === 0 ? 'Hari ini' : 'H-1' }}
                            </div>
                        @elseif($daysToDeadline <= 3)
                            <div class="home-reminder-deadline-badge warning mb-1">
                                <i class="ri-time-line"></i> H-{{ $daysToDeadline }}
                            </div>
                        @endif
                    @endif
                    <div class="home-reminder-title">{{ \Illuminate\Support\Str::limit($agenda->judul, 56) }}</div>
                    <div class="home-reminder-actions">
                        @if($alumni)
                                <form action="{{ route('alumni.kegiatan.reminder', $agenda->id) }}"
                                    method="POST"
                                    class="m-0 js-reminder-toggle-form"
                                    data-reminded="{{ $isRemindedAgenda ? '1' : '0' }}"
                                    data-agenda-title="{{ e($agenda->judul) }}">
                                @csrf
                                <button type="submit"
                                        class="home-reminder-btn {{ $isRemindedAgenda ? 'is-on' : '' }}"
                                        {{ $isAgendaOpen ? '' : 'disabled' }}
                                        style="{{ $isAgendaOpen ? '' : 'opacity:0.6;cursor:not-allowed;' }}">
                                    <i class="{{ $isRemindedAgenda ? 'ri-notification-3-fill' : 'ri-notification-3-line' }}"></i>
                                    {{ $isRemindedAgenda ? 'Diingatkan' : 'Ingatkan Saya' }}
                                </button>
                            </form>
                        @else
                            <span class="home-reminder-btn" style="opacity:0.7;"><i class="ri-notification-3-line"></i> Login untuk ingatkan</span>
                        @endif

                        <a href="{{ route('alumni.kegiatan.show', $agenda->id) }}" class="home-reminder-link">Lihat</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="home-feed-stat-note mb-0">Belum ada yang diingatkan. Tandai agenda dari menu Kegiatan &amp; Info Alumni lewat tombol Ingatkan Saya.</p>
    @endif
</div>

<div class="modal fade" id="homeReminderAgendaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:1px solid var(--parja-border);">
            <div class="modal-header" style="border-bottom:1px solid var(--parja-border);">
                <h6 class="modal-title fw-bold" style="color:var(--parja-purple);">
                    <i class="ri-notification-3-line me-2" style="color:var(--parja-magenta);"></i>
                    Reminder Agenda Alumni Kamu
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($personalReminderInfos->isEmpty())
                    <div class="home-feed-stat-note">Belum ada yang diingatkan.</div>
                @else
                    <ul class="nav nav-pills mb-3" role="tablist" style="gap:8px;">
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-sm btn-outline-secondary rounded-pill active"
                                    id="home-reminder-tab-active"
                                    data-bs-toggle="pill"
                                    data-bs-target="#home-reminder-pane-active"
                                    type="button" role="tab">
                                <span class="home-reminder-tab active">
                                    Aktif
                                    <span class="home-reminder-tab-count">{{ $activeReminderInfos->count() }}</span>
                                </span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-sm btn-outline-secondary rounded-pill"
                                    id="home-reminder-tab-history"
                                    data-bs-toggle="pill"
                                    data-bs-target="#home-reminder-pane-history"
                                    type="button" role="tab">
                                <span class="home-reminder-tab">
                                    History
                                    <span class="home-reminder-tab-count">{{ $historyReminderInfos->count() }}</span>
                                </span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home-reminder-pane-active" role="tabpanel" aria-labelledby="home-reminder-tab-active">
                            @if($activeReminderInfos->isEmpty())
                                <div class="home-feed-stat-note">Belum ada reminder aktif yang kamu tandai.</div>
                            @else
                                @foreach($activeReminderInfos as $agenda)
                                    @php
                                        $daysToDeadline = $agenda->tanggal_berakhir ? now()->startOfDay()->diffInDays($agenda->tanggal_berakhir->copy()->startOfDay(), false) : null;
                                        $statusClass = (!is_null($daysToDeadline) && $daysToDeadline <= 1) ? 'critical' : 'warning';
                                    @endphp
                                    <div class="home-reminder-modal-item">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <div class="d-flex align-items-start" style="gap:8px; min-width:0;">
                                                <span class="home-reminder-bell {{ $statusClass }}"><i class="ri-notification-3-line"></i></span>
                                                <div style="min-width:0;">
                                                    <div class="home-reminder-meta mb-1">
                                                        <i class="ri-calendar-event-line"></i>
                                                        {{ $agenda->tanggal_publikasi?->format('d M Y') }}
                                                        @if($agenda->tanggal_berakhir)
                                                            • Hingga {{ $agenda->tanggal_berakhir->format('d M Y') }}
                                                        @endif
                                                    </div>
                                                    @if(!is_null($daysToDeadline))
                                                        @if($daysToDeadline <= 1)
                                                            <div class="home-reminder-deadline-badge critical mb-1"><i class="ri-alarm-warning-line"></i> {{ $daysToDeadline === 0 ? 'Hari ini' : 'H-1' }}</div>
                                                        @elseif($daysToDeadline <= 3)
                                                            <div class="home-reminder-deadline-badge warning mb-1"><i class="ri-time-line"></i> H-{{ $daysToDeadline }}</div>
                                                        @endif
                                                    @endif
                                                    <div class="home-reminder-title mb-1">{{ \Illuminate\Support\Str::limit($agenda->judul, 88) }}</div>
                                                </div>
                                            </div>
                                            <a href="{{ route('alumni.kegiatan.show', $agenda->id) }}" class="home-reminder-link">Lihat</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="tab-pane fade" id="home-reminder-pane-history" role="tabpanel" aria-labelledby="home-reminder-tab-history">
                            @if($historyReminderInfos->isEmpty())
                                <div class="home-feed-stat-note">Belum ada history reminder.</div>
                            @else
                                @foreach($historyReminderInfos as $agenda)
                                    <div class="home-reminder-modal-item">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <div class="d-flex align-items-start" style="gap:8px; min-width:0;">
                                                <span class="home-reminder-bell expired"><i class="ri-notification-3-line"></i></span>
                                                <div style="min-width:0;">
                                                    <div class="home-reminder-meta mb-1">
                                                        <i class="ri-calendar-event-line"></i>
                                                        {{ $agenda->tanggal_publikasi?->format('d M Y') }}
                                                        @if($agenda->tanggal_berakhir)
                                                            • Hingga {{ $agenda->tanggal_berakhir->format('d M Y') }}
                                                        @endif
                                                    </div>
                                                    <div class="home-reminder-deadline-badge expired mb-1"><i class="ri-history-line"></i> Deadline lewat</div>
                                                    <div class="home-reminder-title mb-1">{{ \Illuminate\Support\Str::limit($agenda->judul, 88) }}</div>
                                                </div>
                                            </div>
                                            <a href="{{ route('alumni.kegiatan.show', $agenda->id) }}" class="home-reminder-link">Lihat</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Form "Tulis Artikel Alumni" sengaja dihilangkan dari beranda agar lebih
     ringkas & hemat ruang. Pembuatan post tetap tersedia di halaman Profil. --}}

<div class="d-flex align-items-center justify-content-between mb-3 mt-4">
    <div class="home-feed-stats-title mb-0" style="font-size: 1.1rem;">
        <i class="ri-history-line" style="color: var(--parja-magenta);"></i>
        Kumpulan Postingan Terbaru
    </div>
    <a href="{{ route('alumni.feed.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill fw-semibold" style="font-size:0.75rem;">
        Lihat Semua
    </a>
</div>

@if ($latestPosts->isEmpty())
    <div class="feed-post-card">
        <div class="empty-feed">
            <i class="ri-newspaper-line"></i>
            <p class="mb-0 fw-semibold">Belum ada artikel yang dipublikasikan.</p>
        </div>
    </div>
@else
    @foreach ($latestPosts as $post)
        @php
            $namaAlumni = $post->alumni?->nama ?? 'Alumni';
            $inisial    = strtoupper(substr($namaAlumni, 0, 1));
            $dapil      = $post->alumni?->dapil ?? '-';
            $profil     = $post->alumni?->profile;
            $fotoProfil = $profil?->foto_profil
                ? asset('storage/' . $profil->foto_profil)
                : null;
        @endphp
        <div id="post-{{ $post->id }}" class="feed-post-card">
            <div class="feed-post-header">
                @if ($fotoProfil)
                    <img src="{{ $fotoProfil }}" alt="{{ $namaAlumni }}" class="feed-post-avatar">
                @else
                    <div class="feed-post-avatar">{{ $inisial }}</div>
                @endif
                <div class="feed-post-meta flex-grow-1">
                    <p class="name">{{ $namaAlumni }}</p>
                    <p class="sub">Dapil: {{ $dapil }} &bull; {{ $post->created_at->diffForHumans() }}</p>
                </div>
                <span class="tipe-badge artikel">Artikel</span>
                <span class="tipe-badge" style="background: {{ $post->privasi === 'public' ? 'rgba(13,110,253,0.1)' : 'rgba(65,23,75,0.1)' }}; color: {{ $post->privasi === 'public' ? '#084298' : 'var(--parja-purple)' }};">
                    <i class="{{ $post->privasi === 'public' ? 'ri-earth-line' : 'ri-lock-line' }}"></i>
                    {{ $post->privasi === 'public' ? 'Public' : 'Parja Alumni' }}
                </span>
                @auth('keycloak-external')
                    @if ($alumni && $post->alumni_id === $alumni->id)
                        <button type="button" class="post-settings-btn"
                            onclick="togglePostSettings({{ $post->id }}, event)"
                            title="Pengaturan post">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <div class="post-settings-dropdown" id="settings-menu-{{ $post->id }}">
                            <button type="button" class="post-settings-item"
                                onclick="openEditPostModal({{ $post->id }}, {{ json_encode($post->judul) }}, {{ json_encode($post->konten) }}, {{ json_encode($post->kategori_artikel) }}, {{ json_encode($post->foto_paths ?? []) }})">
                                <i class="ri-edit-line"></i> Edit Post
                            </button>
                            <form action="{{ route('alumni.feed.toggle-privacy', $post->id) }}" method="POST" class="m-0">
                                @csrf
                                @if ($post->privasi === 'public')
                                    <button type="submit" class="post-settings-item">
                                        <i class="ri-lock-line"></i> Jadikan Alumni Only
                                    </button>
                                @elseif ($post->privasi_request === 'public')
                                    <button type="submit" class="post-settings-item" style="color:#b45309;">
                                        <i class="ri-time-line"></i> Batalkan Request Publik
                                    </button>
                                @else
                                    <button type="submit" class="post-settings-item">
                                        <i class="ri-earth-line"></i> Ajukan Jadikan Publik
                                    </button>
                                @endif
                            </form>
                            <div class="post-settings-divider"></div>
                            <form action="{{ route('alumni.feed.destroy', $post->id) }}" method="POST" class="m-0"
                                  id="delete-post-form-{{ $post->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="post-settings-item danger"
                                    onclick="confirmDeletePost({{ $post->id }})">
                                    <i class="ri-delete-bin-line"></i> Hapus Post
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            @if ($post->tipe === 'artikel' && $post->judul)
                <div class="feed-post-title">{{ $post->judul }}</div>
                @if (!empty($post->kategori_artikel))
                    <div>
                        <span style="display:inline-flex; align-items:center; gap:4px; font-size:0.72rem; padding:3px 10px; border-radius:50px; font-weight:600; background:rgba(191,0,80,0.08); color:var(--parja-magenta); border:1px solid rgba(191,0,80,0.18); margin-top:6px; margin-bottom: 8px;">
                            <i class="ri-price-tag-3-line"></i>
                            {{ $post->kategori_artikel }}
                        </span>
                    </div>
                @endif
            @endif

            <div class="feed-post-content">{{ \Illuminate\Support\Str::limit($post->konten, 250) }}
                @if(strlen($post->konten) > 250)
                    <a href="{{ route('alumni.feed.index', ['focus_post' => $post->id]) }}" class="text-decoration-none fw-semibold" style="color:var(--parja-magenta);">Baca selengkapnya</a>
                @endif
            </div>

            @if (!empty($post->foto_paths))
                @php $fotoCount = count($post->foto_paths); @endphp
                <div class="feed-photo-grid count-{{ min($fotoCount, 5) }}">
                    @foreach ($post->foto_paths as $foto)
                        <img src="{{ asset('storage/' . $foto) }}"
                             alt="Foto post"
                             onclick="openLightbox('{{ asset('storage/' . $foto) }}')">
                    @endforeach
                </div>
            @endif

            {{-- Like & Komentar Bar --}}
            @php
                $likeCount    = $post->likes->count();
                $isLiked      = isset($likedPostIds) && collect($likedPostIds)->contains($post->id);
                $commentCount = $post->comments->count();
            @endphp

            <div class="post-action-bar">
                @auth('keycloak-external')
                    <button type="button"
                        class="post-action-btn like-btn {{ $isLiked ? 'liked' : '' }}"
                        data-post-id="{{ $post->id }}"
                        data-liked="{{ $isLiked ? '1' : '0' }}"
                        data-url="{{ route('alumni.feed.like', $post->id) }}">
                        <i class="{{ $isLiked ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                        <span class="like-count">{{ $likeCount }}</span>
                        <span>Suka</span>
                    </button>
                    <a href="{{ route('alumni.feed.index', ['focus_post' => $post->id]) }}"
                        class="post-action-btn text-decoration-none" style="margin-left: 10px;">
                        <i class="ri-chat-1-line"></i>
                        {{ $commentCount }} Komentar
                    </a>
                @else
                    <span class="post-action-btn" style="cursor:default;">
                        <i class="ri-heart-line"></i> {{ $likeCount }} Suka
                    </span>
                    <span class="post-action-btn" style="cursor:default; margin-left: 10px;">
                        <i class="ri-chat-1-line"></i> {{ $commentCount }} Komentar
                    </span>
                @endauth
            </div>
        </div>
    @endforeach
@endif

    </div>

    <div class="col-lg-4">

        {{-- Grup Chat Widget — DISEMBUNYIKAN sementara: fitur kanal/group chat belum diperlukan. --}}
        {{-- Untuk menampilkan lagi: ganti `@if (false)` di bawah menjadi `@if (true)` (atau hapus pembungkus @if ... @endif). --}}
        @if (false)
        <div class="sidebar-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <p class="fw-bold mb-0" style="color: var(--parja-purple); font-size: 0.9rem;">
                    <i class="ri-chat-3-line me-1" style="color: var(--parja-magenta);"></i>
                    Grup Chat
                </p>
                <a href="{{ route('alumni.chat.index') }}"
                   style="font-size:0.75rem; color:var(--parja-magenta); font-weight:600; text-decoration:none;">
                    Lihat Semua
                </a>
            </div>

            @if ($activeChannels->isEmpty())
                <p class="small text-muted mb-2">Belum ada kanal yang diikuti.</p>
            @else
                @foreach ($activeChannels as $ch)
                    @php $inisial = strtoupper(substr($ch->nama, 0, 1)); @endphp
                    <a href="{{ route('alumni.chat.show', $ch->id) }}"
                       class="d-flex align-items-center gap-2 mb-2 text-decoration-none"
                       style="padding:8px; border-radius:10px; border:1px solid var(--parja-border); transition:background 0.15s;"
                       onmouseover="this.style.background='rgba(191,0,80,0.05)'" onmouseout="this.style.background='transparent'">
                        @if ($ch->avatar)
                            <img src="{{ asset('storage/' . $ch->avatar) }}"
                                 style="width:34px;height:34px;border-radius:9px;object-fit:cover;flex-shrink:0;"
                                 alt="{{ $ch->nama }}">
                        @else
                            <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--parja-magenta),var(--parja-purple));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:0.8rem;flex-shrink:0;">
                                {{ $inisial }}
                            </div>
                        @endif
                        <div style="flex:1;min-width:0;">
                            <p class="mb-0 fw-semibold" style="font-size:0.82rem;color:var(--parja-purple);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $ch->nama }}</p>
                            <p class="mb-0" style="font-size:0.72rem;color:var(--parja-muted);">
                                @if ($ch->latestMessage)
                                    {{ Str::limit($ch->latestMessage->pesan, 28) }}
                                @else
                                    <em>Belum ada pesan</em>
                                @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            @endif

            <a href="{{ route('alumni.chat.index') }}"
               class="btn btn-sm fw-semibold rounded-pill w-100 mt-1"
               style="background:var(--parja-magenta);color:#fff;font-size:0.8rem;">
                <i class="ri-add-line"></i> Buat / Cari Kanal
            </a>
        </div>
        @endif
        {{-- /Grup Chat Widget (disembunyikan) --}}

        {{-- Widget Info Kegiatan Alumni --}}
        @include('parja::alumni.partials.sidebar-kegiatan')
    
        @include('parja::alumni.partials.slideshow')

        @php
            $feedTop10 = collect($feedStats['trending_top10'] ?? []);
            $chatTop10 = collect($chatStats['trending_top10'] ?? []);
            $infoTop10 = collect($infoStats['trending_top10'] ?? []);
        @endphp

        <div class="home-feed-stats mt-3">
            <div class="home-feed-stats-title">
                <i class="ri-megaphone-line" style="color: var(--parja-magenta);"></i>
                Statistik Kegiatan &amp; Info Alumni (Update Otomatis)
            </div>

            <div class="home-feed-stats-grid">


                <div class="home-feed-stat-item"
                     role="button"
                     data-bs-toggle="modal"
                     data-bs-target="#homeTop10InfoModal"
                     data-info-modal-filter="berita">
                    <div class="d-flex align-items-center justify-content-between" style="gap:10px;">
                        <div class="home-feed-stat-label mb-0">Trending Berita Minggu Ini</div>
                        <i class="ri-eye-line text-muted action-icon" style="font-size: 1.1rem; transition: color 0.15s ease;"></i>
                    </div>
                    @php
                        $topInfoBerita = $infoTop10->first(fn ($item) => ($item['tipe'] ?? '') === 'berita');
                    @endphp
                    @if($topInfoBerita)
                        <div class="home-feed-trending-title home-feed-trending-title-row" style="margin-top:8px;">
                            <i class="ri-vip-crown-2-line" style="color:#f6b100;"></i>
                            <span>{{ \Illuminate\Support\Str::limit($topInfoBerita['title'], 42) }}</span>
                        </div>
                        <div class="home-feed-trending-meta">
                            Skor {{ $topInfoBerita['score'] }} ({{ $topInfoBerita['likes'] }} like • {{ $topInfoBerita['comments'] }} komentar • {{ $topInfoBerita['reminders'] }} pengingat)
                        </div>
                    @else
                        <div class="home-feed-trending-title" style="margin-top:8px;">Belum ada tren berita minggu ini</div>
                        <div class="home-feed-trending-meta">Reset otomatis setiap awal minggu</div>
                    @endif
                </div>

                <div class="home-feed-stat-item"
                     role="button"
                     data-bs-toggle="modal"
                     data-bs-target="#homeTop10InfoModal"
                     data-info-modal-filter="kegiatan">
                    <div class="d-flex align-items-center justify-content-between" style="gap:10px;">
                        <div class="home-feed-stat-label mb-0">Trending Kegiatan Minggu Ini</div>
                        <i class="ri-eye-line text-muted action-icon" style="font-size: 1.1rem; transition: color 0.15s ease;"></i>
                    </div>
                    @php
                        $topInfoKegiatan = $infoTop10->first(fn ($item) => ($item['tipe'] ?? '') === 'kegiatan');
                    @endphp
                    @if($topInfoKegiatan)
                        <div class="home-feed-trending-title home-feed-trending-title-row" style="margin-top:8px;">
                            <i class="ri-vip-crown-2-line" style="color:#f6b100;"></i>
                            <span>{{ \Illuminate\Support\Str::limit($topInfoKegiatan['title'], 42) }}</span>
                        </div>
                        <div class="home-feed-trending-meta">
                            Skor {{ $topInfoKegiatan['score'] }} ({{ $topInfoKegiatan['likes'] }} like • {{ $topInfoKegiatan['comments'] }} komentar • {{ $topInfoKegiatan['reminders'] }} pengingat)
                        </div>
                    @else
                        <div class="home-feed-trending-title" style="margin-top:8px;">Belum ada tren kegiatan minggu ini</div>
                        <div class="home-feed-trending-meta">Reset otomatis setiap awal minggu</div>
                    @endif
                </div>

                <div class="home-feed-stat-item"
                     role="button"
                     data-bs-toggle="modal"
                     data-bs-target="#homeTop10InfoModal"
                     data-info-modal-filter="pengumuman">
                    <div class="d-flex align-items-center justify-content-between" style="gap:10px;">
                        <div class="home-feed-stat-label mb-0">Trending Pengumuman Minggu Ini</div>
                        <i class="ri-eye-line text-muted action-icon" style="font-size: 1.1rem; transition: color 0.15s ease;"></i>
                    </div>
                    @php
                        $topInfoPengumuman = $infoTop10->first(fn ($item) => ($item['tipe'] ?? '') === 'pengumuman');
                    @endphp
                    @if($topInfoPengumuman)
                        <div class="home-feed-trending-title home-feed-trending-title-row" style="margin-top:8px;">
                            <i class="ri-vip-crown-2-line" style="color:#f6b100;"></i>
                            <span>{{ \Illuminate\Support\Str::limit($topInfoPengumuman['title'], 42) }}</span>
                        </div>
                        <div class="home-feed-trending-meta">
                            Skor {{ $topInfoPengumuman['score'] }} ({{ $topInfoPengumuman['likes'] }} like • {{ $topInfoPengumuman['comments'] }} komentar • {{ $topInfoPengumuman['reminders'] }} pengingat)
                        </div>
                    @else
                        <div class="home-feed-trending-title" style="margin-top:8px;">Belum ada tren pengumuman minggu ini</div>
                        <div class="home-feed-trending-meta">Reset otomatis setiap awal minggu</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="home-feed-stats mt-3">
            <div class="home-feed-stats-title">
                <i class="ri-bar-chart-box-line" style="color: var(--parja-magenta);"></i>
                Statistik Feed Post (Update Otomatis)
            </div>

            <div class="home-feed-stats-grid">


                <div class="home-feed-stat-item"
                     role="button"
                     data-bs-toggle="modal"
                     data-bs-target="#homeTop10FeedModal"
                     data-feed-modal-filter="alumni">
                    <div class="d-flex align-items-center justify-content-between" style="gap:10px;">
                        <div class="home-feed-stat-label mb-0">Trending Alumni Minggu Ini</div>
                        <i class="ri-eye-line text-muted action-icon" style="font-size: 1.1rem; transition: color 0.15s ease;"></i>
                    </div>
                    @php
                        $topFeedAlumni = $feedTop10->first(fn ($item) => ($item['privacy'] ?? '') === 'alumni');
                    @endphp
                    @if($topFeedAlumni)
                        <div class="home-feed-trending-title" style="margin-top:8px;">
                            <i class="ri-vip-crown-2-line" style="color:#f6b100;"></i>
                            {{ \Illuminate\Support\Str::limit($topFeedAlumni['title'], 42) }}
                        </div>
                        <div class="home-feed-trending-meta">
                            Score {{ $topFeedAlumni['score'] }}
                            ({{ $topFeedAlumni['likes'] }} like, {{ $topFeedAlumni['comments'] }} komentar)
                        </div>
                    @else
                        <div class="home-feed-trending-title" style="margin-top:8px;">Belum ada tren alumni minggu ini</div>
                        <div class="home-feed-trending-meta">Reset otomatis setiap awal minggu</div>
                    @endif
                </div>

                <div class="home-feed-stat-item"
                     role="button"
                     data-bs-toggle="modal"
                     data-bs-target="#homeTop10FeedModal"
                     data-feed-modal-filter="public">
                    <div class="d-flex align-items-center justify-content-between" style="gap:10px;">
                        <div class="home-feed-stat-label mb-0">Trending Publik Minggu Ini</div>
                        <i class="ri-eye-line text-muted action-icon" style="font-size: 1.1rem; transition: color 0.15s ease;"></i>
                    </div>
                    @php
                        $topFeedPublik = $feedTop10->first(fn ($item) => ($item['privacy'] ?? '') === 'public');
                    @endphp
                    @if($topFeedPublik)
                        <div class="home-feed-trending-title">{{ \Illuminate\Support\Str::limit($topFeedPublik['title'], 42) }}</div>
                        <div class="home-feed-trending-meta">
                            Score {{ $topFeedPublik['score'] }}
                            ({{ $topFeedPublik['likes'] }} like, {{ $topFeedPublik['comments'] }} komentar)
                        </div>
                    @else
                        <div class="home-feed-trending-title">Belum ada tren publik minggu ini</div>
                        <div class="home-feed-trending-meta">Reset otomatis setiap awal minggu</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Statistik Group Chat disembunyikan sementara
        <div class="home-feed-stats mt-3">
            <div class="home-feed-stats-title">
                <i class="ri-chat-3-line" style="color: var(--parja-magenta);"></i>
                Statistik Group Chat (Update Otomatis)
            </div>

            <div class="home-feed-stats-grid">


                <div class="home-feed-stat-item"
                     role="button"
                     data-bs-toggle="modal"
                     data-bs-target="#homeTop10ChatModal"
                     id="homeTop10ChatToggleBtn">
                    <div class="d-flex align-items-center justify-content-between" style="gap:10px;">
                        <div class="home-feed-stat-label mb-0">Top 10 Group Chat Trending Minggu Ini</div>
                        <i class="ri-eye-line text-muted action-icon" style="font-size: 1.1rem; transition: color 0.15s ease;"></i>
                    </div>

                    @if($chatTop10->isNotEmpty())
                        <div class="home-feed-trending-title" style="margin-top:8px;">
                            <i class="ri-vip-crown-2-line" style="color:#f6b100;"></i>
                            {{ \Illuminate\Support\Str::limit($chatTop10->first()['nama'], 40) }}
                        </div>
                        <div class="home-feed-trending-meta">
                            Skor {{ $chatTop10->first()['score'] }}
                            ({{ $chatTop10->first()['weekly_messages'] }} obrolan minggu ini, {{ $chatTop10->first()['weekly_members'] }} member baru)
                        </div>
                    @else
                        <div class="home-feed-trending-title" style="margin-top:8px;">Belum ada tren minggu ini</div>
                        <div class="home-feed-trending-meta">Reset otomatis setiap awal minggu</div>
                    @endif
                </div>
            </div>
        </div>
        --}}
    </div>
</div>

<div class="modal fade" id="homeTop10FeedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:1px solid var(--parja-border);">
            <div class="modal-header" style="border-bottom:1px solid var(--parja-border);">
                <h6 class="modal-title fw-bold" style="color:var(--parja-purple);">
                    <i class="ri-fire-line me-2" style="color:var(--parja-magenta);"></i>
                    Top 10 Feed Post Trending Minggu Ini
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-feed-modal-filter active" data-filter="all">Semua</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-feed-modal-filter" data-filter="alumni">Alumni</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-feed-modal-filter" data-filter="public">Publik</button>
                    </div>
                </div>

                <div id="homeTop10FeedList">
                    @forelse($feedTop10 as $index => $item)
                        <div class="home-feed-modal-item"
                             data-feed-item="1"
                             data-privacy="{{ $item['privacy'] }}"
                             data-score="{{ $item['score'] }}">
                            <a href="{{ route('alumni.feed.index', ['focus_post' => $item['id']]) }}" class="d-flex align-items-start justify-content-between gap-2 text-decoration-none">
                                <div class="d-flex align-items-start" style="gap:8px; min-width:0;">
                                    <div class="home-feed-rank" data-rank>{{ $index + 1 }}</div>
                                    <div style="min-width:0;">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="home-feed-privacy-badge {{ ($item['privacy'] ?? '') === 'public' ? 'privacy-public' : 'privacy-alumni' }}">
                                                {{ ($item['privacy'] ?? '') === 'public' ? 'Publik' : 'Alumni' }}
                                            </span>
                                        </div>
                                        <p class="home-feed-modal-title">{{ \Illuminate\Support\Str::limit($item['title'], 84) }}</p>
                                        <div class="home-feed-modal-meta">
                                            {{ $item['likes'] }} like • {{ $item['comments'] }} komentar
                                        </div>
                                    </div>
                                </div>
                                <div class="home-chat-trending-score">Skor {{ $item['score'] }}</div>
                            </a>
                        </div>
                    @empty
                        <div class="home-feed-stat-note" id="homeTop10FeedEmpty">Belum ada post dengan aktivitas minggu ini.</div>
                    @endforelse
                </div>
                <div class="home-feed-stat-note d-none" id="homeTop10FeedFilteredEmpty">Tidak ada post untuk filter ini.</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="homeTop10InfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:1px solid var(--parja-border);">
            <div class="modal-header" style="border-bottom:1px solid var(--parja-border);">
                <h6 class="modal-title fw-bold" style="color:var(--parja-purple);">
                    <i class="ri-fire-line me-2" style="color:var(--parja-magenta);"></i>
                    Top 10 Kegiatan &amp; Info Trending Minggu Ini
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-info-modal-filter active" data-filter="all">Semua</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-info-modal-filter" data-filter="berita">Berita</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-info-modal-filter" data-filter="kegiatan">Kegiatan</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-info-modal-filter" data-filter="pengumuman">Pengumuman</button>
                    </div>
                </div>

                <div id="homeTop10InfoList">
                    @forelse($infoTop10 as $index => $item)
                        <div class="home-info-modal-item"
                             data-info-item="1"
                             data-tipe="{{ $item['tipe'] }}"
                             data-score="{{ $item['score'] }}">
                            <a href="{{ route('alumni.kegiatan.show', $item['id']) }}" class="d-flex align-items-start justify-content-between gap-2 text-decoration-none">
                                <div class="d-flex align-items-start" style="gap:8px; min-width:0;">
                                    <div class="home-chat-trending-rank" data-rank>{{ $index + 1 }}</div>
                                    <div style="min-width:0;">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="home-info-type-badge type-{{ $item['tipe'] }}">
                                                {{ ucfirst($item['tipe']) }}
                                            </span>
                                        </div>
                                        <p class="home-feed-modal-title">{{ \Illuminate\Support\Str::limit($item['title'], 84) }}</p>
                                        <div class="home-feed-modal-meta">
                                            {{ $item['likes'] }} like • {{ $item['comments'] }} komentar • {{ $item['reminders'] }} pengingat
                                        </div>
                                    </div>
                                </div>
                                <div class="home-chat-trending-score">Skor {{ $item['score'] }}</div>
                            </a>
                        </div>
                    @empty
                        <div class="home-feed-stat-note" id="homeTop10InfoEmpty">Belum ada info dengan aktivitas minggu ini.</div>
                    @endforelse
                </div>
                <div class="home-feed-stat-note d-none" id="homeTop10InfoFilteredEmpty">Tidak ada info untuk filter ini.</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="homeTop10ChatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:1px solid var(--parja-border);">
            <div class="modal-header" style="border-bottom:1px solid var(--parja-border);">
                <h6 class="modal-title fw-bold" style="color:var(--parja-purple);">
                    <i class="ri-fire-line me-2" style="color:var(--parja-magenta);"></i>
                    Top 10 Group Chat Trending Minggu Ini
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-chat-modal-filter active" data-filter="all">Semua</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-chat-modal-filter" data-filter="public">Publik</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill home-chat-modal-filter" data-filter="private">Private</button>
                    </div>
                </div>

                <div id="homeTop10ChatList">
                    @forelse($chatTop10 as $index => $item)
                        <div class="home-chat-modal-item"
                             data-chat-item="1"
                             data-public="{{ $item['is_public'] ? '1' : '0' }}"
                             data-score="{{ $item['score'] }}"
                             data-weekly_messages="{{ $item['weekly_messages'] }}"
                             data-weekly_members="{{ $item['weekly_members'] }}">
                            <a href="{{ route('alumni.chat.show', $item['id']) }}" class="d-flex align-items-start justify-content-between gap-2 text-decoration-none">
                                <div class="d-flex align-items-start" style="gap:8px; min-width:0;">
                                    <div class="home-chat-trending-rank" data-rank>{{ $index + 1 }}</div>
                                    <div style="min-width:0;">
                                        <p class="home-chat-trending-name">{{ \Illuminate\Support\Str::limit($item['nama'], 64) }}</p>
                                        <div class="home-chat-trending-meta">
                                            {{ $item['is_public'] ? 'Publik' : 'Private' }}
                                            • {{ $item['weekly_messages'] }} obrolan minggu ini
                                            • {{ $item['weekly_members'] }} member baru
                                            • {{ $item['members'] }} anggota
                                        </div>
                                    </div>
                                </div>
                                <div class="home-chat-trending-score">Skor {{ $item['score'] }}</div>
                            </a>
                        </div>
                    @empty
                        <div class="home-feed-stat-note" id="homeTop10ChatEmpty">Belum ada kanal dengan aktivitas minggu ini.</div>
                    @endforelse
                </div>
                <div class="home-feed-stat-note d-none" id="homeTop10ChatFilteredEmpty">Tidak ada kanal untuk filter ini.</div>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div class="pf-lightbox" id="pfLightbox" onclick="closeLightbox()">
    <span class="pf-lightbox-close" onclick="closeLightbox()">&times;</span>
    <img src="" id="pfLightboxImg" alt="Preview Foto">
</div>

{{-- Edit Post Modal --}}
<div class="post-edit-modal-backdrop" id="editPostBackdrop" onclick="closeEditPostModal(event)">
    <div class="post-edit-modal" onclick="event.stopPropagation()">
        <button type="button" class="post-edit-modal-close" onclick="closeEditPostModalDirect()">
            <i class="ri-close-line"></i>
        </button>
        <div class="post-edit-modal-title">
            <i class="ri-edit-box-line"></i> Edit Post
        </div>
        <form id="editPostForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="post-edit-label">Judul Artikel</label>
                <input type="text" name="judul" id="editJudul" class="post-edit-input"
                       placeholder="Judul artikel..." maxlength="255" required>
            </div>
            <div class="mb-3">
                <label class="post-edit-label">Konten</label>
                <textarea name="konten" id="editKonten" class="post-edit-textarea"
                          placeholder="Tulis konten..." maxlength="5000" required></textarea>
            </div>
            <div class="mb-3">
                <label class="post-edit-label">Kategori (opsional)</label>
                <select name="kategori_artikel" id="editKategori" class="post-edit-select">
                    <option value="">-- Tidak ada kategori --</option>
                    @foreach(['Kesehatan','Pendidikan','Teknologi Informasi','Hukum','Ekonomi','Politik','Sosial Budaya','Lingkungan Hidup','Olahraga','Seni & Budaya'] as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2" id="editExistingPhotos" style="display:none;">
                <label class="post-edit-label">Foto Saat Ini</label>
                <div id="editExistingPhotosGrid" style="display:flex;gap:8px;flex-wrap:wrap;"></div>
                <input type="hidden" name="remove_foto_indexes" id="removeIndexesInput" value="">
            </div>
            <div class="mb-3">
                <label class="post-edit-label">Tambah Foto Baru (maks 5 total)</label>
                <input type="file" name="foto[]" id="editFotoInput" class="post-edit-input"
                       accept="image/jpeg,image/png,image/webp" multiple style="padding:7px 10px;">
            </div>
            <div class="post-edit-actions">
                <button type="button" class="post-edit-btn-cancel" onclick="closeEditPostModalDirect()">Batal</button>
                <button type="submit" class="post-edit-btn-save">
                    <i class="ri-save-line"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Lightbox
    function openLightbox(src) {
        document.getElementById('pfLightboxImg').src = src;
        document.getElementById('pfLightbox').classList.add('show');
    }

    function closeLightbox() {
        document.getElementById('pfLightbox').classList.remove('show');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLightbox();
    });

    // Like via AJAX
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.dataset.postId;
            const url    = this.dataset.url;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                const countEl = this.querySelector('.like-count');
                const iconEl  = this.querySelector('i');
                if (data.liked) {
                    this.classList.add('liked');
                    iconEl.className = 'ri-heart-fill';
                } else {
                    this.classList.remove('liked');
                    iconEl.className = 'ri-heart-line';
                }
                if (countEl) countEl.textContent = data.count;
            })
            .catch(() => {});
        });
    });

    (function () {
        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!form || !form.classList || !form.classList.contains('js-reminder-toggle-form')) {
                return;
            }

            const isReminded = String(form.dataset.reminded || '0') === '1';
            if (!isReminded) {
                return;
            }

            event.preventDefault();

            const agendaTitle = form.dataset.agendaTitle || 'agenda ini';

            const proceed = function () {
                form.dataset.reminded = '0';
                form.submit();
            };

            if (typeof Swal === 'undefined') {
                if (confirm('Batalkan pengingat untuk "' + agendaTitle + '"?')) {
                    proceed();
                }
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Batalkan pengingat?',
                text: 'Agenda "' + agendaTitle + '" akan dihapus dari reminder kamu.',
                showCancelButton: true,
                confirmButtonText: 'Ya, batalkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#bf0050',
            }).then(function (result) {
                if (result.isConfirmed) {
                    proceed();
                }
            });
        });
    })();

    (function () {
        const listEl = document.getElementById('homeTop10FeedList');
        const modalEl = document.getElementById('homeTop10FeedModal');
        const filterButtons = Array.from(document.querySelectorAll('.home-feed-modal-filter'));
        const openerButtons = Array.from(document.querySelectorAll('[data-feed-modal-filter]'));
        const emptyFilteredEl = document.getElementById('homeTop10FeedFilteredEmpty');
        if (!listEl || filterButtons.length === 0 || !modalEl) return;

        const originalItems = Array.from(listEl.querySelectorAll('[data-feed-item]'));
        if (originalItems.length === 0) return;

        let activeFilter = 'all';
        let requestedFilter = 'all';

        function applyFeedRender() {
            const filtered = originalItems.filter(function (node) {
                const privacy = String(node.dataset.privacy || 'alumni');
                if (activeFilter === 'all') return true;
                return privacy === activeFilter;
            });

            filtered.sort(function (a, b) {
                return Number(b.dataset.score || 0) - Number(a.dataset.score || 0);
            });

            listEl.innerHTML = '';

            if (filtered.length === 0) {
                emptyFilteredEl.classList.remove('d-none');
                return;
            }

            emptyFilteredEl.classList.add('d-none');

            filtered.forEach(function (node, index) {
                const rankEl = node.querySelector('[data-rank]');
                if (rankEl) {
                    rankEl.textContent = String(index + 1);
                    rankEl.classList.remove('rank-1', 'rank-2', 'rank-3');
                    if (index === 0) rankEl.classList.add('rank-1');
                    if (index === 1) rankEl.classList.add('rank-2');
                    if (index === 2) rankEl.classList.add('rank-3');
                }

                listEl.appendChild(node);

                node.classList.remove('is-entering');
                void node.offsetWidth;
                node.classList.add('is-entering');
                window.setTimeout(function () {
                    node.classList.remove('is-entering');
                }, 280);
            });
        }

        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                activeFilter = btn.dataset.filter || 'all';
                filterButtons.forEach(function (candidate) {
                    candidate.classList.toggle('active', candidate === btn);
                });
                applyFeedRender();
            });
        });

        openerButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                requestedFilter = btn.dataset.feedModalFilter || 'all';
            });
        });

        modalEl.addEventListener('show.bs.modal', function () {
            activeFilter = requestedFilter || 'all';
            filterButtons.forEach(function (candidate) {
                candidate.classList.toggle('active', (candidate.dataset.filter || 'all') === activeFilter);
            });
            applyFeedRender();
        });

        applyFeedRender();
    })();

    (function () {
        const listEl = document.getElementById('homeTop10InfoList');
        const modalEl = document.getElementById('homeTop10InfoModal');
        const filterButtons = Array.from(document.querySelectorAll('.home-info-modal-filter'));
        const openerButtons = Array.from(document.querySelectorAll('[data-info-modal-filter]'));
        const emptyFilteredEl = document.getElementById('homeTop10InfoFilteredEmpty');
        if (!listEl || filterButtons.length === 0 || !modalEl) return;

        const originalItems = Array.from(listEl.querySelectorAll('[data-info-item]'));
        if (originalItems.length === 0) return;

        let activeFilter = 'all';
        let requestedFilter = 'all';

        function applyInfoRender() {
            const filtered = originalItems.filter(function (node) {
                const tipe = String(node.dataset.tipe || 'berita');
                if (activeFilter === 'all') return true;
                return tipe === activeFilter;
            });

            filtered.sort(function (a, b) {
                return Number(b.dataset.score || 0) - Number(a.dataset.score || 0);
            });

            listEl.innerHTML = '';

            if (filtered.length === 0) {
                emptyFilteredEl.classList.remove('d-none');
                return;
            }

            emptyFilteredEl.classList.add('d-none');

            filtered.forEach(function (node, index) {
                const rankEl = node.querySelector('[data-rank]');
                if (rankEl) {
                    rankEl.textContent = String(index + 1);
                    rankEl.classList.remove('rank-1', 'rank-2', 'rank-3');
                    if (index === 0) rankEl.classList.add('rank-1');
                    if (index === 1) rankEl.classList.add('rank-2');
                    if (index === 2) rankEl.classList.add('rank-3');
                }

                listEl.appendChild(node);

                node.classList.remove('is-entering');
                void node.offsetWidth;
                node.classList.add('is-entering');
                window.setTimeout(function () {
                    node.classList.remove('is-entering');
                }, 280);
            });
        }

        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                activeFilter = btn.dataset.filter || 'all';
                filterButtons.forEach(function (candidate) {
                    candidate.classList.toggle('active', candidate === btn);
                });
                applyInfoRender();
            });
        });

        openerButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                requestedFilter = btn.dataset.infoModalFilter || 'all';
            });
        });

        modalEl.addEventListener('show.bs.modal', function () {
            activeFilter = requestedFilter || 'all';
            filterButtons.forEach(function (candidate) {
                candidate.classList.toggle('active', (candidate.dataset.filter || 'all') === activeFilter);
            });
            applyInfoRender();
        });

        applyInfoRender();
    })();

    (function () {
        const listEl = document.getElementById('homeTop10ChatList');
        const filterButtons = Array.from(document.querySelectorAll('.home-chat-modal-filter'));
        const emptyFilteredEl = document.getElementById('homeTop10ChatFilteredEmpty');
        if (!listEl || filterButtons.length === 0) return;

        const originalItems = Array.from(listEl.querySelectorAll('[data-chat-item]'));
        if (originalItems.length === 0) return;

        let activeFilter = 'all';

        function applyRender() {
            const filtered = originalItems.filter(function (node) {
                const isPublic = node.dataset.public === '1';
                if (activeFilter === 'public') return isPublic;
                if (activeFilter === 'private') return !isPublic;
                return true;
            });

            filtered.sort(function (a, b) {
                return Number(b.dataset.score || 0) - Number(a.dataset.score || 0);
            });

            listEl.innerHTML = '';

            if (filtered.length === 0) {
                emptyFilteredEl.classList.remove('d-none');
                return;
            }

            emptyFilteredEl.classList.add('d-none');

            filtered.forEach(function (node, index) {
                const rankEl = node.querySelector('[data-rank]');
                if (rankEl) {
                    rankEl.textContent = String(index + 1);
                    rankEl.classList.remove('rank-1', 'rank-2', 'rank-3');
                    if (index === 0) rankEl.classList.add('rank-1');
                    if (index === 1) rankEl.classList.add('rank-2');
                    if (index === 2) rankEl.classList.add('rank-3');
                }
                listEl.appendChild(node);

                node.classList.remove('is-entering');
                void node.offsetWidth;
                node.classList.add('is-entering');
                window.setTimeout(function () {
                    node.classList.remove('is-entering');
                }, 280);
            });
        }

        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                activeFilter = btn.dataset.filter || 'all';
                filterButtons.forEach(function (candidate) {
                    candidate.classList.toggle('active', candidate === btn);
                });
                applyRender();
            });
        });
        applyRender();
    })();

    (function () {
        const activeBtn = document.getElementById('home-reminder-tab-active');
        const historyBtn = document.getElementById('home-reminder-tab-history');
        if (!activeBtn || !historyBtn) return;

        function syncTabChip(button) {
            const chips = [
                activeBtn.querySelector('.home-reminder-tab'),
                historyBtn.querySelector('.home-reminder-tab'),
            ];

            chips.forEach(function (chip) {
                if (!chip) return;
                chip.classList.remove('active');
            });

            const current = button.querySelector('.home-reminder-tab');
            if (current) current.classList.add('active');
        }

        activeBtn.addEventListener('shown.bs.tab', function () {
            syncTabChip(activeBtn);
        });
        historyBtn.addEventListener('shown.bs.tab', function () {
            syncTabChip(historyBtn);
        });
    })();

    /* ── Post Settings Dropdown ── */
    function togglePostSettings(postId, e) {
        e.stopPropagation();
        const target = document.getElementById('settings-menu-' + postId);
        const isOpen = target && target.classList.contains('open');
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
        if (!isOpen && target) target.classList.add('open');
    }
    document.addEventListener('click', function () {
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
    });

    /* ── Edit Post Modal ── */
    var _removeFotoIndexes = [];

    function openEditPostModal(id, judul, konten, kategori, fotos) {
        _removeFotoIndexes = [];
        const baseUrl = '{{ url("alumniparja/feed") }}';

        document.getElementById('editJudul').value = judul || '';
        document.getElementById('editKonten').value = konten || '';
        document.getElementById('editKategori').value = kategori || '';
        document.getElementById('editFotoInput').value = '';
        document.getElementById('removeIndexesInput').value = '';

        const grid = document.getElementById('editExistingPhotosGrid');
        const wrap = document.getElementById('editExistingPhotos');
        grid.innerHTML = '';
        if (fotos && fotos.length > 0) {
            fotos.forEach(function (path, idx) {
                const div = document.createElement('div');
                div.style.cssText = 'position:relative;display:inline-block;';
                div.innerHTML = '<img src="{{ asset("storage") }}/' + path + '" style="width:72px;height:72px;object-fit:cover;border-radius:10px;border:1.5px solid var(--parja-border);">'
                    + '<button type="button" onclick="removeFotoByIndex(' + idx + ', this.parentElement)" '
                    + 'style="position:absolute;top:-7px;right:-7px;background:#c0392b;border:none;color:#fff;border-radius:50%;width:20px;height:20px;font-size:0.75rem;line-height:20px;text-align:center;cursor:pointer;padding:0;">&times;</button>';
                div.dataset.fotoIdx = idx;
                grid.appendChild(div);
            });
            wrap.style.display = '';
        } else {
            wrap.style.display = 'none';
        }

        document.getElementById('editPostForm').action = baseUrl + '/' + id;
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
        document.getElementById('editPostBackdrop').classList.add('open');
    }

    function removeFotoByIndex(idx, el) {
        if (!_removeFotoIndexes.includes(idx)) _removeFotoIndexes.push(idx);
        document.getElementById('removeIndexesInput').value = _removeFotoIndexes.join(',');
        if (el) el.remove();
    }

    function closeEditPostModal(e) {
        if (e.target === document.getElementById('editPostBackdrop')) {
            document.getElementById('editPostBackdrop').classList.remove('open');
        }
    }
    function closeEditPostModalDirect() {
        document.getElementById('editPostBackdrop').classList.remove('open');
    }

    /* ── SweetAlert Confirm Delete ── */
    function confirmDeletePost(postId) {
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
        Swal.fire({
            title: 'Hapus Post?',
            text: 'Post yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            iconColor: '#c0392b',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'fw-semibold px-4',
                cancelButton: 'fw-semibold px-4',
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('delete-post-form-' + postId).submit();
            }
        });
    }
</script>
@endpush
