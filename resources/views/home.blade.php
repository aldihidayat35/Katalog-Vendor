<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f0efee">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ setting('site_tagline', 'Katalog Undangan Cetak & Digital Eksklusif - Temukan berbagai pilihan undangan pernikahan & acara berkualitas tinggi.') }}">
    <title>{{ setting('site_name', 'Undangin') }} — {{ setting('site_tagline', 'Katalog Undangan Cetak & Digital Eksklusif') }}</title>
    <link rel="icon" href="{{ site_favicon_url() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bronze: #935006;
            --bronze-hover: #ad641c;
            --ink: #37271f;
            --muted: #706a66;
            --surface: #ffffff;
            --soft: #f3f2f0;
            --line: #eeece9;
        }
        [x-cloak] { display: none !important; }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: radial-gradient(ellipse at 50% -6%, #e8e6e4 0%, #f3f2f1 65%);
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--ink);
            font-size: 14px;
            overflow-x: hidden;
        }
        .serif { font-family: 'Playfair Display', Georgia, serif; }
        button, input { font: inherit; }
        button { cursor: pointer; border: 0; background: transparent; padding: 0; }
        svg { display: inline-block; flex-shrink: 0; }
        button:focus-visible, a:focus-visible, input:focus-visible { outline: 3px solid #bb7b3e; outline-offset: 2px; }

        /* Navigation */
        .nav-shell {
            max-width: 1224px;
            width: calc(100% - 56px);
            margin: auto;
            height: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 28px;
        }
        .logo { display: flex; gap: 8px; align-items: center; text-decoration: none; color: #211c19; }
        .logo-mark { width: 24px; height: 30px; color: #8e4d06; }
        .logo-img { height: 30px; width: auto; max-width: 44px; object-fit: contain; border-radius: 6px; }
        .logo-word { font-size: 22px; font-family: 'Playfair Display', Georgia, serif; font-weight: 700; letter-spacing: -.04em; }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a { text-decoration: none; color: #3b3531; font-size: 12px; font-weight: 600; white-space: nowrap; transition: color .2s; }
        .nav-links a:hover, .nav-links a.active { color: #8b4902; }
        .nav-links a.active:after { content: ''; height: 2px; background: #9d5913; display: block; margin-top: 4px; border-radius: 1px; }

        .nav-tools { display: flex; align-items: center; gap: 10px; }
        .nav-icon-btn {
            height: 34px;
            width: 34px;
            background: #f7f7f7;
            border: 1px solid #e9e8e6;
            border-radius: 50%;
            color: #514c47;
            display: grid;
            place-items: center;
            transition: all .2s;
            position: relative;
            text-decoration: none;
        }
        .nav-icon-btn:hover { background: #efece8; color: #8e4d06; }
        .nav-icon-btn svg { width: 17px; height: 17px; }
        .badge-count {
            position: absolute;
            top: -3px;
            right: -3px;
            background: #975205;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: grid;
            place-items: center;
        }

        .nav-btn {
            height: 36px;
            min-width: 80px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            background: #e8e7e7;
            color: #39332e;
            padding: 0 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .2s;
        }
        .nav-btn.primary {
            background: #975205;
            color: white;
            min-width: 90px;
            box-shadow: 0 4px 10px #5b330a20;
        }
        .nav-btn:hover { filter: brightness(.95); }

        .mobile-menu-button {
            display: none;
            border: 1px solid #e6e3e0;
            border-radius: 9px;
            background: white;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
        }
        .mobile-nav { display: none; }

        /* Hero Surface & Banner Slider */
        .hero-surface {
            width: calc(100% - 48px);
            max-width: 1400px;
            min-height: 440px;
            margin: 0 auto;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 9px 27px rgba(40,27,14,.07);
            background-color: #fbf8f3;
        }
        .hero-slides {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }
        .hero-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transform: scale(1.02);
            transition: opacity 0.8s ease-in-out, transform 1.2s ease-out;
            pointer-events: none;
        }
        .hero-slide.active {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }
        .hero-surface:before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, rgba(251,248,243,0.3) 0%, rgba(244,237,226,0.65) 100%);
            z-index: 1;
            pointer-events: none;
        }
        .hero-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            padding: 32px 20px 24px;
            z-index: 2;
        }
        .hero-pill {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 7px 15px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 30px;
            border: 1px solid #e6ddcf;
            background: #ffffffd4;
            color: #68503b;
            backdrop-filter: blur(4px);
        }
        .hero-pill span { color: #a35e13; font-size: 14px; }

        /* Dynamic Text Slider */
        .hero-text-slider {
            position: relative;
            width: 100%;
            max-width: 860px;
            min-height: 135px;
            display: grid;
            place-items: center;
            margin-top: 10px;
        }
        .hero-text-slide {
            grid-area: 1 / 1;
            width: 100%;
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: opacity 0.3s ease-out, transform 0.3s ease-out, visibility 0.3s step-end;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .hero-text-slide.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            transition: opacity 0.4s ease-in 0.1s, transform 0.4s ease-in 0.1s, visibility 0s linear;
            pointer-events: auto;
        }
        .hero-heading {
            font-family: 'Playfair Display', Georgia, serif;
            color: #43281C;
            font-size: 42px;
            line-height: 1.15;
            letter-spacing: -.02em;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 1px 2px rgba(255,255,255,0.7);
        }
        .hero-heading em {
            font-style: italic;
            font-weight: 500;
            display: block;
            font-size: 38px;
            letter-spacing: -.02em;
            color: #794a2b;
        }
        .hero-subtitle {
            font-size: 14.5px;
            line-height: 1.55;
            color: #5a524e;
            max-width: 680px;
            margin: 10px auto 0;
            font-weight: 400;
            text-shadow: 0 1px 1px rgba(255,255,255,0.6);
        }
        .hero-cta-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            font-size: 12px;
            font-weight: 600;
            color: #8e4d06;
            background: #ffffffd4;
            border: 1px solid #dfd5c8;
            padding: 6px 16px;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(142,77,6,0.08);
            backdrop-filter: blur(4px);
        }
        .hero-cta-link:hover {
            background: #975205;
            color: #ffffff;
            border-color: #975205;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(142,77,6,0.2);
        }

        /* Dots Navigation */
        .hero-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 18px;
        }
        .hero-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #cbbba9;
            border: 0;
            cursor: pointer;
            padding: 0;
            transition: all 0.3s ease;
        }
        .hero-dot.active {
            width: 26px;
            border-radius: 5px;
            background: #975205;
        }
        .hero-dot:hover:not(.active) {
            background: #9d8e7e;
        }

        .hero-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fffffffa;
            border-radius: 27px;
            border: 1px solid #e9e6e2;
            box-shadow: 0 5px 22px #3c2f2415;
            height: 48px;
            width: min(628px, 100%);
            margin-top: 18px;
            padding: 5px 6px 5px 15px;
        }
        .hero-search svg { width: 21px; height: 21px; color: #55504c; }
        .hero-search input {
            border: 0;
            outline: 0;
            min-width: 0;
            background: transparent;
            flex: 1;
            padding: 6px 4px;
            color: #312c29;
            font-size: 13px;
        }
        .hero-search input::placeholder { color: #aaa3a0; }
        .hero-search button {
            height: 38px;
            border-radius: 23px;
            background: #955106;
            color: white;
            font-weight: 700;
            font-size: 12px;
            min-width: 120px;
            transition: background .2s;
        }
        .hero-search button:hover { background: #a66013; }

        .chip-bar {
            display: flex;
            justify-content: center;
            gap: 7px;
            flex-wrap: wrap;
            max-width: 820px;
            margin-top: 14px;
        }
        .chip {
            min-height: 30px;
            padding: 0 16px;
            white-space: nowrap;
            background: #f1efedee;
            border: 1px solid #e3dfdc;
            border-radius: 99px;
            font-size: 11px;
            color: #48403b;
            font-weight: 500;
            transition: all .2s;
        }
        .chip.selected {
            background: #955106;
            border-color: #955106;
            color: #fff;
            font-weight: 700;
        }
        .chip:hover:not(.selected) { background: #fff; border-color: #d1c8c0; }

        /* Panels & Wraps */
        .content-wrap {
            width: calc(100% - 64px);
            max-width: 1368px;
            margin: 0 auto;
        }
        .panel {
            background: #fff;
            border: 1px solid rgba(255,255,255,.9);
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(59,47,37,.03);
        }

        /* Featured Section */
        .featured {
            display: grid;
            grid-template-columns: 290px minmax(0, 1fr);
            gap: 20px;
            min-height: 180px;
            margin-top: 12px;
            padding: 12px 20px 14px 26px;
            align-items: center;
            overflow: hidden;
        }
        .featured-copy { padding: 8px 0; }
        .featured-heading { display: flex; align-items: center; gap: 10px; }
        .featured-heading svg { width: 22px; height: 22px; color: #955106; flex-shrink: 0; }
        .featured h2 { font: 600 22px/1.25 'Playfair Display', Georgia, serif; letter-spacing: -.02em; margin: 0; color: #35261d; }
        .featured p { margin: 6px 0 0; color: #77706d; font-size: 13px; line-height: 1.45; max-width: 250px; }
        .carousel-nav { display: flex; gap: 9px; margin-top: 14px; }
        .round-arrow {
            height: 36px;
            width: 36px;
            border: 1px solid #ece9e5;
            background: #f4f3f2;
            color: #39312b;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 20px;
            transition: all .2s;
            cursor: pointer;
        }
        .round-arrow:hover { background: #e7ddd0; color: #8e4d06; }

        .feature-gallery-container {
            min-width: 0;
            overflow: hidden;
            position: relative;
        }
        .feature-gallery {
            display: flex;
            flex-wrap: nowrap;
            gap: 14px;
            overflow-x: auto;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 6px 2px 10px;
            cursor: grab;
            user-select: none;
        }
        .feature-gallery:active {
            cursor: grabbing;
        }
        .feature-gallery::-webkit-scrollbar {
            display: none;
        }
        .feature-card {
            flex: 0 0 162px;
            min-width: 162px;
            max-width: 162px;
            border: 0;
            text-align: left;
            background: transparent;
            padding: 0;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            scroll-snap-align: start;
        }
        .feature-card:hover { transform: translateY(-3px); }
        .feature-img-wrap {
            position: relative;
            width: 100%;
            height: 140px;
            border-radius: 8px;
            overflow: hidden;
            background: #eee3d6;
            border: 1px solid #eeece9;
        }
        .feature-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            pointer-events: none;
        }
        .feature-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            font-size: 8.5px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: .02em;
            pointer-events: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }
        .feature-badge.printed {
            background: rgba(38, 92, 40, 0.92);
            color: #ffffff;
        }
        .feature-badge.digital {
            background: rgba(24, 73, 126, 0.92);
            color: #ffffff;
        }
        .feature-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            margin: 5px 2px 0;
            color: #37312d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .stars {
            display: block;
            font-size: 11px;
            line-height: 1.2;
            color: #aa640b;
            margin: 1px 2px 0;
        }
        .rating { color: #6e6762; font-size: 10px; margin-left: 4px; }

        /* Middle Grid */
        .middle-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 12px;
        }
        .category-panel, .why-panel {
            min-height: 180px;
            padding: 16px 20px 18px;
        }
        .panel-title {
            font: 600 22px/1.3 'Playfair Display', Georgia, serif;
            letter-spacing: -.02em;
            margin: 0;
            color: #37281f;
        }

        .category-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px 12px;
            margin-top: 12px;
        }
        .cat-card {
            height: 50px;
            border-radius: 9px;
            border: 1px solid #ebe7e4;
            background: #f7f6f4;
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
            padding: 0 14px;
            font-size: 11px;
            color: #2d2b29;
            transition: all .2s;
        }
        .cat-card svg:first-child { width: 22px; height: 22px; color: #a55c0b; }
        .cat-card span { font-weight: 600; flex: 1; }
        .cat-card .chevron { width: 13px; height: 13px; color: #8e8884; }
        .cat-card:hover, .cat-card.is-active {
            background: #efe2d3;
            border-color: #deb992;
            color: #854504;
        }

        .benefits {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0;
            margin-top: 12px;
        }
        .benefit {
            padding: 2px 10px;
            text-align: center;
            position: relative;
        }
        .benefit + .benefit:before {
            content: '';
            position: absolute;
            left: 0;
            top: 20px;
            height: 90px;
            width: 1px;
            background: #eeedeb;
        }
        .benefit-icon {
            display: grid;
            place-items: center;
            background: #f5f2ee;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin: 0 auto 6px;
        }
        .benefit-icon svg { height: 23px; width: 23px; color: #a25c12; }
        .benefit h3 { font: 600 13px/1.2 'Playfair Display', Georgia, serif; margin: 0; color: #352922; }
        .benefit p { font-size: 10px; color: #7a7470; line-height: 1.3; margin: 4px auto 0; max-width: 125px; }

        /* Catalog Section */
        .catalog-panel {
            margin-top: 12px;
            padding: 16px 20px 20px;
            min-height: 220px;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
        }
        .catalog-panel h2 {
            font: 600 24px/1.2 'Playfair Display', Georgia, serif;
            letter-spacing: -.02em;
            margin: 0;
            color: #37281f;
        }
        .view-all {
            background: none;
            border: 0;
            display: flex;
            gap: 8px;
            align-items: center;
            color: #8e4a04;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }
        .view-all svg {
            width: 22px;
            height: 22px;
            padding: 5px;
            border-radius: 50%;
            background: #f6f5f3;
            color: #5f4b39;
        }
        .view-all:hover { text-decoration: underline; }

        .catalog-list {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }
        .catalog-card {
            display: flex;
            height: 156px;
            min-width: 0;
            border: 1px solid #efedeb;
            border-radius: 12px;
            padding: 4px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 3px 14px #2d1f1406;
            transition: all .2s;
        }
        .catalog-card:hover {
            box-shadow: 0 8px 24px #2d1f1412;
            border-color: #e2d7cc;
        }
        .catalog-thumb {
            width: 40%;
            min-width: 115px;
            height: 100%;
            display: block;
            object-fit: cover;
            border-radius: 9px;
            background: #ede6dd;
        }
        .catalog-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            padding: 6px 8px 6px 12px;
        }
        .catalog-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 6px;
        }
        .catalog-name {
            font: 600 14px/1.2 'Playfair Display', Georgia, serif;
            margin: 0;
            color: #34271f;
        }
        .catalog-type {
            font-size: 10px;
            color: #837d78;
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .type-tag {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: .02em;
        }
        .type-tag.printed {
            background: #eef5ed;
            color: #276229;
            border: 1px solid #d4e7d2;
        }
        .type-tag.digital {
            background: #edf3fa;
            color: #1b4d82;
            border: 1px solid #cfe0f3;
        }
        .favourite {
            border: 0;
            background: transparent;
            color: #a36e48;
            padding: 0;
            line-height: 1;
            font-size: 20px;
            cursor: pointer;
        }
        .favourite.active { color: #a0550f; }
        .catalog-description {
            font-size: 11px;
            color: #817a76;
            line-height: 1.35;
            margin: 4px 0 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .catalog-stars {
            color: #a7670b;
            font-size: 11px;
            margin-top: auto;
            line-height: 1;
        }
        .catalog-rating { color: #867e7a; font-size: 10px; margin-left: 4px; }

        .catalog-actions {
            display: flex;
            gap: 6px;
            margin-top: 8px;
        }
        .small-primary, .small-secondary {
            border-radius: 7px;
            height: 31px;
            white-space: nowrap;
            font-size: 10px;
            font-weight: 700;
            padding: 0 10px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            cursor: pointer;
            border: 0;
            transition: all .2s;
        }
        .small-primary { background: #955106; color: #fff; }
        .small-secondary { background: #f1eeea; color: #44362c; gap: 4px; }
        .small-secondary svg { height: 14px; width: 14px; }
        .small-primary:hover { background: #a65c10; }
        .small-secondary:hover { background: #e7ded3; }

        /* CTA Banner */
        .cta-banner {
            min-height: 80px;
            border-radius: 14px;
            overflow: hidden;
            background-position: center;
            background-size: cover;
            margin-top: 12px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 80px;
            gap: 25px;
            position: relative;
            box-shadow: 0 8px 24px rgba(40,25,12,.08);
        }
        .cta-banner h2 {
            font: 600 22px/1.2 'Playfair Display', Georgia, serif;
            letter-spacing: -.02em;
            margin: 0;
            color: #fff;
        }
        .cta-banner p {
            font-size: 12px;
            color: #f0e6dc;
            margin: 5px 0 0;
            line-height: 1.4;
        }
        .cta-button {
            height: 40px;
            min-width: 190px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 0;
            border-radius: 8px;
            background: #fff;
            color: #302b27;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            padding: 0 20px;
            transition: all .2s;
        }
        .cta-button svg { width: 16px; height: 16px; }
        .cta-button:hover { background: #f5e9d9; color: #8e4d06; }

        /* Footer */
        .footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 20px 4px 24px;
            color: #948e89;
            font-size: 11px;
        }
        .footer a { color: #8b5e35; text-decoration: none; }

        .status-line { font-size: 11px; color: #8d6542; min-height: 18px; margin-bottom: 8px; }
        .empty-results {
            padding: 24px;
            color: #7e736b;
            text-align: center;
            border: 1px dashed #e5d4c4;
            border-radius: 10px;
            background: #fffbf7;
            font-size: 13px;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translate(-50%, 25px);
            background: #29231f;
            color: #fff;
            padding: 12px 20px;
            border-radius: 11px;
            box-shadow: 0 16px 50px rgba(0,0,0,0.25);
            font-size: 12px;
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: .25s;
        }
        .toast.show { opacity: 1; transform: translate(-50%, 0); }

        /* Modal Overlay */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(16, 14, 13, 0.75);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 90;
            backdrop-filter: blur(2px);
        }
        .overlay.open { display: flex; }
        .modal {
            width: min(100%, 620px);
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 70px rgba(0,0,0,0.3);
        }
        .modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            background: #ffffffcc;
            border-radius: 50%;
            border: 1px solid #ddd;
            width: 34px;
            height: 34px;
            font-size: 20px;
            display: grid;
            place-items: center;
            z-index: 1;
            cursor: pointer;
        }
        .modal-main {
            display: grid;
            grid-template-columns: 45% 1fr;
            gap: 0;
        }
        .modal-main img {
            width: 100%;
            height: 360px;
            object-fit: cover;
            background: #eee5db;
        }
        .modal-text {
            padding: 30px 24px;
            display: flex;
            flex-direction: column;
        }
        .modal-text h2 {
            font: 600 26px 'Playfair Display', Georgia, serif;
            margin: 0 0 6px;
            color: #34271f;
        }
        .modal-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            color: #925409;
            background: #f7eedf;
            padding: 2px 10px;
            border-radius: 99px;
            margin-bottom: 12px;
            width: fit-content;
        }
        .modal-text p {
            font-size: 13px;
            color: #6e6660;
            line-height: 1.55;
            margin: 0 0 14px;
        }
        .modal-price {
            font-size: 18px;
            font-weight: 700;
            color: #975205;
            margin-bottom: 16px;
        }
        .modal-actions {
            margin-top: auto;
            display: flex;
            gap: 10px;
        }
        .modal-actions .btn {
            background: #955106;
            color: white;
            padding: 11px 18px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            cursor: pointer;
            flex: 1;
        }
        .modal-actions .btn-secondary {
            background: #f1eeea;
            color: #44362c;
        }

        /* Responsive */
        @media(max-width: 1150px) {
            .featured { grid-template-columns: 250px minmax(0, 1fr); }
            .catalog-list { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .cta-banner { padding: 16px 40px; }
        }
        @media(max-width: 768px) {
            .nav-shell { width: calc(100% - 32px); height: 56px; }
            .nav-links, .nav-tools .nav-btn, .nav-tools .user-dropdown-wrap { display: none; }
            .mobile-menu-button { display: flex; }
            .mobile-nav {
                display: block;
                position: absolute;
                top: 60px;
                left: 16px;
                right: 16px;
                background: #fff;
                border: 1px solid #ece8e2;
                border-radius: 13px;
                padding: 12px;
                z-index: 70;
                box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            }
            .mobile-nav[hidden] { display: none; }
            .mobile-nav a {
                display: block;
                padding: 10px 12px;
                color: #453930;
                font-size: 13px;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
            }
            .mobile-nav a:hover { background: #f7f3ee; color: #8e4d06; }
            .mobile-auth { border-top: 1px solid #eeece9; margin-top: 8px; padding-top: 8px; display: flex; gap: 8px; }
            .mobile-auth a { flex: 1; text-align: center; }

            .hero-surface { width: calc(100% - 24px); min-height: 400px; }
            .hero-heading { font-size: 28px; }
            .hero-heading em { font-size: 24px; }
            .hero-text-slider { min-height: 120px; }
            .hero-subtitle { font-size: 13px; }
            .hero-content { padding: 24px 14px 20px; }
            .chip-bar { display: none !important; }
            .content-wrap { width: calc(100% - 24px); }

            .featured { grid-template-columns: 1fr; padding: 16px; }
            .feature-card { flex: 0 0 145px; min-width: 145px; max-width: 145px; }

            .middle-grid { grid-template-columns: 1fr; }
            .category-options { grid-template-columns: repeat(2, 1fr); }
            .catalog-list { grid-template-columns: 1fr; }
            .catalog-card { height: 160px; }
            .cta-banner {
                flex-direction: column;
                align-items: flex-start;
                padding: 22px;
                gap: 14px;
            }
            .modal-main { grid-template-columns: 1fr; }
            .modal-main img { height: 220px; }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Global SVG Icons -->
    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" style="position:absolute;width:0;height:0;overflow:hidden">
        <symbol id="i-logo" viewBox="0 0 24 30"><path d="M10.8 12.7C7 10.3 4.5 7.2 4.3 3.6c5.7-.5 9 2.3 9.1 7.1L10.8 12.7Zm2.6-2c-.2-4.5 2.2-7.9 7.2-8.8.3 4.6-1.8 8.2-5.7 9.6l-1.5-.8ZM10.4 16c-4-2-7.5-.7-9 2.5C-.7 23 3.8 26.2 9 25.4l1.4-9.4Zm5.2-2c5.2.7 7.9 4.1 7 8.7-1.1 5-6.6 6.5-10.5 3.4l3.5-12.1Z" fill="currentColor"/><path d="M12.4 13.4v13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></symbol>
        <symbol id="i-search" viewBox="0 0 24 24"><circle cx="10.6" cy="10.6" r="7.1" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="m16 16 5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
        <symbol id="i-arrow" viewBox="0 0 24 24"><path d="m9 5 7 7-7 7" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></symbol>
        <symbol id="i-star" viewBox="0 0 24 24"><path d="m12 2 2.7 7.2L22 12l-7.3 2.8L12 22l-2.7-7.2L2 12l7.3-2.8L12 2Z" fill="none" stroke="currentColor" stroke-width="1.75"/></symbol>
        <symbol id="i-ring" viewBox="0 0 24 24"><circle cx="9" cy="13" r="5.7" stroke="currentColor" stroke-width="1.85" fill="none"/><circle cx="15.5" cy="13" r="5.7" stroke="currentColor" stroke-width="1.85" fill="none"/></symbol>
        <symbol id="i-cake" viewBox="0 0 24 24"><path d="M3 11h18v10H3zM3 16h18M8 7v4m4-4v4m4-4v4M8 3v2m4-2v2m4-2v2" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></symbol>
        <symbol id="i-baby" viewBox="0 0 24 24"><circle cx="12" cy="13" r="8" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="M9 5c-2-3 4-5 5-2 .4 1-.4 2-1 2M8 12h.1M16 12h.1M9 16c2 2 4 2 6 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
        <symbol id="i-heart" viewBox="0 0 24 24"><path d="M20.4 5C18 2.8 14.5 3 12 5.8 9.5 3 6 2.8 3.6 5a5.7 5.7 0 0 0 0 8.2L12 21l8.4-7.8a5.7 5.7 0 0 0 0-8.2Z" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linejoin="round"/></symbol>
        <symbol id="i-mosque" viewBox="0 0 24 24"><path d="M3 21V10l9-8 9 8v11M8 21V12h8v9M12 3v18M1 21h22" fill="none" stroke="currentColor" stroke-width="1.55" stroke-linecap="round" stroke-linejoin="round"/></symbol>
        <symbol id="i-grad" viewBox="0 0 24 24"><path d="m2 9 10-5 10 5-10 5L2 9Zm4 3v6c4 3 8 3 12 0v-6M22 9v9" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></symbol>
        <symbol id="i-phone" viewBox="0 0 24 24"><rect x="7" y="2" width="10" height="20" rx="2" stroke="currentColor" stroke-width="1.75" fill="none"/><path d="M10 18.5h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></symbol>
        <symbol id="i-flash" viewBox="0 0 24 24"><path d="M13 1 4 13h7l-1 10L20 9h-7V1Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></symbol>
        <symbol id="i-cart" viewBox="0 0 24 24"><path d="M2 3h3l2.1 13h12l2.4-9H6M8 21h.1M18 21h.1" stroke="currentColor" stroke-width="1.7" fill="none" stroke-linecap="round" stroke-linejoin="round"/></symbol>
        <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.75" fill="none"/><path d="M5 21v-2a7 7 0 0 1 14 0v2" stroke="currentColor" stroke-width="1.75" fill="none"/></symbol>
        <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    </svg>

    <!-- Header Navigation -->
    <header class="relative" id="beranda">
        <nav class="nav-shell" aria-label="Navigasi utama">
            <a href="{{ route('home') }}" class="logo">
                @if(site_logo_url())
                    <img src="{{ site_logo_url() }}" alt="{{ setting('site_name', 'Undangin') }}" class="logo-img">
                @else
                    <svg class="logo-mark"><use href="#i-logo"/></svg>
                @endif
                <span class="logo-word">{{ setting('site_name', 'Undangin') }}</span>
            </a>

            <div class="nav-links">
                <a class="active" href="#beranda">Beranda</a>
                <a href="#template">Koleksi Undangan</a>
                <a href="#kategori">Kategori</a>
                <a href="{{ route('catalog.index') }}">Katalog Lengkap</a>
                <a href="{{ route('how-to-order') }}">Cara Pesan</a>
                <a href="{{ route('contact') }}">Kontak</a>
            </div>

            <div class="nav-tools">
                <button class="nav-icon-btn" id="navSearch" title="Cari undangan cetak, website..." aria-label="Cari undangan">
                    <svg><use href="#i-search"/></svg>
                </button>

                <!-- Keranjang Belanja -->
                @php
                    $cartCount = auth()->check() ? auth()->user()->carts()->sum('quantity') : 0;
                @endphp
                <a href="{{ route('cart.index') }}" class="nav-icon-btn" title="Keranjang Belanja">
                    <svg><use href="#i-cart"/></svg>
                    @if($cartCount > 0)
                        <span class="badge-count">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    <!-- User Dropdown Link -->
                    <a href="{{ route('order.index') }}" class="nav-btn" title="Pesanan Saya">
                        Pesanan
                    </a>
                    <div class="relative user-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="nav-btn primary flex items-center gap-1.5" title="Profil Akun">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-5 h-5 rounded-full object-cover">
                            @endif
                            <span>Akun</span>
                            <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition.opacity.duration.150ms class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-[#ece8e2] py-2 z-50 text-left" style="display: none;">
                            <div class="px-4 py-2.5 border-b border-stone-100 flex items-center gap-2.5">
                                @if(auth()->user()->avatar_url)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-stone-200 shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-[#f3ede6] text-[#935006] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-stone-800 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-stone-500 truncate">{{ auth()->user()->username ? '@' . auth()->user()->username : auth()->user()->email }}</p>
                                    @if(auth()->user()->isAdmin())
                                        <span class="inline-block mt-0.5 px-2 py-0.5 text-[9px] font-semibold bg-[#f5ede4] text-[#935006] rounded-full">
                                            Administrator
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-[#935006] hover:bg-[#faf8f5] font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    Admin Panel
                                </a>
                            @endif

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 hover:bg-[#faf8f5]">
                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Dashboard Member
                            </a>

                            <a href="{{ route('order.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 hover:bg-[#faf8f5]">
                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                Pesanan Saya
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 hover:bg-[#faf8f5]">
                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                                Profil Saya
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="border-t border-stone-100 mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-semibold">
                                    <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">Masuk</a>
                    <a href="{{ route('register') }}" class="nav-btn primary">Daftar</a>
                @endauth

                <button id="mobileMenuButton" class="mobile-menu-button" aria-expanded="false" aria-label="Buka menu">
                    <svg width="20" height="20"><use href="#i-menu"/></svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Drawer -->
        <div id="mobileNav" class="mobile-nav" hidden>
            <a href="#beranda">Beranda</a>
            <a href="#template">Koleksi Undangan</a>
            <a href="#kategori">Kategori Acara</a>
            <a href="{{ route('catalog.index') }}">Katalog Lengkap</a>
            <a href="{{ route('how-to-order') }}">Cara Pesan</a>
            <a href="{{ route('contact') }}">Kontak Kami</a>
            <div class="mobile-auth">
                @auth
                    <a href="{{ route('cart.index') }}" class="nav-btn">Keranjang ({{ $cartCount }})</a>
                    <a href="{{ route('order.index') }}" class="nav-btn primary">Pesanan Saya</a>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">Masuk</a>
                    <a href="{{ route('register') }}" class="nav-btn primary">Daftar</a>
                @endauth
            </div>
            @auth
                <div class="pt-2 mt-2 border-t border-[#ece8e2] flex flex-col gap-1 text-xs">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-[#935006] font-semibold py-1.5 px-3 rounded-lg hover:bg-[#faf8f5]">Admin Panel</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="py-1.5 px-3 rounded-lg hover:bg-[#faf8f5]">Dashboard Member</a>
                    <a href="{{ route('profile.edit') }}" class="py-1.5 px-3 rounded-lg hover:bg-[#faf8f5]">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 px-3 text-rose-600 font-semibold rounded-lg hover:bg-rose-50">Keluar</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main>
        <!-- 1. Hero Section (Dynamic Banner Slider & Content) -->
        @php
            $hasBanners = isset($banners) && $banners->isNotEmpty();
            $defaultHeroBg = str_starts_with($heroBg, 'http') || str_starts_with($heroBg, 'data:')
                ? $heroBg
                : asset($heroBg);
        @endphp
        <section class="hero-surface" id="heroSlider" aria-label="Banner Promosi & Katalog Undangan">
            <!-- Background Image Slides -->
            <div class="hero-slides" aria-hidden="true">
                @if($hasBanners)
                    @foreach($banners as $index => $banner)
                        <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" 
                             style="background-image: url('{{ $banner->image_url }}');"
                             data-slide-index="{{ $index }}"></div>
                    @endforeach
                @else
                    <div class="hero-slide active" 
                         style="background-image: url('{{ $defaultHeroBg }}');"
                         data-slide-index="0"></div>
                @endif
            </div>

            <div class="hero-content">
                <!-- Pill Badge -->
                <div class="hero-pill">
                    <span>✦</span> {{ setting('hero_pill_text', 'Katalog Undangan Cetak & Digital Eksklusif') }}
                </div>

                <!-- Text Slider (Banner Title & Subtitle) -->
                <div class="hero-text-slider">
                    @if($hasBanners)
                        @foreach($banners as $index => $banner)
                            <div class="hero-text-slide {{ $index === 0 ? 'active' : '' }}" data-text-index="{{ $index }}">
                                <h1 class="hero-heading" id="hero-title-{{ $index }}">
                                    {{ $banner->title }}
                                </h1>
                                @if(!empty($banner->subtitle))
                                    <p class="hero-subtitle">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif
                                @if(!empty($banner->link_url))
                                    <a href="{{ $banner->link_url }}" class="hero-cta-link">
                                        <span>Lihat Selengkapnya</span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="hero-text-slide active" data-text-index="0">
                            <h1 class="hero-heading" id="hero-title-default">
                                {{ setting('hero_title', 'Rayakan Setiap Cerita') }}
                                <em>{{ setting('hero_subtitle_italic', 'dengan Undangan yang Berkesan') }}</em>
                            </h1>
                            <p class="hero-subtitle">
                                {{ setting('hero_subtitle', 'Pilihan lengkap undangan cetak fisik berkualitas tinggi hingga undangan digital interaktif yang elegan untuk setiap momen spesial Anda.') }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Search Form -->
                <form class="hero-search" id="searchForm">
                    <svg><use href="#i-search"/></svg>
                    <input id="searchInput" placeholder="Cari undangan cetak, digital, tema acara..." type="search" aria-label="Cari undangan">
                    <button type="submit">Cari</button>
                </form>

                <!-- Chip Bar (Category & Type quick filter) -->
                <div class="chip-bar" aria-label="Filter kategori cepat">
                    <button class="chip selected" data-filter="Semua" aria-pressed="true">Semua</button>
                    <button class="chip" data-filter="Cetak Fisik">Cetak Fisik</button>
                    <button class="chip" data-filter="Digital Web">Digital Web</button>
                    @foreach($categories as $cat)
                        <button class="chip" data-filter="{{ $cat->name }}">{{ $cat->name }}</button>
                    @endforeach
                </div>

                <!-- Dots Navigation (if multiple banners) -->
                @if($hasBanners && $banners->count() > 1)
                    <div class="hero-dots" aria-label="Navigasi slide banner">
                        @foreach($banners as $index => $banner)
                            <button type="button" 
                                    class="hero-dot {{ $index === 0 ? 'active' : '' }}" 
                                    data-dot-index="{{ $index }}" 
                                    aria-label="Slide {{ $index + 1 }}: {{ $banner->title }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <div class="content-wrap">
            <!-- 2. Koleksi Pilihan Minggu Ini (Featured Carousel) -->
            <section class="panel featured" aria-labelledby="featured-title">
                <div class="featured-copy">
                    <div class="featured-heading">
                        <svg><use href="#i-star"/></svg>
                        <h2 id="featured-title">Koleksi Pilihan Minggu Ini</h2>
                    </div>
                    <p>Pilihan undangan cetak &amp; digital terbaik yang paling banyak diminati calon pengantin serta penyelenggara acara.</p>
                    <div class="carousel-nav">
                        <button class="round-arrow" id="slidePrev" aria-label="Koleksi sebelumnya">‹</button>
                        <button class="round-arrow" id="slideNext" aria-label="Koleksi selanjutnya">›</button>
                    </div>
                </div>

                <div class="feature-gallery-container">
                    <div class="feature-gallery" id="featuredGallery" aria-label="Koleksi pilihan minggu ini">
                        @foreach($featuredProducts as $fp)
                            <button class="feature-card" data-template-id="{{ $fp->id }}">
                                <div class="feature-img-wrap">
                                    <img src="{{ $fp->primary_image_url }}" alt="{{ $fp->name }}">
                                    <span class="feature-badge {{ $fp->type === 'printed' ? 'printed' : 'digital' }}">
                                        {{ $fp->type === 'printed' ? 'Cetak Fisik' : 'Digital Web' }}
                                    </span>
                                </div>
                                <strong class="feature-label">{{ $fp->name }}</strong>
                                <span class="stars">★★★★★ <span class="rating">4.9</span></span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- 3. Middle Grid (Kategori Acara & Keunggulan) -->
            <div class="middle-grid" id="kategori">
                <section class="panel category-panel" aria-labelledby="category-title">
                    <h2 class="panel-title" id="category-title">Kategori Acara &amp; Paket</h2>
                    <div class="category-options">
                        @php
                            $catIcons = [
                                'pernikahan' => '#i-ring',
                                'ulang-tahun' => '#i-cake',
                                'aqiqah' => '#i-baby',
                                'tunangan' => '#i-heart',
                                'khitanan' => '#i-mosque',
                                'wisuda' => '#i-grad',
                            ];
                        @endphp
                        @foreach($categories->take(6) as $cItem)
                            <button class="cat-card" data-category="{{ $cItem->name }}">
                                <svg><use href="{{ $catIcons[$cItem->slug] ?? '#i-heart' }}"/></svg>
                                <span>{{ $cItem->name }}</span>
                                <svg class="chevron"><use href="#i-arrow"/></svg>
                            </button>
                        @endforeach
                    </div>
                </section>

                <section class="panel why-panel" aria-labelledby="why-title">
                    <h2 class="panel-title" id="why-title">Kenapa Memilih Layanan Kami?</h2>
                    <div class="benefits">
                        <div class="benefit">
                            <div class="benefit-icon"><svg><use href="#i-star"/></svg></div>
                            <h3>Desain Eksklusif</h3>
                            <p>Koleksi desain modern &amp; mewah untuk undangan cetak maupun digital.</p>
                        </div>
                        <div class="benefit">
                            <div class="benefit-icon"><svg><use href="#i-phone"/></svg></div>
                            <h3>Kualitas Terjamin</h3>
                            <p>Kertas bertekstur premium, hardbox presisi, serta web responsif.</p>
                        </div>
                        <div class="benefit">
                            <div class="benefit-icon"><svg><use href="#i-flash"/></svg></div>
                            <h3>Pemesanan Praktis</h3>
                            <p>Konsultasi ramah, revisi fleksibel, dan pengerjaan tepat waktu.</p>
                        </div>
                        <div class="benefit">
                            <div class="benefit-icon"><svg><use href="#i-heart"/></svg></div>
                            <h3>Untuk Semua Momen</h3>
                            <p>Pernikahan, tunangan, khitanan, aqiqah, wisuda, &amp; acara keluarga.</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- 4. Katalog Undangan (Grid, Filter, Search, Favorit, Modal) -->
            <section class="panel catalog-panel" id="template" aria-labelledby="catalog-title">
                <div class="section-header">
                    <h2 id="catalog-title">Katalog Undangan</h2>
                    <a href="{{ route('catalog.index') }}" id="viewAll" class="view-all">
                        Buka Katalog Lengkap <svg><use href="#i-arrow"/></svg>
                    </a>
                </div>

                <div class="status-line" id="searchStatus" aria-live="polite"></div>

                <div class="catalog-list" id="catalogGrid">
                    <!-- Di-render dinamis oleh JavaScript menggunakan data backend -->
                </div>

                <div class="empty-results" id="emptyState" hidden>
                    Belum ada produk undangan untuk pencarian atau filter ini. Silakan coba kata kunci atau filter lainnya.
                </div>
            </section>

            <!-- 5. CTA Banner (Dynamic Background & Link) -->
            @php
                $ctaBgUrl = str_starts_with($ctaBg, 'http') || str_starts_with($ctaBg, 'data:')
                    ? $ctaBg
                    : asset($ctaBg);
            @endphp
            <section class="cta-banner" id="harga" style="background-image: linear-gradient(to right, rgba(59,38,19,.25), rgba(59,38,19,.08)), url('{{ $ctaBgUrl }}');" aria-label="Jelajahi seluruh katalog undangan">
                <div>
                    <h2>{{ setting('cta_title', 'Wujudkan Undangan Impian Anda') }}</h2>
                    <p>{{ setting('cta_subtitle', 'Pilih desain favorit Anda, dari paket cetak fisik eksklusif hingga undangan digital modern.') }}</p>
                </div>
                <a href="{{ route('catalog.index') }}" id="ctaExplore" class="cta-button">
                    Jelajahi Semua Undangan <svg><use href="#i-arrow"/></svg>
                </a>
            </section>

            <!-- 6. Footer -->
            <footer class="footer" id="tentang">
                <span>&copy; {{ date('Y') }} {{ setting('site_name', 'Undangin') }} — {{ setting('site_tagline', 'Katalog Undangan Cetak & Digital Eksklusif') }}.</span>
                <span>Konsultasi Cepat: <a href="{{ wa_link('Halo Admin, saya tertarik untuk konsultasi dan memesan undangan (cetak / digital).') }}" target="_blank" rel="noopener">WhatsApp Admin</a></span>
            </footer>
        </div>
    </main>

    <!-- Modal Detail Overlay -->
    <div class="overlay" id="detailOverlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal">
            <button class="modal-close" id="closeModal" aria-label="Tutup dialog">&times;</button>
            <div class="modal-main">
                <img id="modalImage" alt="Preview produk undangan" src="">
                <div class="modal-text">
                    <span class="modal-badge" id="modalCategory">Pernikahan</span>
                    <h2 id="modalTitle">Nama Undangan</h2>
                    <p id="modalDescription">Deskripsi produk undangan...</p>
                    <div class="modal-price" id="modalPrice">Rp 150.000</div>
                    <div style="color:#aa640b; font-size:14px; margin-bottom:16px;">★★★★★ <span id="modalRating" style="color:#777; font-size:12px;">4.9</span></div>
                    <div class="modal-actions">
                        <a href="#" id="modalDetailLink" class="btn btn-secondary">Lihat Halaman Produk</a>
                        <a href="#" id="modalUse" class="btn">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Popup Notification -->
    <div class="toast" id="toast" role="status" aria-live="polite"></div>

    <!-- JSON DATA DARI BACKEND LARAVEL -->
    <script>
        window.__TEMPLATES__ = {!! json_encode($templatesJson) !!};
        window.__FEATURED__ = {!! json_encode($featuredJson) !!};
    </script>

    <!-- Client-Side Interactive Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const templates = window.__TEMPLATES__ || [];
            const featuredList = window.__FEATURED__ || [];
            let chosenCategory = 'Semua';
            let keyword = '';
            let carouselOffset = 0;
            let expanded = false;

            const gallery = document.querySelector('#featuredGallery');
            const catalogGrid = document.querySelector('#catalogGrid');
            const searchStatus = document.querySelector('#searchStatus');
            const emptyState = document.querySelector('#emptyState');
            const toastEl = document.querySelector('#toast');
            const overlay = document.querySelector('#detailOverlay');

            // Favorit LocalStorage
            const favouriteSet = new Set((() => {
                try { return JSON.parse(window.localStorage.getItem('undangin-favorites') || '[]'); }
                catch { return []; }
            })());

            function toast(msg) {
                toastEl.textContent = msg;
                toastEl.classList.add('show');
                clearTimeout(window._toastTimer);
                window._toastTimer = setTimeout(() => toastEl.classList.remove('show'), 2600);
            }

            function getTemplate(idOrSlug) {
                return templates.find(t => t.id == idOrSlug || t.slug == idOrSlug) ||
                       featuredList.find(t => t.id == idOrSlug || t.slug == idOrSlug);
            }

            // Featured carousel render (single horizontal row)
            function renderFeatured() {
                if (!featuredList.length) return;
                gallery.innerHTML = featuredList.map(t => {
                    const typeText = t.type_label || (t.type === 'printed' ? 'Cetak Fisik' : 'Digital Web');
                    const typeClass = t.type === 'printed' ? 'printed' : 'digital';
                    return `
                    <button class="feature-card" data-template-id="${t.id}" title="${t.name}">
                        <div class="feature-img-wrap">
                            <img src="${t.image}" alt="${t.name}" loading="lazy">
                            <span class="feature-badge ${typeClass}">${typeText}</span>
                        </div>
                        <strong class="feature-label">${t.name}</strong>
                        <span class="stars">★★★★★ <span class="rating">${t.rating}</span></span>
                    </button>
                    `;
                }).join('');
            }

            // Catalog Card Template
            function catalogCardHtml(t) {
                const isFav = favouriteSet.has(String(t.id));
                const typeText = t.type_label || (t.type === 'printed' ? 'Cetak Fisik' : 'Digital Web');
                const typeClass = t.type === 'printed' ? 'printed' : 'digital';
                return `
                    <article class="catalog-card" data-category="${t.category}" data-template-id="${t.id}">
                        <img class="catalog-thumb" src="${t.image}" alt="${t.name}" loading="lazy">
                        <div class="catalog-info">
                            <div class="catalog-top">
                                <div>
                                    <h3 class="catalog-name">${t.name}</h3>
                                    <div class="catalog-type">
                                        <span class="type-tag ${typeClass}">${typeText}</span>
                                        <span>${t.category}</span>
                                    </div>
                                </div>
                                <button class="favourite ${isFav ? 'active' : ''}" aria-label="Favorit ${t.name}" data-fav="${t.id}">
                                    ${isFav ? '♥' : '♡'}
                                </button>
                            </div>
                            <p class="catalog-description">${t.description}</p>
                            <div class="catalog-stars">
                                ★★★★★ <span class="catalog-rating">${t.rating} (${t.reviews})</span>
                            </div>
                            <div class="catalog-actions">
                                <button class="small-primary" data-detail="${t.id}">Lihat Detail</button>
                                <a href="${t.detail_url}" class="small-secondary" data-use="${t.id}">
                                    <svg><use href="#i-cart"/></svg> Pesan
                                </a>
                            </div>
                        </div>
                    </article>
                `;
            }

            // Catalog Filter & Render
            function renderCatalog() {
                let filtered = templates.filter(t => {
                    let matchCategory = true;
                    if (chosenCategory !== 'Semua') {
                        if (chosenCategory === 'Cetak Fisik') {
                            matchCategory = (t.type === 'printed');
                        } else if (chosenCategory === 'Digital Web') {
                            matchCategory = (t.type === 'digital');
                        } else {
                            matchCategory = (t.category.toLowerCase() === chosenCategory.toLowerCase());
                        }
                    }
                    const matchKeyword = (!keyword || `${t.name} ${t.category} ${t.type_label || ''} ${t.description}`.toLowerCase().includes(keyword.toLowerCase()));
                    return matchCategory && matchKeyword;
                });

                if (!expanded && !keyword && chosenCategory === 'Semua') {
                    filtered = filtered.slice(0, 9);
                }

                catalogGrid.innerHTML = filtered.map(catalogCardHtml).join('');
                emptyState.hidden = filtered.length > 0;

                if (keyword || chosenCategory !== 'Semua') {
                    searchStatus.textContent = `Menampilkan ${filtered.length} pilihan undangan untuk ${chosenCategory !== 'Semua' ? 'filter "' + chosenCategory + '"' : 'semua pilihan'}${keyword ? ' · kata kunci: "' + keyword + '"' : ''}`;
                } else {
                    searchStatus.textContent = '';
                }
            }

            function selectCategory(cat) {
                chosenCategory = cat;
                document.querySelectorAll('[data-filter]').forEach(b => {
                    const isSelected = b.dataset.filter === cat;
                    b.classList.toggle('selected', isSelected);
                    b.setAttribute('aria-pressed', String(isSelected));
                });
                document.querySelectorAll('.cat-card').forEach(b => {
                    b.classList.toggle('is-active', b.dataset.category === cat);
                });
                renderCatalog();
            }

            // Event Listeners - Featured Carousel (slide & drag)
            document.querySelector('#slidePrev')?.addEventListener('click', () => {
                gallery?.scrollBy({ left: -340, behavior: 'smooth' });
            });

            document.querySelector('#slideNext')?.addEventListener('click', () => {
                gallery?.scrollBy({ left: 340, behavior: 'smooth' });
            });

            // Smooth Mouse Drag-to-Slide (Bisa di geser-geser)
            let isDown = false;
            let startX = 0;
            let scrollLeftPos = 0;
            let hasDragged = false;

            gallery?.addEventListener('mousedown', (e) => {
                isDown = true;
                hasDragged = false;
                startX = e.pageX - gallery.offsetLeft;
                scrollLeftPos = gallery.scrollLeft;
            });

            window.addEventListener('mouseup', () => {
                if (isDown) {
                    isDown = false;
                    setTimeout(() => { hasDragged = false; }, 80);
                }
            });

            gallery?.addEventListener('mouseleave', () => {
                if (isDown) {
                    isDown = false;
                    setTimeout(() => { hasDragged = false; }, 80);
                }
            });

            gallery?.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                const x = e.pageX - gallery.offsetLeft;
                const walk = (x - startX) * 1.5;
                if (Math.abs(walk) > 5) {
                    hasDragged = true;
                }
                gallery.scrollLeft = scrollLeftPos - walk;
            });

            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectCategory(btn.dataset.filter);
                    if (btn.dataset.filter !== 'Semua') {
                        document.querySelector('#template').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            document.querySelectorAll('.cat-card').forEach(btn => {
                btn.addEventListener('click', () => {
                    expanded = true;
                    selectCategory(btn.dataset.category);
                    document.querySelector('#template').scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            document.querySelector('#searchForm')?.addEventListener('submit', (e) => {
                e.preventDefault();
                keyword = document.querySelector('#searchInput').value.trim();
                expanded = true;
                renderCatalog();
                document.querySelector('#template').scrollIntoView({ behavior: 'smooth' });
            });

            document.querySelector('#navSearch')?.addEventListener('click', () => {
                const searchInp = document.querySelector('#searchInput');
                searchInp.focus();
                document.querySelector('#beranda').scrollIntoView({ behavior: 'smooth' });
            });

            // Mobile menu toggle
            const mobileBtn = document.querySelector('#mobileMenuButton');
            const mobileNav = document.querySelector('#mobileNav');
            mobileBtn?.addEventListener('click', () => {
                mobileNav.hidden = !mobileNav.hidden;
                mobileBtn.setAttribute('aria-expanded', String(!mobileNav.hidden));
            });
            document.querySelectorAll('#mobileNav a').forEach(a => {
                a.addEventListener('click', () => { mobileNav.hidden = true; });
            });

            // Detail Modal Handlers
            function openDetail(t) {
                if (!t) return;
                document.querySelector('#modalImage').src = t.image;
                const typeText = t.type_label || (t.type === 'printed' ? 'Cetak Fisik' : 'Digital Web');
                document.querySelector('#modalCategory').textContent = `${t.category} • ${typeText}`;
                document.querySelector('#modalDescription').textContent = t.description;
                document.querySelector('#modalPrice').textContent = t.price || 'Rp 150.000';
                document.querySelector('#modalRating').textContent = t.rating || '4.9';
                document.querySelector('#modalDetailLink').href = t.detail_url;
                document.querySelector('#modalUse').href = t.detail_url;
                overlay.classList.add('open');
                document.querySelector('#closeModal').focus();
            }

            function closeDetail() {
                overlay.classList.remove('open');
            }

            document.querySelector('#closeModal')?.addEventListener('click', closeDetail);
            overlay?.addEventListener('click', (e) => { if (e.target === overlay) closeDetail(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDetail(); });

            // Global Click Delegation
            document.addEventListener('click', (e) => {
                // Favorite Button
                const favBtn = e.target.closest('[data-fav]');
                if (favBtn) {
                    const id = String(favBtn.dataset.fav);
                    if (favouriteSet.has(id)) {
                        favouriteSet.delete(id);
                        favBtn.textContent = '♡';
                        favBtn.classList.remove('active');
                        toast('Dihapus dari daftar favorit');
                    } else {
                        favouriteSet.add(id);
                        favBtn.textContent = '♥';
                        favBtn.classList.add('active');
                        toast('Ditambahkan ke daftar favorit');
                    }
                    try {
                        window.localStorage.setItem('undangin-favorites', JSON.stringify([...favouriteSet]));
                    } catch {}
                    return;
                }

                // Detail Modal Click
                const detailBtn = e.target.closest('[data-detail], .feature-card');
                if (detailBtn) {
                    if (hasDragged) return; // Abaikan klik jika baru selesai drag/geser
                    const id = detailBtn.dataset.detail || detailBtn.dataset.templateId;
                    const item = getTemplate(id);
                    if (item) openDetail(item);
                    return;
                }
            });

            // Hero Banner Slider
            const heroSlider = document.querySelector('#heroSlider');
            const bgSlides = document.querySelectorAll('.hero-slide');
            const textSlides = document.querySelectorAll('.hero-text-slide');
            const heroDots = document.querySelectorAll('.hero-dot');
            const totalHeroSlides = bgSlides.length;
            let currentHeroIndex = 0;
            let heroSlideInterval = null;

            function goToHeroSlide(index) {
                if (totalHeroSlides <= 1) return;
                currentHeroIndex = (index + totalHeroSlides) % totalHeroSlides;
                bgSlides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === currentHeroIndex);
                });
                textSlides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === currentHeroIndex);
                });
                heroDots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentHeroIndex);
                });
            }

            function startHeroAutoSlide() {
                if (totalHeroSlides <= 1) return;
                stopHeroAutoSlide();
                heroSlideInterval = setInterval(() => {
                    goToHeroSlide(currentHeroIndex + 1);
                }, 5000);
            }

            function stopHeroAutoSlide() {
                if (heroSlideInterval) {
                    clearInterval(heroSlideInterval);
                    heroSlideInterval = null;
                }
            }

            if (totalHeroSlides > 1) {
                startHeroAutoSlide();

                heroDots.forEach((dot) => {
                    dot.addEventListener('click', () => {
                        const targetIdx = parseInt(dot.dataset.dotIndex, 10);
                        if (!isNaN(targetIdx)) {
                            goToHeroSlide(targetIdx);
                            startHeroAutoSlide();
                        }
                    });
                });

                if (heroSlider) {
                    heroSlider.addEventListener('mouseenter', stopHeroAutoSlide);
                    heroSlider.addEventListener('mouseleave', startHeroAutoSlide);
                    heroSlider.addEventListener('focusin', stopHeroAutoSlide);
                    heroSlider.addEventListener('focusout', startHeroAutoSlide);
                }
            }

            // Init
            renderFeatured();
            renderCatalog();
        });
    </script>

    <!-- PROMOTIONAL POPUP MODAL (PORTRAIT) -->
    @if(popup_is_enabled() || request()->has('preview_popup'))
        @php
            $popupDelayMs = (int) (max(0, (float) setting('popup_delay_seconds', '1.5')) * 1000);
            $popupTarget = popup_target_url();
            $popupImage = popup_image_url();
            $popupTitle = setting('popup_title', 'Promo Spesial Galeria Invitation');
            $isPreview = request()->has('preview_popup');
        @endphp
        <div
            id="promoPopupOverlay"
            x-data="{
                isOpen: false,
                dontShowToday: false,
                previewMode: {{ $isPreview ? 'true' : 'false' }},
                storageKeySession: 'galeria_promo_popup_seen',
                storageKeyDay: 'galeria_promo_popup_dismissed_until',

                init() {
                    if (this.previewMode) {
                        setTimeout(() => { this.isOpen = true; }, 300);
                        return;
                    }

                    // Check if dismissed for today via localStorage
                    const dismissedUntil = localStorage.getItem(this.storageKeyDay);
                    if (dismissedUntil && Date.now() < Number(dismissedUntil)) {
                        return;
                    }

                    // Check if already seen in current browser session
                    if (sessionStorage.getItem(this.storageKeySession)) {
                        return;
                    }

                    // Open after configured delay
                    setTimeout(() => {
                        this.isOpen = true;
                        sessionStorage.setItem(this.storageKeySession, 'true');
                    }, {{ $popupDelayMs }});
                },

                close() {
                    this.isOpen = false;
                    if (this.dontShowToday) {
                        const endOfDay = new Date();
                        endOfDay.setHours(23, 59, 59, 999);
                        localStorage.setItem(this.storageKeyDay, endOfDay.getTime().toString());
                    }
                }
            }"
            x-show="isOpen"
            x-cloak
            @keydown.escape.window="close()"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-charcoal-950/75 backdrop-blur-sm transition-opacity duration-300"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;"
        >
            <!-- Backdrop Click Closes -->
            <div class="fixed inset-0" @click="close()"></div>

            <!-- Modal Content Card (Constrained Height & Portrait Aspect Ratio) -->
            <div
                class="relative z-10 w-full max-w-[320px] sm:max-w-[380px] flex flex-col items-center"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            >
                <!-- Floating Close Button -->
                <button
                    type="button"
                    @click="close()"
                    class="absolute -top-3.5 -right-3.5 z-30 w-9 h-9 rounded-full bg-charcoal-900/90 text-white hover:bg-black hover:scale-110 active:scale-95 transition-all flex items-center justify-center shadow-xl border border-white/30 focus:outline-none"
                    aria-label="Tutup Popup Promosi"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <!-- Clickable Poster Flyer (Portrait Ratio with max-height constraint) -->
                <a
                    href="{{ $popupTarget }}"
                    class="block w-full rounded-3xl overflow-hidden shadow-2xl border border-white/20 relative group bg-stone-900 max-h-[78vh] flex items-center justify-center transition-transform duration-300 hover:scale-[1.01]"
                >
                    <img
                        src="{{ $popupImage }}"
                        alt="{{ $popupTitle }}"
                        class="w-full h-auto max-h-[78vh] object-contain rounded-3xl"
                        loading="eager"
                    >

                    <!-- Hover Quick Prompt overlay on Desktop -->
                    <div class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-5 pointer-events-none">
                        <span class="px-5 py-2 rounded-full bg-champagne-600/95 text-white text-xs font-bold shadow-lg backdrop-blur-xs flex items-center gap-1.5">
                            <span>Buka Promo Ini</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </a>

                <!-- Bottom Action: 'Jangan tampilkan lagi hari ini' -->
                <div class="mt-3 flex items-center justify-center gap-2 text-xs text-white/95 select-none drop-shadow-md">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            x-model="dontShowToday"
                            class="rounded border-white/40 text-champagne-600 focus:ring-champagne-500 bg-black/40"
                        >
                        <span>Jangan tampilkan lagi hari ini</span>
                    </label>
                </div>
            </div>
        </div>
    @endif
</body>
</html>
