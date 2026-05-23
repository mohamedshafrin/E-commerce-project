<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Central Trading</title>
    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark: #081b29;
            --ink: #ededed;
            --muted: #b2c2cc;
            --line: rgba(237,237,237,.16);
            --paper: #081b29;
            --white: #ffffff;
            --brand: #00d424;
            --brand-strong: #00f02a;
            --danger: #df4f64;
            --warning: #f4b740;
            --panel: #0b2435;
            --panel-soft: #0e2c42;
            --shadow: 0 18px 60px rgba(0, 0, 0, .32);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            color: var(--ink);
            background: var(--paper);
            font-family: Inter, "Segoe UI", Arial, Helvetica, sans-serif;
        }
        button, input, select, textarea { font: inherit; }
        button { cursor: pointer; }
        a { color: inherit; text-decoration: none; }

        .app-header {
            position: sticky;
            top: 0;
            z-index: 60;
            background: rgba(255,255,255,.96);
            color: var(--ink);
            border-bottom: 1px solid #f2c7cd;
            box-shadow: 0 10px 32px rgba(224,46,64,.08);
            backdrop-filter: blur(16px);
        }
        .header-inner {
            position: relative;
            display: grid;
            grid-template-columns: auto minmax(300px, 560px) minmax(0, max-content);
            align-items: center;
            gap: 18px;
            width: 100%;
            margin: 0 auto;
            padding: 14px 32px;
            min-height: 74px;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            justify-self: start;
            font-size: 1.42rem;
            font-weight: 900;
            letter-spacing: 0;
            white-space: nowrap;
        }
        .brand-mark {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: var(--brand);
            color: var(--white);
            font-weight: 900;
        }
        .brand span:last-child { color: var(--brand); }
        .header-search {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            justify-self: center;
            width: 100%;
            min-width: 0;
            overflow: hidden;
            border: 1px solid #efc9ce;
            border-radius: 8px;
            background: #fff7f8;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.7);
        }
        .header-search input {
            min-width: 0;
            border: 0;
            outline: 0;
            color: var(--ink);
            background: transparent;
            padding: 14px 16px;
        }
        .header-search input::placeholder { color: #8b6c71; }
        .header-search button,
        .primary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            border-radius: 8px;
            background: var(--brand);
            color: var(--white);
            font-weight: 900;
            padding: 12px 18px;
            line-height: 1;
        }
        .header-search button {
            border-radius: 0;
            min-width: 102px;
        }
        .nav-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            justify-self: end;
            gap: 4px;
            min-width: 0;
            overflow: visible;
            margin-left: auto;
        }
        .nav-link, .icon-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            border: 1px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: var(--ink);
            font-size: .86rem;
            font-weight: 800;
            padding: 10px 9px;
            white-space: nowrap;
        }
        .nav-link:hover, .icon-btn:hover {
            border-color: #f3c2c8;
            background: #fff0f2;
            color: var(--brand);
        }
        .cart-count {
            display: inline-grid;
            place-items: center;
            min-width: 22px;
            height: 22px;
            border-radius: 999px;
            background: var(--brand);
            color: var(--white);
            font-size: .78rem;
            font-weight: 900;
            padding: 0 6px;
        }
        .menu-btn {
            display: none;
            justify-self: end;
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, .88fr) minmax(520px, 1.12fr);
            gap: 44px;
            width: 100%;
            margin: 0 auto;
            padding: 54px clamp(28px, 6vw, 92px) 26px;
        }
        .hero-copy {
            display: flex;
            min-height: 560px;
            flex-direction: column;
            justify-content: center;
        }
        .eyebrow {
            margin: 0 0 12px;
            color: var(--brand-strong);
            font-size: .82rem;
            font-weight: 900;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        h1, h2, h3, p { margin-top: 0; }
        .hero h1 {
            max-width: 780px;
            margin-bottom: 18px;
            font-size: 4.9rem;
            line-height: .98;
            letter-spacing: 0;
        }
        .hero p {
            max-width: 720px;
            color: var(--muted);
            font-size: 1.16rem;
            line-height: 1.75;
        }
        .hero-actions, .row-actions, .filter-actions, .form-row, .tabs {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }
        .ghost-btn, .danger-btn, .dark-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            border-radius: 8px;
            font-weight: 900;
            padding: 10px 14px;
        }
        .ghost-btn { border: 1px solid var(--line); background: var(--white); color: var(--ink); }
        .danger-btn { border: 0; background: var(--danger); color: var(--white); }
        .dark-btn { border: 0; background: var(--dark); color: var(--white); }
        .hero-media {
            position: relative;
            overflow: hidden;
            min-height: 560px;
            border-radius: 0;
            background: var(--dark);
            box-shadow: var(--shadow);
        }
        .hero-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .92;
        }
        .hero-stat {
            position: absolute;
            right: 24px;
            bottom: 24px;
            display: grid;
            grid-template-columns: repeat(3, minmax(120px, 1fr));
            gap: 1px;
            overflow: hidden;
            border-radius: 8px;
            background: rgba(255,255,255,.24);
            backdrop-filter: blur(18px);
        }
        .hero-stat div {
            background: rgba(42,42,42,.82);
            color: var(--white);
            padding: 18px;
        }
        .hero-stat strong { display: block; font-size: 1.5rem; color: var(--brand); }
        .hero-stat span { color: rgba(255,255,255,.75); font-size: .86rem; }

        .section {
            width: 100%;
            margin: 0 auto;
            padding: 46px clamp(28px, 6vw, 92px);
        }
        .section-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }
        .section-head h2 {
            margin: 0;
            font-size: 2.72rem;
            line-height: 1;
            letter-spacing: -.02em;
        }
        .section-subtitle {
            max-width: 620px;
            margin: 12px 0 0;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.65;
        }
        .item-count-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #f2c7cd;
            border-radius: 999px;
            background: #fff7f8;
            color: var(--brand);
            font-weight: 900;
            padding: 10px 14px;
            white-space: nowrap;
        }
        .category-strip {
            position: relative;
            overflow: hidden;
            padding: 2px 0 6px;
            mask-image: linear-gradient(90deg, transparent, #000 5%, #000 95%, transparent);
            -webkit-mask-image: linear-gradient(90deg, transparent, #000 5%, #000 95%, transparent);
        }
        .category-strip:hover .category-track,
        .category-strip:focus-within .category-track {
            animation-play-state: paused;
        }
        .category-track {
            display: flex;
            width: max-content;
            gap: 16px;
            animation: categorySlide 42s linear infinite;
        }
        .category-tile {
            flex: 0 0 clamp(220px, 14vw, 260px);
            min-height: 150px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            box-shadow: 0 10px 24px rgba(20, 31, 31, .06);
            text-align: left;
            padding: 22px;
        }
        .category-tile:hover,
        .category-tile:focus-visible {
            border-color: var(--brand);
            box-shadow: 0 16px 34px rgba(0, 212, 36, .12);
            transform: translateY(-2px);
        }
        .category-tile strong {
            display: block;
            margin-bottom: 8px;
            font-size: 1.12rem;
        }
        .category-tile p {
            margin: 0;
            font-size: .98rem;
            line-height: 1.35;
        }
        .category-tile.active {
            border-color: var(--brand-strong);
            background: linear-gradient(135deg, #ffffff, #e9fbfc);
        }
        @keyframes categorySlide {
            from { transform: translateX(0); }
            to { transform: translateX(calc(-50% - 8px)); }
        }
        .shop-grid {
            display: grid;
            grid-template-columns: 330px minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }
        .panel {
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            box-shadow: var(--shadow);
            padding: 22px;
        }
        .filters { position: sticky; top: 92px; }
        .filters h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 16px;
            font-size: 1.15rem;
        }
        .filter-card {
            display: grid;
            gap: 14px;
        }
        .filter-field {
            display: grid;
            gap: 8px;
            margin: 0;
        }
        .filter-field span {
            color: var(--ink);
            font-size: .9rem;
            font-weight: 900;
        }
        .filter-field small {
            color: var(--muted);
            font-weight: 700;
        }
        .range-wrap {
            display: grid;
            gap: 8px;
            border: 1px solid #f0d4d8;
            border-radius: 8px;
            background: #fff8f9;
            padding: 12px;
        }
        input[type="range"] {
            accent-color: var(--brand);
        }
        label { display: grid; gap: 8px; margin-bottom: 14px; color: var(--muted); font-weight: 800; }
        input, select, textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            color: var(--ink);
            outline: 0;
            padding: 12px 13px;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--brand-strong);
            box-shadow: 0 0 0 4px rgba(224,46,64,.16);
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }
        .product-card {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            box-shadow: 0 12px 34px rgba(20,31,31,.08);
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .product-card:hover {
            transform: translateY(-3px);
            border-color: #f1b8c0;
            box-shadow: 0 18px 42px rgba(20,31,31,.12);
        }
        .product-image {
            position: relative;
            display: block;
            width: 100%;
            aspect-ratio: 1 / .78;
            border: 0;
            background: #e9f4f4;
            padding: 0;
            overflow: hidden;
            cursor: zoom-in;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1);
            transform-origin: var(--focus-x, 50%) var(--focus-y, 50%);
            transition: transform .22s ease;
        }
        .product-image:hover img,
        .product-image:focus-visible img {
            transform: scale(1.16);
        }
        .product-image:focus-visible {
            outline: 3px solid var(--brand);
            outline-offset: -3px;
        }
        .image-view-hint {
            position: absolute;
            right: 12px;
            bottom: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(0,212,36,.48);
            border-radius: 999px;
            background: rgba(8,27,41,.82);
            color: var(--brand);
            font-size: .78rem;
            font-weight: 900;
            padding: 7px 10px;
            opacity: 0;
            transform: translateY(6px);
            transition: opacity .16s ease, transform .16s ease;
        }
        .product-image:hover .image-view-hint,
        .product-image:focus-visible .image-view-hint {
            opacity: 1;
            transform: translateY(0);
        }
        .product-image-fallback {
            display: grid;
            place-items: center;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #fff1f3, #f7fbfb);
            color: var(--brand);
            font-weight: 900;
            text-align: center;
            padding: 18px;
        }
        .badge {
            position: absolute;
            top: 12px;
            left: 12px;
            border-radius: 999px;
            background: var(--dark);
            color: var(--brand);
            font-size: .78rem;
            font-weight: 900;
            padding: 7px 10px;
        }
        .product-body { display: grid; gap: 12px; padding: 17px; }
        .product-body small { color: var(--brand-strong); font-weight: 900; }
        .product-body h3 { margin: 0; min-height: 46px; font-size: 1.16rem; line-height: 1.28; }
        .product-body p { min-height: 54px; margin: 0; color: var(--muted); line-height: 1.55; }
        .price-row { display: flex; align-items: baseline; gap: 10px; margin: 0; }
        .price-row strong { font-size: 1.28rem; }
        .price-row del, .muted { color: var(--muted); }
        .product-card .row-actions {
            display: grid;
            grid-template-columns: minmax(150px, auto) 1fr;
            align-items: center;
            gap: 12px;
        }
        .cart-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            border: 0;
            border-radius: 8px;
            background: var(--brand);
            color: var(--white);
            font-weight: 900;
            line-height: 1;
            padding: 11px 15px;
            white-space: nowrap;
        }
        .cart-btn svg,
        .cart-btn i {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
        }
        .stock-note {
            color: var(--muted);
            font-size: .9rem;
            font-weight: 700;
            text-align: right;
        }
        .contact-page {
            min-height: calc(100vh - 74px);
            background:
                radial-gradient(circle at top right, rgba(224,46,64,.08), transparent 34rem),
                linear-gradient(180deg, #ffffff 0%, #f7fbfb 100%);
        }
        .contact-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 24px;
            border: 1px solid #f2c7cd;
            border-radius: 8px;
            background:
                linear-gradient(135deg, rgba(224,46,64,.09), rgba(255,255,255,.96)),
                #ffffff;
            box-shadow: 0 18px 42px rgba(20,31,31,.08);
            padding: 34px;
        }
        .contact-hero h1 {
            margin: 0;
            font-size: 3.1rem;
            line-height: 1;
            letter-spacing: -.03em;
        }
        .contact-hero p:not(.eyebrow) {
            max-width: 760px;
            margin: 14px 0 0;
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.7;
        }
        .contact-layout {
            display: grid;
            grid-template-columns: minmax(380px, .82fr) minmax(0, 1.18fr);
            gap: 24px;
            margin-top: 24px;
            align-items: start;
        }
        .contact-form-card,
        .contact-info-card {
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            box-shadow: 0 18px 45px rgba(20,31,31,.08);
            padding: 24px;
        }
        .contact-form-card h2,
        .contact-info-card h2 {
            margin: 0 0 18px;
            font-size: 1.55rem;
        }
        .contact-methods {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }
        .contact-method {
            border: 1px solid #f1d8dc;
            border-radius: 8px;
            background: #fff8f9;
            padding: 16px;
        }
        .contact-method i,
        .contact-method svg {
            color: var(--brand);
            margin-bottom: 10px;
        }
        .contact-method strong,
        .contact-method span {
            display: block;
        }
        .contact-method span {
            color: var(--muted);
            margin-top: 4px;
            overflow-wrap: anywhere;
        }
        .contact-service-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        .service-card {
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 18px;
        }
        .service-card strong {
            display: block;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }
        .service-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.55;
        }
        .contact-note {
            margin-top: 18px;
            border-radius: 8px;
            background: var(--dark);
            color: var(--white);
            padding: 18px;
        }
        .contact-note strong {
            color: var(--brand);
        }

        .image-lightbox {
            position: fixed;
            inset: 0;
            z-index: 120;
            display: grid;
            grid-template-rows: auto minmax(0, 1fr) auto;
            gap: 18px;
            background: rgba(0,0,0,.82);
            color: var(--ink);
            padding: 22px;
        }
        .image-lightbox-head,
        .image-lightbox-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            width: min(100%, 1180px);
            margin: 0 auto;
        }
        .image-lightbox-title {
            min-width: 0;
        }
        .image-lightbox-title h2 {
            margin: 0;
            font-size: 1.28rem;
        }
        .image-lightbox-title p {
            margin: 4px 0 0;
            color: rgba(237,237,237,.72);
            font-size: .92rem;
        }
        .image-lightbox-stage {
            display: grid;
            place-items: center;
            overflow: auto;
            width: min(100%, 1180px);
            min-height: 0;
            margin: 0 auto;
            border: 1px solid rgba(237,237,237,.18);
            border-radius: 8px;
            background: rgba(8,27,41,.72);
        }
        .image-lightbox-stage img {
            display: block;
            max-width: 92%;
            max-height: 72vh;
            object-fit: contain;
            transform-origin: var(--focus-x, 50%) var(--focus-y, 50%);
            transition: transform .16s ease;
        }
        .zoom-controls {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(237,237,237,.16);
            border-radius: 8px;
            background: rgba(8,27,41,.9);
            padding: 8px 10px;
        }
        .zoom-controls input {
            width: 170px;
            padding: 0;
        }
        .zoom-value {
            min-width: 52px;
            color: var(--brand);
            font-size: .88rem;
            font-weight: 900;
            text-align: right;
        }

        .drawer-backdrop {
            position: fixed;
            inset: 0;
            z-index: 90;
            background: rgba(0,0,0,.45);
        }
        .drawer {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 100;
            width: min(100%, 520px);
            height: 100vh;
            overflow: auto;
            background: var(--panel);
            box-shadow: -20px 0 60px rgba(0,0,0,.24);
            padding: 24px;
        }
        .drawer-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid var(--line);
            margin: -4px 0 18px;
            padding-bottom: 16px;
        }
        .drawer-head .modal-close {
            min-width: 88px;
            min-height: 38px;
            border-color: var(--brand);
            background: transparent;
            color: var(--brand);
            opacity: 1;
            padding: 0 16px;
            gap: 0;
            text-align: center;
        }
        .drawer-head .modal-close i,
        .drawer-head .modal-close svg {
            display: none;
        }
        .drawer-head .modal-close span {
            width: 100%;
            justify-content: center;
        }
        .drawer-head .modal-close:hover {
            background: var(--brand);
            color: var(--dark);
        }
        .drawer-title h2 {
            margin: 0;
            font-size: 1.55rem;
        }
        .drawer-title p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: .92rem;
        }
        .drawer-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 40px;
            border: 2px solid var(--brand);
            border-radius: 8px;
            background: transparent;
            color: var(--brand);
            font-weight: 900;
            padding: 8px 12px;
        }
        .drawer-close:hover {
            background: var(--brand);
            color: var(--dark);
        }
        .cart-line {
            display: grid;
            grid-template-columns: 78px minmax(0, 1fr);
            gap: 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: rgba(237,237,237,.035);
            margin-bottom: 12px;
            padding: 12px;
        }
        .cart-line img,
        .cart-image-fallback {
            width: 78px;
            height: 78px;
            border-radius: 8px;
            object-fit: cover;
        }
        .cart-image-fallback {
            display: grid;
            place-items: center;
            background: rgba(0,212,36,.1);
            color: var(--brand);
            font-size: .72rem;
            font-weight: 900;
            text-align: center;
            padding: 8px;
        }
        .cart-item-main {
            display: grid;
            gap: 10px;
            min-width: 0;
        }
        .cart-item-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: start;
        }
        .cart-item-top strong {
            line-height: 1.25;
        }
        .cart-item-price {
            color: var(--brand);
            font-weight: 900;
            white-space: nowrap;
        }
        .cart-item-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .qty {
            display: inline-flex;
            align-items: center;
            gap: 0;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: rgba(237,237,237,.05);
        }
        .qty button {
            width: 36px;
            height: 34px;
            border: 0;
            background: transparent;
            color: var(--ink);
            font-weight: 900;
        }
        .qty button:hover {
            background: rgba(0,212,36,.12);
            color: var(--brand);
        }
        .qty span {
            min-width: 38px;
            text-align: center;
            font-weight: 900;
        }
        .remove-cart {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 34px;
            border: 1px solid rgba(223,79,100,.45);
            border-radius: 8px;
            background: rgba(223,79,100,.08);
            color: #ff7184;
            font-weight: 900;
            padding: 7px 10px;
        }
        .summary {
            display: grid;
            gap: 8px;
            margin: 18px 0 14px;
            border-radius: 8px;
            border: 1px solid rgba(0,212,36,.22);
            background: linear-gradient(135deg, rgba(0,212,36,.12), rgba(8,27,41,.7));
            padding: 16px;
        }
        .summary div { display: flex; justify-content: space-between; }
        .summary strong { font-size: 1.2rem; }
        .checkout-btn {
            width: 100%;
            min-height: 50px;
            font-size: 1rem;
        }

        .admin-shell {
            display: grid;
            grid-template-columns: 300px minmax(0, 1fr);
            gap: 0;
            width: 100%;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background:
                radial-gradient(circle at top right, rgba(224,46,64,.08), transparent 32rem),
                #f7fbfb;
        }
        .admin-nav {
            position: sticky;
            top: 0;
            align-self: start;
            display: grid;
            gap: 8px;
            min-height: 100vh;
            border-radius: 0;
            background: var(--dark);
            color: var(--white);
            padding: 26px 20px;
        }
        .admin-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            font-size: 1.22rem;
            font-weight: 900;
        }
        .admin-brand span:first-child {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: var(--brand);
            color: var(--white);
        }
        .tab-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px;
            background: rgba(255,255,255,.06);
            color: var(--white);
            text-align: left;
            font-weight: 900;
            min-height: 46px;
            padding: 10px 12px;
        }
        .tab-btn span {
            display: grid;
            gap: 2px;
        }
        .tab-btn small {
            color: rgba(255,255,255,.62);
            font-weight: 700;
            font-size: .75rem;
        }
        .tab-btn.active { background: var(--brand); color: var(--white); border-color: var(--brand); }
        .tab-btn.active small { color: rgba(255,255,255,.86); }
        .admin-side-actions {
            display: grid;
            gap: 10px;
            margin-top: auto;
        }
        .admin-workspace {
            min-width: 0;
            padding: 34px 38px 70px;
        }
        .admin-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            border: 1px solid #f1d3d7;
            border-radius: 8px;
            background: linear-gradient(135deg, #ffffff, #fff5f6);
            box-shadow: 0 18px 42px rgba(20,31,31,.08);
            padding: 24px;
        }
        .admin-hero h1 {
            margin: 0;
            font-size: 2.45rem;
            letter-spacing: -.03em;
        }
        .admin-hero p {
            max-width: 760px;
            margin: 8px 0 0;
            color: var(--muted);
            line-height: 1.65;
        }
        .admin-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
        .stat-card {
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            box-shadow: 0 12px 26px rgba(20,31,31,.06);
            padding: 18px;
        }
        .stat-card span {
            color: var(--muted);
            font-weight: 800;
        }
        .stat-card strong {
            display: block;
            margin-top: 8px;
            color: var(--brand);
            font-size: 2rem;
            line-height: 1;
        }
        .crud-grid { display: grid; grid-template-columns: minmax(320px, .72fr) minmax(0, 1.28fr); gap: 22px; align-items: start; }
        .table-list { display: grid; gap: 12px; }
        .table-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 14px;
            align-items: center;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            padding: 14px;
        }
        .icon-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 44px;
            min-height: 40px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            color: var(--ink);
            font-size: .82rem;
            font-weight: 900;
            padding: 0 12px;
            line-height: 1;
        }
        .primary-btn svg,
        .primary-btn i,
        .icon-action svg,
        .icon-action i {
            display: block;
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
            color: currentColor;
            stroke: currentColor;
            stroke-width: 2.4;
        }
        .primary-btn span,
        .icon-action span {
            display: inline-flex;
            align-items: center;
            line-height: 1;
        }
        .icon-action:hover {
            border-color: var(--brand);
            color: var(--brand);
            background: rgba(0,212,36,.08);
        }
        .icon-action.edit-action {
            border-color: var(--brand);
            color: var(--brand);
            background: rgba(0,212,36,.05);
        }
        .icon-action.edit-action:hover {
            background: var(--brand);
            color: var(--dark);
            box-shadow: 0 0 16px rgba(0,212,36,.28);
        }
        .icon-action.delete { color: var(--danger); }
        .icon-action.delete:hover {
            border-color: var(--danger);
            background: rgba(224,46,64,.12);
            color: var(--danger);
        }
        .row-actions .icon-action {
            min-width: 92px;
            text-align: center;
        }
        .order-grid {
            display: grid;
            gap: 18px;
        }
        .order-filter-bar {
            display: grid;
            grid-template-columns: minmax(260px, 1fr) auto;
            gap: 12px;
            align-items: end;
        }
        .order-filter-bar input { min-height: 48px; }
        .order-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 16px;
            border: 1px solid rgba(0,212,36,.42);
            border-radius: 8px;
            background: rgba(0,212,36,.08);
            color: var(--brand);
            font-weight: 900;
            white-space: nowrap;
        }
        .order-edit-form {
            display: grid;
            gap: 12px;
            border-bottom: 1px solid var(--line);
            margin-bottom: 14px;
            padding-bottom: 14px;
        }
        .order-edit-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .order-card {
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            box-shadow: 0 12px 30px rgba(20,31,31,.07);
            padding: 18px;
        }
        .order-top {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(240px, 320px);
            gap: 18px;
            align-items: start;
            border-bottom: 1px solid var(--line);
            padding-bottom: 14px;
            margin-bottom: 14px;
        }
        .order-top h3 {
            margin: 8px 0 14px;
            font-size: 1.2rem;
        }
        .order-meta-line {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 14px;
            color: var(--muted);
            font-weight: 800;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 104px;
            border-radius: 999px;
            font-weight: 900;
            padding: 7px 12px;
            text-transform: capitalize;
        }
        .status-submitted { background: #fff2b8; color: #7a5700; }
        .status-ready { background: #d9ebff; color: #0758a8; }
        .status-complete { background: #d9f8df; color: #116b2a; }
        .order-items {
            display: grid;
            gap: 8px;
        }
        .order-item {
            display: grid;
            grid-template-columns: 58px minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 8px 10px;
        }
        .order-item img,
        .image-fallback {
            width: 58px;
            height: 58px;
            border-radius: 8px;
            object-fit: cover;
            background: #fff1f3;
        }
        .image-fallback {
            display: grid;
            place-items: center;
            color: var(--brand);
            font-weight: 900;
        }
        .order-item strong { font-size: .94rem; }
        .order-item p { margin: 3px 0 0; }
        .order-item-total {
            display: grid;
            gap: 4px;
            justify-items: end;
            min-width: 130px;
        }
        .order-item-total small {
            color: var(--muted);
            font-weight: 800;
        }
        .order-edit-items {
            display: grid;
            gap: 10px;
        }
        .order-edit-item {
            display: grid;
            grid-template-columns: minmax(160px, 1.2fr) repeat(3, minmax(96px, .55fr));
            gap: 10px;
            align-items: end;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 10px;
        }
        .order-total-row {
            display: grid;
            gap: 8px;
            text-align: right;
        }
        .status-select {
            width: 100%;
            max-width: none;
            margin-left: auto;
        }
        .order-total-row .row-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 6px;
        }
        .order-total-row .row-actions .icon-action {
            min-width: 0;
            width: 100%;
        }

        .login-card {
            width: min(100%, 460px);
            margin: 72px auto;
        }
        .confirm-card {
            width: min(calc(100% - 32px), 560px);
            margin: 15vh auto;
            display: grid;
            gap: 22px;
            border: 1px solid rgba(0,212,36,.34);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 22px 70px rgba(0,0,0,.44);
        }
        .confirm-eyebrow {
            margin: 0 0 8px;
            color: var(--danger);
            font-size: .78rem;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .confirm-card h2 {
            margin: 0;
            font-size: 1.75rem;
            line-height: 1.15;
        }
        .confirm-card p {
            margin: 0;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.65;
        }
        .confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .confirm-actions button {
            min-width: 112px;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 18px;
            background: #eafffb;
            color: #11737a;
            font-weight: 900;
            padding: 13px 15px;
        }
        .alert.error { background: #fff0f3; color: #b5324a; }
        .footer {
            margin-top: 40px;
            background: var(--dark);
            color: var(--white);
        }
        .footer-inner {
            display: grid;
            grid-template-columns: 1.4fr .8fr .8fr .8fr;
            gap: 28px;
            width: min(100%, 1640px);
            margin: 0 auto;
            padding: 46px 28px;
        }
        .footer p, .footer a { display: block; color: rgba(255,255,255,.72); }

        /* Dark green brand theme */
        body,
        .contact-page,
        .admin-shell {
            background:
                radial-gradient(circle at top right, rgba(0,212,36,.12), transparent 34rem),
                var(--dark);
            color: var(--ink);
        }
        .app-header {
            background: rgba(8,27,41,.96);
            border-bottom-color: rgba(0,212,36,.28);
            box-shadow: 0 10px 32px rgba(0,0,0,.28);
        }
        .header-search {
            border-color: rgba(0,212,36,.5);
            background: rgba(237,237,237,.04);
            box-shadow: inset 0 0 0 1px rgba(237,237,237,.04), 0 0 18px rgba(0,212,36,.05);
        }
        .header-search input,
        input,
        select,
        textarea {
            color: var(--ink);
        }
        .header-search input::placeholder,
        input::placeholder,
        textarea::placeholder {
            color: rgba(237,237,237,.56);
        }
        input,
        select,
        textarea {
            background: rgba(237,237,237,.035);
            border-color: rgba(237,237,237,.18);
        }
        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(0,212,36,.16);
        }
        .primary-btn,
        .header-search button,
        .cart-btn,
        .ghost-btn,
        .dark-btn {
            border: 2px solid var(--brand);
            background: transparent;
            color: var(--brand);
            box-shadow: none;
        }
        .primary-btn:hover,
        .header-search button:hover,
        .cart-btn:hover,
        .ghost-btn:hover,
        .dark-btn:hover {
            background: var(--brand);
            color: var(--dark);
            box-shadow: 0 0 22px rgba(0,212,36,.34);
        }
        .brand-mark,
        .cart-count {
            background: var(--brand);
            color: var(--dark);
        }
        .nav-link,
        .icon-btn {
            color: var(--ink);
        }
        .nav-link:hover,
        .icon-btn:hover {
            border-color: rgba(0,212,36,.42);
            background: rgba(0,212,36,.08);
            color: var(--brand);
        }
        .panel,
        .filters,
        .product-card,
        .category-tile,
        .contact-form-card,
        .contact-info-card,
        .contact-method,
        .service-card,
        .stat-card,
        .table-row,
        .order-card,
        .admin-hero,
        .login-card,
        .drawer {
            background: var(--panel);
            border-color: var(--line);
            color: var(--ink);
            box-shadow: var(--shadow);
        }
        .category-tile.active,
        .contact-hero,
        .summary,
        .range-wrap,
        .product-image-fallback {
            background: linear-gradient(135deg, rgba(0,212,36,.14), rgba(8,27,41,.95));
            border-color: rgba(0,212,36,.42);
            color: var(--ink);
        }
        .hero-media,
        .contact-note,
        .footer,
        .admin-nav {
            background: #061722;
        }
        .badge {
            background: #061722;
            color: var(--brand);
        }
        .item-count-pill {
            background: rgba(0,212,36,.08);
            border-color: rgba(0,212,36,.42);
            color: var(--brand);
        }
        .tab-btn.active {
            background: var(--brand);
            color: var(--dark);
            border-color: var(--brand);
        }
        .tab-btn.active small {
            color: rgba(8,27,41,.78);
        }
        .alert {
            background: rgba(0,212,36,.12);
            color: var(--brand);
        }
        .footer p,
        .footer a,
        .muted,
        .hero p,
        .section-subtitle,
        .product-body p,
        .stock-note,
        .contact-method span,
        .service-card p,
        .admin-hero p {
            color: var(--muted);
        }
        select {
            color-scheme: dark;
        }
        select option {
            background: var(--dark);
            color: var(--ink);
        }
        select option:checked,
        select option:hover {
            background: var(--brand);
            color: var(--dark);
        }
        .row-actions .icon-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 92px;
            color: var(--ink);
        }
        .row-actions .icon-action.edit-action {
            border-color: var(--brand);
            color: var(--brand);
        }
        .row-actions .icon-action.edit-action:hover {
            background: var(--brand);
            border-color: var(--brand);
            color: #000000;
        }
        .row-actions .icon-action.delete {
            border-color: rgba(224,46,64,.65);
            color: var(--danger);
        }
        .footer h2,
        .footer h3,
        h1,
        h2,
        h3,
        strong,
        label,
        .filter-field span {
            color: var(--ink);
        }

        @media (max-width: 1500px) {
            .header-inner {
                display: grid;
                grid-template-columns: 220px 1fr auto;
            }
            .menu-btn { display: inline-flex; }
            .header-search {
                position: static;
                transform: none;
                width: 100%;
            }
            .nav-right {
                display: none;
                grid-column: 1 / -1;
                justify-content: flex-start;
                flex-wrap: wrap;
                border-top: 1px solid #f5ccd1;
                padding-top: 12px;
            }
            body.menu-open .nav-right { display: flex; }
            .hero, .shop-grid, .admin-shell, .crud-grid { grid-template-columns: 1fr; }
            .hero h1 { font-size: 3.7rem; }
            .product-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .filters, .admin-nav { position: static; min-height: auto; }
            .admin-shell { grid-template-columns: 1fr; }
            .admin-nav { border-radius: 0; }
            .admin-workspace { padding: 24px 18px 54px; }
            .admin-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 820px) {
            .app-header {
                border-bottom-color: rgba(0,212,36,.32);
            }
            .header-inner {
                display: grid;
                grid-template-columns: minmax(0, 1fr) 52px;
                gap: 14px;
                padding: 14px 16px;
            }
            .brand {
                min-width: 0;
                gap: 9px;
                font-size: 1.08rem;
            }
            .brand-mark {
                width: 40px;
                height: 40px;
                font-size: 1.05rem;
            }
            .menu-btn {
                width: 52px;
                min-width: 52px;
                height: 52px;
                padding: 0;
                border: 2px solid var(--brand);
                border-radius: 8px;
                justify-content: center;
                background: rgba(0,212,36,.06);
                color: var(--brand);
            }
            .menu-btn svg,
            .menu-btn i {
                width: 28px;
                height: 28px;
            }
            .header-search {
                grid-column: 1 / -1;
                order: 2;
                grid-template-columns: minmax(0, 1fr) 108px;
                min-height: 56px;
                border: 2px solid var(--brand);
                border-radius: 8px;
                overflow: hidden;
            }
            .header-search input {
                min-height: 52px;
                padding: 0 16px;
                font-size: .98rem;
            }
            .header-search button {
                min-width: 108px;
                border: 0;
                border-left: 2px solid var(--brand);
                border-radius: 0;
                background: rgba(0,212,36,.1);
            }
            .nav-right {
                grid-column: 1 / -1;
                order: 3;
                display: none;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
                width: 100%;
                margin: 2px 0 0;
                padding: 14px 0 0;
                border-top: 1px solid rgba(237,237,237,.18);
            }
            body.menu-open .nav-right {
                display: grid;
            }
            .nav-right .nav-link,
            .nav-right .icon-btn {
                width: 100%;
                min-height: 48px;
                justify-content: flex-start;
                border: 1px solid rgba(237,237,237,.12);
                background: rgba(237,237,237,.035);
                border-radius: 8px;
                padding: 12px;
                font-size: .92rem;
                white-space: normal;
            }
            .nav-right .icon-btn svg,
            .nav-right .icon-btn i {
                width: 20px;
                height: 20px;
                flex: 0 0 20px;
            }
            .cart-count {
                margin-left: auto;
            }
            .category-strip {
                mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent);
                -webkit-mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent);
            }
            .category-track {
                gap: 12px;
                animation-duration: 34s;
            }
            .category-tile {
                flex-basis: 230px;
                min-height: 138px;
            }
            .image-lightbox {
                padding: 14px;
            }
            .image-lightbox-head,
            .image-lightbox-tools {
                align-items: stretch;
                flex-direction: column;
            }
            .image-lightbox-stage img {
                max-width: 96%;
                max-height: 62vh;
            }
            .zoom-controls {
                width: 100%;
                justify-content: space-between;
            }
            .zoom-controls input {
                width: min(42vw, 180px);
            }
            .hero { padding: 30px 16px; }
            .hero-copy, .hero-media { min-height: auto; }
            .hero-media { min-height: 360px; }
            .hero h1 { font-size: 2.55rem; }
            .hero-stat { left: 14px; right: 14px; grid-template-columns: 1fr; }
            .section, .admin-shell { padding-left: 16px; padding-right: 16px; }
            .section-head { align-items: flex-start; flex-direction: column; }
            .product-grid, .footer-inner, .form-row { grid-template-columns: 1fr; }
            .cart-line { grid-template-columns: 58px 1fr; }
            .cart-line > strong { grid-column: 1 / -1; }
            .order-top,
            .order-item,
            .order-edit-grid,
            .order-edit-item,
            .order-filter-bar {
                grid-template-columns: 1fr;
            }
            .order-total-row {
                text-align: left;
            }
            .order-item-total {
                justify-items: start;
            }
            .status-select {
                margin-left: 0;
            }
            .admin-hero,
            .admin-stats,
            .contact-hero,
            .contact-layout,
            .contact-methods,
            .contact-service-grid {
                grid-template-columns: 1fr;
            }
            .contact-hero h1 {
                font-size: 2.35rem;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .category-track {
                animation: none;
                overflow-x: auto;
                width: auto;
                padding-bottom: 8px;
            }
            .product-image img,
            .image-lightbox-stage img,
            .category-tile {
                transition: none;
            }
        }
    </style>
</head>
<body>
    <div id="root"></div>

    <script type="text/babel">
        @verbatim
        const { useEffect, useMemo, useState } = React;
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const basePath = window.location.pathname.includes('/public')
            ? window.location.pathname.split('/public')[0] + '/public'
            : '';
        const money = value => 'Rs. ' + Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const refreshIcons = () => {
            if (!window.lucide) return;
            window.requestAnimationFrame(() => window.lucide.createIcons());
        };
        const api = (url, options = {}) => fetch(url, {
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            credentials: 'same-origin',
            ...options,
            body: options.body ? JSON.stringify(options.body) : undefined,
        }).then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(data.message || 'Request failed');
            return data;
        });
        const endpoint = path => basePath + path;
        const assetUrl = url => {
            if (!url) return '';
            if (/^(https?:)?\/\//.test(url) || url.startsWith('data:')) return url;
            return basePath + (url.startsWith('/') ? url : '/' + url);
        };

        function App() {
            const [state, setState] = useState({ auth: {}, banner: null, categories: [], products: [], orders: [] });
            const [filters, setFilters] = useState({ search: '', category: '', min_price: '', max_price: '', min_discount: 0 });
            const [cart, setCart] = useState(() => JSON.parse(localStorage.getItem('myb_cart') || '[]'));
            const [cartOpen, setCartOpen] = useState(false);
            const [selectedProduct, setSelectedProduct] = useState(null);
            const [adminOpen, setAdminOpen] = useState(false);
            const [screen, setScreen] = useState('home');
            const [notice, setNotice] = useState('');

            const load = () => {
                const params = new URLSearchParams(Object.entries(filters).filter(([,v]) => String(v) !== ''));
                return api(endpoint('/api/state?' + params.toString())).then(setState);
            };
            useEffect(() => { load(); }, [filters.search, filters.category, filters.min_price, filters.max_price, filters.min_discount]);
            useEffect(() => { localStorage.setItem('myb_cart', JSON.stringify(cart)); }, [cart]);
            useEffect(refreshIcons);

            const user = state.auth?.user;
            const isAdmin = user?.role === 'super_admin';
            const cartCount = cart.length;
            const addToCart = product => {
                setCart(items => {
                    const exists = items.find(item => item.id === product.id);
                    if (exists) return items.map(item => item.id === product.id ? { ...item, quantity: item.quantity + 1 } : item);
                    return [...items, { ...product, quantity: 1 }];
                });
                setCartOpen(true);
            };

            return (
                <>
                    {!(adminOpen && isAdmin) && <Header filters={filters} setFilters={setFilters} load={load} cartCount={cartCount} setCartOpen={setCartOpen} user={user} isAdmin={isAdmin} setAdminOpen={setAdminOpen} setState={setState} setScreen={setScreen} />}
                    {notice && <div className="alert" style={{width:'min(100%,1640px)', margin:'18px auto 0'}}>{notice}</div>}
                    {adminOpen && isAdmin ? (
                        <Admin state={state} reload={load} setNotice={setNotice} />
                    ) : screen === 'contact' ? (
                        <ContactScreen setScreen={setScreen} />
                    ) : screen === 'about' ? (
                        <AboutScreen setScreen={setScreen} />
                    ) : (
                        <Store state={state} filters={filters} setFilters={setFilters} load={load} addToCart={addToCart} setSelectedProduct={setSelectedProduct} />
                    )}
                    {!(adminOpen && isAdmin) && <Footer />}
                    {cartOpen && <CartDrawer cart={cart} setCart={setCart} setCartOpen={setCartOpen} setNotice={setNotice} reload={load} />}
                    {selectedProduct && <ProductImageViewer product={selectedProduct} setSelectedProduct={setSelectedProduct} />}
                </>
            );
        }

        function Header({ filters, setFilters, load, cartCount, setCartOpen, user, isAdmin, setAdminOpen, setState, setScreen }) {
            const [term, setTerm] = useState(filters.search);
            const [loginOpen, setLoginOpen] = useState(false);
            const logout = () => api(endpoint('/api/logout'), { method: 'POST' }).then(() => setState(s => ({...s, auth:{user:null}})));
            const closeMenu = () => document.body.classList.remove('menu-open');
            const openAdmin = () => {
                closeMenu();
                load().finally(() => setAdminOpen(true));
            };
            return (
                <header className="app-header">
                    <div className="header-inner">
                        <a className="brand" href="#" onClick={() => setAdminOpen(false)}>
                            <span className="brand-mark">CT</span><span>Central</span><span>Trading</span>
                        </a>
                        <form className="header-search" onSubmit={e => { e.preventDefault(); setFilters({...filters, search: term}); document.getElementById('items')?.scrollIntoView(); }}>
                            <input value={term} onChange={e => setTerm(e.target.value)} placeholder="Search items, brands, categories" />
                            <button>Search</button>
                        </form>
                        <button className="icon-btn menu-btn" onClick={() => document.body.classList.toggle('menu-open')}><i data-lucide="menu"></i></button>
                        <nav className="nav-right">
                            <a className="nav-link" href="#" onClick={() => { closeMenu(); setScreen('home'); setAdminOpen(false); }}>Home</a>
                            <a className="nav-link" href="#categories" onClick={() => { closeMenu(); setScreen('home'); }}>Categories</a>
                            <button className="nav-link" type="button" onClick={() => { closeMenu(); setScreen('about'); setAdminOpen(false); window.scrollTo({top:0, behavior:'smooth'}); }}>About Us</button>
                            <button className="nav-link" type="button" onClick={() => { closeMenu(); setScreen('contact'); setAdminOpen(false); window.scrollTo({top:0, behavior:'smooth'}); }}>Contact Us</button>
                            <a className="nav-link" href="#items" onClick={() => { closeMenu(); setScreen('home'); }}>View All Items</a>
                            <button className="icon-btn" onClick={() => { closeMenu(); setCartOpen(true); }}><i data-lucide="shopping-cart"></i> Cart <span className="cart-count">{cartCount}</span></button>
                            {isAdmin && <button className="icon-btn" onClick={openAdmin}><i data-lucide="settings"></i> Super Admin</button>}
                            {user ? <button className="icon-btn" onClick={() => { closeMenu(); logout(); }}><i data-lucide="log-out"></i> Logout</button> : <button className="icon-btn" onClick={() => { closeMenu(); setLoginOpen(true); }}><i data-lucide="log-in"></i> Login</button>}
                        </nav>
                    </div>
                    {loginOpen && <LoginModal setLoginOpen={setLoginOpen} setState={setState} />}
                </header>
            );
        }

        function LoginModal({ setLoginOpen, setState }) {
            const [form, setForm] = useState({ email: 'admin@my-bussiness.local', password: 'admin123' });
            const [error, setError] = useState('');
            const submit = e => {
                e.preventDefault();
                api(endpoint('/api/login'), { method:'POST', body: form }).then(data => { setState(s => ({...s, auth:{user:data.user}})); setLoginOpen(false); }).catch(err => setError(err.message));
            };
            return (
                <div className="drawer-backdrop">
                    <form className="panel login-card" onSubmit={submit}>
                        <div className="drawer-head"><h2>Super admin login</h2><button type="button" className="icon-action modal-close" onClick={() => setLoginOpen(false)}><i data-lucide="x"></i><span>Close</span></button></div>
                        {error && <div className="alert error">{error}</div>}
                        <label>Email<input value={form.email} onChange={e => setForm({...form, email:e.target.value})} /></label>
                        <label>Password<input type="password" value={form.password} onChange={e => setForm({...form, password:e.target.value})} /></label>
                        <button className="primary-btn">Login</button>
                    </form>
                </div>
            );
        }

        function ConfirmModal({ confirm, setConfirm }) {
            if (!confirm) return null;
            const close = () => setConfirm(null);
            const confirmLabel = confirm.confirmLabel || 'Delete';
            const confirmIcon = confirm.confirmIcon || 'trash-2';
            const confirmClass = confirm.confirmClass || 'danger-btn';
            return (
                <div className="drawer-backdrop">
                    <div className="panel confirm-card">
                        <div>
                            <p className="confirm-eyebrow">{confirm.eyebrow || 'Confirm delete'}</p>
                            <h2>{confirm.title}</h2>
                            <p>{confirm.message}</p>
                        </div>
                        <div className="confirm-actions">
                            <button type="button" className="ghost-btn" onClick={close}>Cancel</button>
                            <button type="button" className={confirmClass} onClick={() => { confirm.onConfirm(); close(); }}><i data-lucide={confirmIcon}></i> {confirmLabel}</button>
                        </div>
                    </div>
                </div>
            );
        }

        function Store({ state, filters, setFilters, load, addToCart, setSelectedProduct }) {
            const banner = state.banner || {};
            const categoryCards = [
                { id: 'all', name: 'All Items', description: 'Complete catalog', slug: '' },
                ...state.categories
            ];
            const renderCategoryCard = (category, copy) => (
                <button
                    key={`${copy}-${category.id}`}
                    className={'category-tile ' + (filters.category === category.slug ? 'active':'')}
                    onClick={() => setFilters({...filters, category: category.slug})}
                    tabIndex={copy === 'loop' ? -1 : 0}
                >
                    <strong>{category.name}</strong>
                    <p className="muted">{category.description}</p>
                </button>
            );
            return (
                <>
                    <section className="hero">
                        <div className="hero-copy">
                            <p className="eyebrow">{banner.eyebrow}</p>
                            <h1>{banner.title}</h1>
                            <p>{banner.subtitle}</p>
                            <div className="hero-actions">
                                <a className="primary-btn" href="#items">{banner.cta_text || 'Shop all items'}</a>
                                <a className="ghost-btn" href="#categories"><i data-lucide="layers"></i> Browse categories</a>
                            </div>
                        </div>
                        <div className="hero-media">
                            <img src={assetUrl(banner.image_url)} alt="Central Trading banner" />
                            <div className="hero-stat">
                                <div><strong>{state.categories.length}</strong><span>Categories</span></div>
                                <div><strong>{state.products.length}</strong><span>Visible items</span></div>
                                <div><strong>24h</strong><span>Order handling</span></div>
                            </div>
                        </div>
                    </section>
                    <section className="section" id="categories">
                        <div className="section-head"><div><p className="eyebrow">Product departments</p><h2>Categories</h2></div></div>
                        <div className="category-strip">
                            <div className="category-track">
                                {categoryCards.map(category => renderCategoryCard(category, 'main'))}
                                {categoryCards.map(category => renderCategoryCard(category, 'loop'))}
                            </div>
                        </div>
                    </section>
                    <section className="section" id="items">
                        <div className="section-head">
                            <div>
                                <p className="eyebrow">Shop inventory</p>
                                <h2>Explore the item catalog</h2>
                                <p className="section-subtitle">Filter by category, price, and discount to quickly find the right products for your next order.</p>
                            </div>
                            <span className="item-count-pill"><i data-lucide="package-check"></i>{state.products.length} items found</span>
                        </div>
                        <div className="shop-grid">
                            <aside className="panel filters">
                                <h3><i data-lucide="sliders-horizontal"></i> Refine items</h3>
                                <div className="filter-card">
                                    <label className="filter-field"><span>Category</span><select value={filters.category} onChange={e => setFilters({...filters, category:e.target.value})}><option value="">All categories</option>{state.categories.map(c => <option key={c.id} value={c.slug}>{c.name}</option>)}</select></label>
                                    <label className="filter-field"><span>Minimum price</span><input type="number" placeholder="Rs. 0" value={filters.min_price} onChange={e => setFilters({...filters, min_price:e.target.value})} /></label>
                                    <label className="filter-field"><span>Maximum price</span><input type="number" placeholder="Any price" value={filters.max_price} onChange={e => setFilters({...filters, max_price:e.target.value})} /></label>
                                    <label className="filter-field range-wrap"><span>Minimum discount</span><input type="range" min="0" max="60" value={filters.min_discount} onChange={e => setFilters({...filters, min_discount:e.target.value})} /><small>{filters.min_discount}% or more</small></label>
                                    <button className="ghost-btn" onClick={() => setFilters({search:'', category:'', min_price:'', max_price:'', min_discount:0})}><i data-lucide="rotate-ccw"></i> Reset filters</button>
                                </div>
                            </aside>
                            <div className="product-grid">
                                {state.products.map(product => <ProductCard key={product.id} product={product} addToCart={addToCart} setSelectedProduct={setSelectedProduct} />)}
                            </div>
                        </div>
                    </section>
                </>
            );
        }

        function AboutScreen({ setScreen }) {
            return (
                <section className="section contact-page">
                    <div className="contact-hero">
                        <div>
                            <p className="eyebrow">About us</p>
                            <h1>Built for practical local commerce.</h1>
                            <p>Central Trading brings products, categories, customer checkout, and super-admin operations into one clean ecommerce platform.</p>
                        </div>
                        <button className="ghost-btn" onClick={() => setScreen('home')}><i data-lucide="arrow-left"></i> Back to Home</button>
                    </div>
                    <div className="contact-layout">
                        <div className="contact-info-card">
                            <p className="eyebrow">What we do</p>
                            <h2>Simple shopping, stronger operations.</h2>
                            <p className="muted">Customers can browse products, filter by price or discount, add items to cart, and place orders. Super admin can manage catalog content, banners, and order status from a dedicated dashboard.</p>
                            <div className="contact-service-grid" style={{marginTop:18}}>
                                <div className="service-card"><strong>Organized catalog</strong><p>Products are grouped by category so customers can find the right items quickly.</p></div>
                                <div className="service-card"><strong>Order workflow</strong><p>Orders move through submitted, ready, and complete statuses for better control.</p></div>
                                <div className="service-card"><strong>Admin control</strong><p>Super admin can create, edit, delete, and operate store content from one workspace.</p></div>
                                <div className="service-card"><strong>Responsive design</strong><p>The storefront is designed for desktop, tablet, and mobile screens.</p></div>
                            </div>
                        </div>
                        <aside className="contact-form-card">
                            <p className="eyebrow">Business values</p>
                            <h2>Reliable. Clear. Fast.</h2>
                            <div className="summary">
                                <div><span>Focus</span><strong>Daily essentials</strong></div>
                                <div><span>Support</span><strong>Order handling</strong></div>
                                <div><span>Catalog</span><strong>Multi-category</strong></div>
                            </div>
                            <p className="muted">This platform is built around clear pricing, visible discounts, item availability, and easy customer checkout.</p>
                        </aside>
                    </div>
                </section>
            );
        }

        function ContactScreen({ setScreen }) {
            return (
                <section className="section contact-page">
                    <div className="contact-hero">
                        <div>
                            <p className="eyebrow">Contact us</p>
                            <h1>Talk to Central Trading</h1>
                            <p>Need help with an order, delivery, product availability, or supplier request? Send the details here and we will help you move it forward.</p>
                        </div>
                        <button className="ghost-btn" onClick={() => setScreen('home')}><i data-lucide="arrow-left"></i> Back to Home</button>
                    </div>
                    <div className="contact-layout">
                        <form className="contact-form-card">
                            <h2>Send a message</h2>
                            <label>Name<input placeholder="Your name" /></label>
                            <label>Phone<input placeholder="+94..." /></label>
                            <label>Email<input placeholder="you@example.com" /></label>
                            <label>Message<textarea rows="6" placeholder="How can we help?"></textarea></label>
                            <button type="button" className="primary-btn"><i data-lucide="send"></i> Send Message</button>
                        </form>
                        <aside className="contact-info-card">
                            <p className="eyebrow">Store details</p>
                            <h2>Central Trading</h2>
                            <div className="contact-methods">
                                <div className="contact-method"><i data-lucide="phone"></i><strong>Hotline</strong><span>+94 77 000 0000</span></div>
                                <div className="contact-method"><i data-lucide="mail"></i><strong>Email</strong><span>hello@my-bussiness.local</span></div>
                                <div className="contact-method"><i data-lucide="map-pin"></i><strong>Address</strong><span>Add your business address here.</span></div>
                            </div>
                            <div className="contact-service-grid">
                                <div className="service-card"><strong>Order support</strong><p>Ask about submitted orders, quantities, delivery timing, or product changes.</p></div>
                                <div className="service-card"><strong>Supplier inquiries</strong><p>Share brand, pricing, product, and wholesale details for review.</p></div>
                                <div className="service-card"><strong>Product requests</strong><p>Request new categories or items to add into the catalog.</p></div>
                                <div className="service-card"><strong>Response time</strong><p>Most customer and supplier messages are reviewed within 24 hours.</p></div>
                            </div>
                            <div className="contact-note"><strong>Tip:</strong> Include item names, quantities, and delivery area for faster support.</div>
                        </aside>
                    </div>
                </section>
            );
        }

        function ProductCard({ product, addToCart, setSelectedProduct }) {
            const focusImage = event => {
                const rect = event.currentTarget.getBoundingClientRect();
                const x = ((event.clientX - rect.left) / rect.width) * 100;
                const y = ((event.clientY - rect.top) / rect.height) * 100;
                event.currentTarget.style.setProperty('--focus-x', `${x}%`);
                event.currentTarget.style.setProperty('--focus-y', `${y}%`);
            };
            const centerImageFocus = event => {
                event.currentTarget.style.setProperty('--focus-x', '50%');
                event.currentTarget.style.setProperty('--focus-y', '50%');
            };
            return (
                <article className="product-card">
                    <button className="product-image" type="button" onMouseMove={focusImage} onFocus={centerImageFocus} onClick={() => setSelectedProduct(product)} aria-label={`View larger image of ${product.name}`}>
                        {product.image_url ? <img src={assetUrl(product.image_url)} alt={product.name} /> : <div className="product-image-fallback">{product.name}</div>}
                        {Number(product.discount_percent) > 0 && <span className="badge">{Number(product.discount_percent)}% OFF</span>}
                        <span className="image-view-hint"><i data-lucide="zoom-in"></i> View</span>
                    </button>
                    <div className="product-body">
                        <small>{product.category_name}</small>
                        <h3>{product.name}</h3>
                        <p>{product.description}</p>
                        <div className="price-row"><strong>{money(product.selling_price)}</strong>{Number(product.discount_percent) > 0 && <del>{money(product.price)}</del>}</div>
                        <div className="row-actions"><button className="cart-btn" onClick={() => addToCart(product)}><i data-lucide="shopping-cart"></i><span>Add to Cart</span></button><span className="stock-note">{product.stock} stock</span></div>
                    </div>
                </article>
            );
        }

        function ProductImageViewer({ product, setSelectedProduct }) {
            const [zoom, setZoom] = useState(100);
            const [focus, setFocus] = useState({ x: 50, y: 50 });
            useEffect(() => {
                const closeOnEscape = event => {
                    if (event.key === 'Escape') setSelectedProduct(null);
                };
                window.addEventListener('keydown', closeOnEscape);
                return () => window.removeEventListener('keydown', closeOnEscape);
            }, []);
            const focusViewerImage = event => {
                const rect = event.currentTarget.getBoundingClientRect();
                setFocus({
                    x: ((event.clientX - rect.left) / rect.width) * 100,
                    y: ((event.clientY - rect.top) / rect.height) * 100,
                });
            };
            const setBoundedZoom = value => setZoom(Math.max(100, Math.min(220, Number(value))));
            return (
                <div className="image-lightbox" role="dialog" aria-modal="true" aria-label={`${product.name} image viewer`}>
                    <div className="image-lightbox-head">
                        <div className="image-lightbox-title">
                            <h2>{product.name}</h2>
                            <p>{product.category_name} - {money(product.selling_price)}</p>
                        </div>
                        <button className="drawer-close" type="button" onClick={() => setSelectedProduct(null)}><i data-lucide="x"></i> Close</button>
                    </div>
                    <div className="image-lightbox-stage" onClick={() => setSelectedProduct(null)}>
                        {product.image_url ? <img src={assetUrl(product.image_url)} alt={product.name} onMouseMove={focusViewerImage} style={{transform:`scale(${zoom / 100})`, '--focus-x': `${focus.x}%`, '--focus-y': `${focus.y}%`}} onClick={event => event.stopPropagation()} /> : <div className="product-image-fallback">{product.name}</div>}
                    </div>
                    <div className="image-lightbox-tools">
                        <div className="zoom-controls">
                            <button className="drawer-close" type="button" onClick={() => setBoundedZoom(zoom - 20)}><i data-lucide="zoom-out"></i></button>
                            <input type="range" min="100" max="220" step="10" value={zoom} onChange={event => setBoundedZoom(event.target.value)} aria-label="Image zoom" />
                            <button className="drawer-close" type="button" onClick={() => setBoundedZoom(zoom + 20)}><i data-lucide="zoom-in"></i></button>
                            <span className="zoom-value">{zoom}%</span>
                        </div>
                        <button className="ghost-btn" type="button" onClick={() => setZoom(100)}><i data-lucide="rotate-ccw"></i> Reset</button>
                    </div>
                </div>
            );
        }

        function CartDrawer({ cart, setCart, setCartOpen, setNotice, reload }) {
            const [checkout, setCheckout] = useState(false);
            const [customer, setCustomer] = useState({ name:'', phone:'', email:'', address:'' });
            const subtotal = cart.reduce((sum, item) => sum + Number(item.price) * item.quantity, 0);
            const total = cart.reduce((sum, item) => sum + Number(item.selling_price) * item.quantity, 0);
            const updateQty = (id, delta) => setCart(items => items.map(item => item.id === id ? {...item, quantity: Math.max(1, item.quantity + delta)} : item));
            const remove = id => setCart(items => items.filter(item => item.id !== id));
            const submit = e => {
                e.preventDefault();
                api(endpoint('/api/checkout'), { method:'POST', body:{ customer, items: cart.map(({id, quantity}) => ({id, quantity})) } })
                    .then(data => { setCart([]); setCartOpen(false); setNotice(`Order #${data.order_id} placed successfully.`); reload(); })
                    .catch(err => setNotice(err.message));
            };
            return (
                <>
                    <div className="drawer-backdrop" onClick={() => setCartOpen(false)}></div>
                    <aside className="drawer">
                        <div className="drawer-head">
                            <div className="drawer-title">
                                <h2>Your cart</h2>
                                <p>{cart.length} item{cart.length === 1 ? '' : 's'} selected</p>
                            </div>
                            <button className="drawer-close" onClick={() => setCartOpen(false)}><i data-lucide="x"></i> Close</button>
                        </div>
                        {cart.length === 0 && <p className="muted">No items added yet.</p>}
                        {cart.map(item => <div className="cart-line" key={item.id}>
                            {item.image_url ? <img src={assetUrl(item.image_url)} /> : <div className="cart-image-fallback">No image</div>}
                            <div className="cart-item-main">
                                <div className="cart-item-top">
                                    <div>
                                        <strong>{item.name}</strong>
                                        <p className="muted" style={{margin:'4px 0 0'}}>{money(item.selling_price)} each</p>
                                    </div>
                                    <span className="cart-item-price">{money(item.selling_price * item.quantity)}</span>
                                </div>
                                <div className="cart-item-controls">
                                    <div className="qty"><button onClick={() => updateQty(item.id, -1)}>-</button><span>{item.quantity}</span><button onClick={() => updateQty(item.id, 1)}>+</button></div>
                                    <button className="remove-cart" onClick={() => remove(item.id)}><i data-lucide="trash-2"></i> Remove</button>
                                </div>
                            </div>
                        </div>)}
                        {cart.length > 0 && <><div className="summary"><div><span>Subtotal</span><span>{money(subtotal)}</span></div><div><span>Discount</span><span>{money(subtotal - total)}</span></div><div><strong>Total</strong><strong>{money(total)}</strong></div></div><button className="primary-btn checkout-btn" onClick={() => setCheckout(true)}><i data-lucide="credit-card"></i> Checkout</button></>}
                        {checkout && <form onSubmit={submit} style={{marginTop:18}}><h3>Checkout details</h3><label>Name<input required value={customer.name} onChange={e => setCustomer({...customer, name:e.target.value})} /></label><label>Phone<input required value={customer.phone} onChange={e => setCustomer({...customer, phone:e.target.value})} /></label><label>Email<input value={customer.email} onChange={e => setCustomer({...customer, email:e.target.value})} /></label><label>Delivery address<textarea required rows="3" value={customer.address} onChange={e => setCustomer({...customer, address:e.target.value})}></textarea></label><button className="dark-btn">Place Order</button></form>}
                    </aside>
                </>
            );
        }

        function Admin({ state, reload, setNotice }) {
            const [tab, setTab] = useState('products');
            const [confirm, setConfirm] = useState(null);
            const logout = () => api(endpoint('/api/logout'), { method: 'POST' }).then(() => window.location.reload());
            useEffect(refreshIcons, [tab, state.products.length, state.categories.length, state.orders.length]);
            useEffect(() => {
                if (tab !== 'orders') return;
                reload();
                const timer = window.setInterval(reload, 5000);
                return () => window.clearInterval(timer);
            }, [tab]);
            const pageMeta = {
                products: {
                    title: 'Product Management',
                    text: 'Create, edit, delete, and feature inventory items with prices, discounts, stock levels, and product media.'
                },
                categories: {
                    title: 'Category Management',
                    text: 'Organize your store departments and keep category names and descriptions clean for customers.'
                },
                banner: {
                    title: 'Homepage Banner',
                    text: 'Control the first impression customers see on the storefront hero section.'
                },
                orders: {
                    title: 'Order Operations',
                    text: 'Review submitted orders, inspect item quantities and images, update delivery status, edit customer details, or delete records.'
                }
            };
            const pending = state.orders.filter(order => order.status === 'submitted').length;
            const ready = state.orders.filter(order => order.status === 'ready').length;
            const complete = state.orders.filter(order => order.status === 'complete').length;
            return (
                <section className="admin-shell">
                    <aside className="admin-nav">
                        <div className="admin-brand"><span>CT</span><strong>Central Trading</strong></div>
                        {[
                            ['products', 'Products', 'Manage item catalog', 'package'],
                            ['categories', 'Categories', 'Store departments', 'layers'],
                            ['banner', 'Banner', 'Homepage hero', 'image'],
                            ['orders', 'Orders', 'Customer operations', 'receipt-text']
                        ].map(([key, label, help, icon]) => <button key={key} className={'tab-btn ' + (tab === key ? 'active':'')} onClick={() => setTab(key)}><i data-lucide={icon}></i><span>{label}<small>{help}</small></span></button>)}
                        <div className="admin-side-actions">
                            <button className="tab-btn" onClick={() => window.location.reload()}><i data-lucide="store"></i><span>View Store<small>Return to website</small></span></button>
                            <button className="tab-btn" onClick={logout}><i data-lucide="log-out"></i><span>Logout<small>End admin session</small></span></button>
                        </div>
                    </aside>
                    <div className="admin-workspace">
                        <div className="admin-hero">
                            <div>
                                <p className="eyebrow">Super admin</p>
                                <h1>{pageMeta[tab].title}</h1>
                                <p>{pageMeta[tab].text}</p>
                            </div>
                            <button className="ghost-btn" onClick={reload}><i data-lucide="refresh-cw"></i> Refresh</button>
                        </div>
                        <div className="admin-stats">
                            <div className="stat-card"><span>Products</span><strong>{state.products.length}</strong></div>
                            <div className="stat-card"><span>Categories</span><strong>{state.categories.length}</strong></div>
                            <div className="stat-card"><span>Submitted</span><strong>{pending}</strong></div>
                            <div className="stat-card"><span>Ready / Complete</span><strong>{ready + complete}</strong></div>
                        </div>
                        {tab === 'products' && <ProductCrud state={state} reload={reload} setNotice={setNotice} setConfirm={setConfirm} />}
                        {tab === 'categories' && <CategoryCrud state={state} reload={reload} setNotice={setNotice} setConfirm={setConfirm} />}
                        {tab === 'banner' && <BannerForm banner={state.banner} reload={reload} setNotice={setNotice} />}
                        {tab === 'orders' && <Orders orders={state.orders} reload={reload} setNotice={setNotice} setConfirm={setConfirm} />}
                    </div>
                    <ConfirmModal confirm={confirm} setConfirm={setConfirm} />
                </section>
            );
        }

        function ProductCrud({ state, reload, setNotice, setConfirm }) {
            const blank = { category_id: state.categories[0]?.id || '', name:'', sku:'', description:'', price:'', discount_percent:0, stock:0, image_url:'', is_featured:false };
            const [form, setForm] = useState(blank);
            const save = e => { e.preventDefault(); const editing = !!form.id; api(endpoint(editing ? `/api/products/${form.id}` : '/api/products'), { method: editing ? 'PUT':'POST', body: form }).then(d => { setNotice(d.message); setForm(blank); reload(); }).catch(err => setNotice(err.message)); };
            const del = product => setConfirm({ title: 'Delete product?', message: `This will remove "${product.name}" from the catalog.`, onConfirm: () => api(endpoint(`/api/products/${product.id}`), { method:'DELETE' }).then(d => { setNotice(d.message); reload(); }) });
            return <div className="crud-grid"><form className="panel" onSubmit={save}><label>Category<select value={form.category_id} onChange={e => setForm({...form, category_id:e.target.value})}>{state.categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}</select></label><label>Name<input value={form.name} onChange={e => setForm({...form, name:e.target.value})} required /></label><label>SKU<input value={form.sku || ''} onChange={e => setForm({...form, sku:e.target.value})} /></label><label>Description<textarea rows="4" value={form.description || ''} onChange={e => setForm({...form, description:e.target.value})}></textarea></label><div className="form-row"><label>Price<input type="number" value={form.price} onChange={e => setForm({...form, price:e.target.value})} required /></label><label>Discount %<input type="number" value={form.discount_percent} onChange={e => setForm({...form, discount_percent:e.target.value})} /></label><label>Stock<input type="number" value={form.stock} onChange={e => setForm({...form, stock:e.target.value})} /></label></div><label>Image URL<input value={form.image_url || ''} onChange={e => setForm({...form, image_url:e.target.value})} /></label><label style={{display:'flex', flexDirection:'row'}}><input style={{width:'auto'}} type="checkbox" checked={!!form.is_featured} onChange={e => setForm({...form, is_featured:e.target.checked})} /> Featured</label><button className="primary-btn" type="submit"><i data-lucide="save"></i><span>{form.id ? 'Update item':'Create item'}</span></button></form><div className="table-list">{state.products.map(p => <div className="table-row" key={p.id}><div><strong>{p.name}</strong><p className="muted">{p.category_name} · {money(p.selling_price)}</p></div><div className="row-actions"><button className="icon-action edit-action" title="Edit product" onClick={() => setForm({...p, is_featured: !!p.is_featured})}><i data-lucide="pencil"></i><span>Edit</span></button><button className="icon-action delete" title="Delete product" onClick={() => del(p)}><i data-lucide="trash-2"></i><span>Delete</span></button></div></div>)}</div></div>;
        }

        function CategoryCrud({ state, reload, setNotice, setConfirm }) {
            const [form, setForm] = useState({name:'', description:''});
            const save = e => { e.preventDefault(); const editing = !!form.id; api(endpoint(editing ? `/api/categories/${form.id}` : '/api/categories'), { method: editing ? 'PUT':'POST', body: form }).then(d => { setNotice(d.message); setForm({name:'', description:''}); reload(); }).catch(err => setNotice(err.message)); };
            const del = category => setConfirm({ title: 'Delete category?', message: `This will remove "${category.name}" and its products.`, onConfirm: () => api(endpoint(`/api/categories/${category.id}`), { method:'DELETE' }).then(d => { setNotice(d.message); reload(); }) });
            return <div className="crud-grid"><form className="panel" onSubmit={save}><label>Category name<input value={form.name} onChange={e => setForm({...form, name:e.target.value})} required /></label><label>Description<textarea rows="4" value={form.description || ''} onChange={e => setForm({...form, description:e.target.value})}></textarea></label><button className="primary-btn"><i data-lucide="save"></i><span>{form.id ? 'Update category':'Create category'}</span></button></form><div className="table-list">{state.categories.map(c => <div className="table-row" key={c.id}><div><strong>{c.name}</strong><p className="muted">{c.description}</p></div><div className="row-actions"><button className="icon-action edit-action" title="Edit category" onClick={() => setForm(c)}><i data-lucide="pencil"></i><span>Edit</span></button><button className="icon-action delete" title="Delete category" onClick={() => del(c)}><i data-lucide="trash-2"></i><span>Delete</span></button></div></div>)}</div></div>;
        }

        function BannerForm({ banner, reload, setNotice }) {
            const [form, setForm] = useState(banner || {});
            const save = e => { e.preventDefault(); api(endpoint('/api/banner'), { method:'POST', body: form }).then(d => { setNotice(d.message); reload(); }); };
            return <form className="panel" onSubmit={save}><label>Eyebrow<input value={form.eyebrow || ''} onChange={e => setForm({...form, eyebrow:e.target.value})} /></label><label>Title<input value={form.title || ''} onChange={e => setForm({...form, title:e.target.value})} /></label><label>Subtitle<textarea rows="4" value={form.subtitle || ''} onChange={e => setForm({...form, subtitle:e.target.value})}></textarea></label><label>Banner image URL<input value={form.image_url || ''} onChange={e => setForm({...form, image_url:e.target.value})} /></label><label>CTA text<input value={form.cta_text || ''} onChange={e => setForm({...form, cta_text:e.target.value})} /></label><button className="primary-btn"><i data-lucide="save"></i>Update home banner</button></form>;
        }

        function Orders({ orders, reload, setNotice, setConfirm }) {
            const [editing, setEditing] = useState(null);
            const [query, setQuery] = useState('');
            const discountOf = item => {
                const unit = Number(item.unit_price || 0);
                const selling = Number(item.selling_price || 0);
                return unit > 0 ? Math.max(0, Math.min(100, ((unit - selling) / unit) * 100)).toFixed(2) : '0.00';
            };
            const startEdit = order => setEditing({
                ...order,
                original_status: order.status,
                items: (order.items || []).map(item => ({
                    ...item,
                    quantity: Number(item.quantity || 1),
                    unit_price: Number(item.unit_price || 0),
                    discount_percent: discountOf(item),
                }))
            });
            const setEditItem = (id, patch) => setEditing(current => ({
                ...current,
                items: current.items.map(item => item.id === id ? { ...item, ...patch } : item)
            }));
            const visibleOrders = orders.filter(order => {
                const text = `${order.id} ${order.customer_name || ''} ${order.customer_phone || ''} ${order.customer_email || ''}`.toLowerCase();
                return text.includes(query.trim().toLowerCase());
            });
            const applyStatusUpdate = (order, status) => {
                api(endpoint(`/api/orders/${order.id}/status`), { method:'PUT', body:{ status } })
                    .then(d => { setNotice(d.message); reload(); })
                    .catch(err => setNotice(err.message));
            };
            const updateStatus = (order, status) => {
                if (status === 'complete' && order.status !== 'complete') {
                    setConfirm({
                        eyebrow: 'Confirm status change',
                        title: 'Complete this order?',
                        message: `Are you sure you want to change order #${order.id} to complete? Product stock will be deducted from the ordered quantities.`,
                        confirmLabel: 'Yes',
                        confirmIcon: 'check',
                        confirmClass: 'primary-btn',
                        onConfirm: () => applyStatusUpdate(order, status)
                    });
                    return;
                }

                applyStatusUpdate(order, status);
            };
            const saveOrder = e => {
                e.preventDefault();
                const payload = {
                    ...editing,
                    items: editing.items.map(item => ({
                        id: item.id,
                        quantity: Number(item.quantity || 1),
                        unit_price: Number(item.unit_price || 0),
                        discount_percent: Number(item.discount_percent || 0),
                    }))
                };
                const submitOrderUpdate = () => api(endpoint(`/api/orders/${editing.id}`), { method:'PUT', body: payload })
                    .then(d => { setNotice(d.message); setEditing(null); reload(); })
                    .catch(err => setNotice(err.message));

                if (payload.status === 'complete' && editing.original_status !== 'complete') {
                    setConfirm({
                        eyebrow: 'Confirm status change',
                        title: 'Complete this order?',
                        message: `Are you sure you want to change order #${editing.id} to complete? Product stock will be deducted from the ordered quantities.`,
                        confirmLabel: 'Yes',
                        confirmIcon: 'check',
                        confirmClass: 'primary-btn',
                        onConfirm: submitOrderUpdate
                    });
                    return;
                }

                submitOrderUpdate();
            };
            const deleteOrder = order => setConfirm({
                title: `Delete order #${order.id}?`,
                message: 'This will permanently remove the order and all item details from the admin records.',
                onConfirm: () => api(endpoint(`/api/orders/${order.id}`), { method:'DELETE' })
                    .then(d => { setNotice(d.message); reload(); })
                    .catch(err => setNotice(err.message))
            });
            return (
                <div className="order-grid">
                    <div className="panel order-filter-bar">
                        <label style={{margin:0}}>Search orders<input value={query} onChange={e => setQuery(e.target.value)} placeholder="Search by order ID, customer name, or mobile number" /></label>
                        <span className="order-count">{visibleOrders.length} order{visibleOrders.length === 1 ? '' : 's'}</span>
                    </div>
                    {visibleOrders.length === 0 && <div className="panel">No matching orders.</div>}
                    {visibleOrders.map(order => (
                        <article className="order-card" key={order.id}>
                            {editing?.id === order.id && (
                                <form className="order-edit-form" onSubmit={saveOrder}>
                                    <div className="order-edit-grid">
                                        <label>Customer name<input value={editing.customer_name || ''} onChange={e => setEditing({...editing, customer_name:e.target.value})} required /></label>
                                        <label>Phone<input value={editing.customer_phone || ''} onChange={e => setEditing({...editing, customer_phone:e.target.value})} required /></label>
                                        <label>Email<input value={editing.customer_email || ''} onChange={e => setEditing({...editing, customer_email:e.target.value})} /></label>
                                        <label>Status<select value={editing.status} onChange={e => setEditing({...editing, status:e.target.value})}><option value="submitted">Submitted</option><option value="ready">Ready</option><option value="complete">Complete</option></select></label>
                                    </div>
                                    <label>Delivery address<textarea rows="2" value={editing.delivery_address || ''} onChange={e => setEditing({...editing, delivery_address:e.target.value})} required></textarea></label>
                                    <div className="order-edit-items">
                                        {(editing.items || []).map(item => (
                                            <div className="order-edit-item" key={item.id}>
                                                <div><strong>{item.product_name}</strong><p className="muted">SKU: {item.sku || 'N/A'}</p></div>
                                                <label>Qty<input type="number" min="1" value={item.quantity} onChange={e => setEditItem(item.id, {quantity: e.target.value})} /></label>
                                                <label>Unit price<input type="number" min="0" step="0.01" value={item.unit_price} onChange={e => setEditItem(item.id, {unit_price: e.target.value})} /></label>
                                                <label>Discount %<input type="number" min="0" max="100" step="0.01" value={item.discount_percent} onChange={e => setEditItem(item.id, {discount_percent: e.target.value})} /></label>
                                            </div>
                                        ))}
                                    </div>
                                    <div className="row-actions">
                                        <button className="primary-btn"><i data-lucide="save"></i> Save Order</button>
                                        <button type="button" className="ghost-btn" onClick={() => setEditing(null)}>Cancel</button>
                                    </div>
                                </form>
                            )}
                            <div className="order-top">
                                <div>
                                    <p className="eyebrow">Order #{order.id}</p>
                                    <h3>{order.customer_name}</h3>
                                    <div className="order-meta-line"><span>{order.customer_phone}</span><span>{order.customer_email || 'No email'}</span><span>{(order.items || []).length} item{(order.items || []).length === 1 ? '' : 's'}</span></div>
                                    <p className="muted">{order.delivery_address}</p>
                                </div>
                                <div className="order-total-row">
                                    <span className={'status-pill status-' + order.status}>{order.status}</span>
                                    <strong>{money(order.grand_total)}</strong>
                                    <select className="status-select" value={order.status} onChange={e => updateStatus(order, e.target.value)}>
                                        <option value="submitted">Submitted</option>
                                        <option value="ready">Ready</option>
                                        <option value="complete">Complete</option>
                                    </select>
                                    <div className="row-actions">
                                        <button className="icon-action edit-action" title="Edit order" onClick={() => startEdit(order)}><i data-lucide="pencil"></i><span>Edit</span></button>
                                        <button className="icon-action delete" title="Delete order" onClick={() => deleteOrder(order)}><i data-lucide="trash-2"></i><span>Delete</span></button>
                                    </div>
                                </div>
                            </div>
                            <div className="order-items">
                                {(order.items || []).map(item => (
                                    <div className="order-item" key={item.id}>
                                        {item.image_url ? <img src={assetUrl(item.image_url)} alt={item.product_name} /> : <div className="image-fallback">No image</div>}
                                        <div>
                                            <strong>{item.product_name}</strong>
                                            <p className="muted">SKU: {item.sku || 'N/A'} · Qty: {item.quantity} · Discount: {discountOf(item)}%</p>
                                            <p className="muted">Selling: {money(item.selling_price)} · Original: {money(item.unit_price)}</p>
                                        </div>
                                        <div className="order-item-total"><strong>{money(item.line_total)}</strong><small>{money(item.selling_price)} each</small></div>
                                    </div>
                                ))}
                            </div>
                        </article>
                    ))}
                </div>
            );
        }

        function Footer() {
            return <footer className="footer" id="contact"><div className="footer-inner"><div><h2>Central Trading</h2><p>Industrial level ecommerce for local retail, wholesale, and daily essentials.</p></div><div><h3>Contact</h3><p>+94 77 000 0000</p><p>hello@my-bussiness.local</p></div><div><h3>Store</h3><a href="#items">All Items</a><a href="#categories">Categories</a></div><div><h3>Brand</h3><p>Dark navy and #00d424 business color system.</p></div></div></footer>;
        }

        ReactDOM.createRoot(document.getElementById('root')).render(<App />);
        @endverbatim
    </script>
</body>
</html>
