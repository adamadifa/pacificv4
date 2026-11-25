<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('titlepage', 'Portal') | Pacific</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/img/favicon/favicon.ico') }}" />

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Compiled Tailwind CSS & JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons from V1 (Tabler Icons & FontAwesome) -->
    @include('layouts.icons')

    <!-- Vendor Libraries (jQuery, SweetAlert2, Flatpickr, Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/vendor/js/jquery.maskMoney.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/easy-number-separator.js') }}?v=1.0.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.css') }}?v=1.0.1" />
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}?v=1.0.1"></script>

    <!-- ApexCharts Global Preload -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Dark Mode Initializer -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Custom Select2 Tailwind Overrides -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }

        /* ==========================================================================
           SELECT2 V2 TAILWIND DESIGN SYSTEM OVERRIDES
           ========================================================================== */
        /* In-page Select2 container inside form inputs: stays in DOM flow with 100% width and z-index 1 */
        .select2.select2-container,
        .field-group .select2-container,
        .relative > .select2-container {
            width: 100% !important;
            display: block !important;
            position: relative !important;
            z-index: 1 !important;
        }

        /* Prefix icon MUST always stay on top of the Select2 selection box */
        .relative > .input-prefix-icon,
        .field-group .relative .input-prefix-icon {
            z-index: 20 !important;
            pointer-events: none !important;
        }

        /* Floating dropdown overlay attached to document.body (never in-page container) */
        body > .select2-container,
        .select2-container:not(.select2) {
            z-index: 99999 !important;
            max-width: 100vw !important;
        }
        .select2-dropdown {
            z-index: 99999 !important;
        }

        .select2-container--default .select2-selection--single {
            position: relative !important;
            height: 36px !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important; /* slate-200 */
            border-radius: 0.5rem !important;     /* rounded-lg: 8px */
            padding: 0 50px 0 12px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.15s ease-in-out !important;
            outline: none !important;
            box-shadow: none !important;
        }
        /* When input has prefix icon: ensure left padding so text never touches icon */
        .input-prefix-icon ~ .select2 .select2-selection--single,
        .input-prefix-icon ~ .select2-container .select2-selection--single,
        [class*="left-0"] ~ .select2 .select2-selection--single,
        [class*="left-0"] ~ .select2-container .select2-selection--single,
        .select2-has-prefix + .select2 .select2-selection--single,
        .select2-has-prefix + .select2-container .select2-selection--single,
        .select2-has-prefix ~ .select2 .select2-selection--single,
        .select2-has-prefix ~ .select2-container .select2-selection--single,
        .select2-selection--single.select2-has-prefix,
        .relative:has(.input-prefix-icon) .select2-selection--single,
        .relative:has([class*="left-0"]) .select2-selection--single,
        .relative:has(i[data-lucide]) .select2-selection--single,
        .relative:has(svg) .select2-selection--single,
        .relative:has([data-lucide]) .select2-selection--single,
        .field-group .relative:has(.input-prefix-icon) .select2-selection--single {
            padding-left: 36px !important;
            padding-right: 50px !important;
        }

        /* Form Filter standard height (h-[34px]) support for Select2 */
        .select2-container--default.select2-h34 .select2-selection--single,
        select.h-\[34px\] + .select2 .select2-selection--single,
        select.h-\[34px\] ~ .select2 .select2-selection--single,
        select.h-\[34px\] + .select2-container .select2-selection--single,
        select.h-\[34px\] ~ .select2-container .select2-selection--single,
        form:has(.flatpickr-date) .select2-selection--single,
        .formSearch .select2-selection--single {
            height: 34px !important;
        }
        .select2-container--default.select2-h34 .select2-selection--single .select2-selection__rendered,
        select.h-\[34px\] + .select2 .select2-selection--single .select2-selection__rendered,
        select.h-\[34px\] ~ .select2 .select2-selection--single .select2-selection__rendered,
        select.h-\[34px\] + .select2-container .select2-selection--single .select2-selection__rendered,
        select.h-\[34px\] ~ .select2-container .select2-selection--single .select2-selection__rendered,
        form:has(.flatpickr-date) .select2-selection--single .select2-selection__rendered,
        .formSearch .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
        }
        .select2-container--default.select2-h34 .select2-selection--single .select2-selection__arrow,
        select.h-\[34px\] + .select2 .select2-selection--single .select2-selection__arrow,
        select.h-\[34px\] ~ .select2 .select2-selection--single .select2-selection__arrow,
        select.h-\[34px\] + .select2-container .select2-selection--single .select2-selection__arrow,
        select.h-\[34px\] ~ .select2-container .select2-selection--single .select2-selection__arrow,
        form:has(.flatpickr-date) .select2-selection--single .select2-selection__arrow,
        .formSearch .select2-selection--single .select2-selection__arrow {
            height: 34px !important;
        }
        /* Dark Mode */
        .dark .select2-container--default .select2-selection--single {
            background-color: #1e293b !important; /* slate-800 */
            border-color: #334155 !important;     /* slate-700 */
        }
        /* Focus & Open State */
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--open .select2-selection--multiple {
            border-color: #284c9a !important;
            box-shadow: 0 0 0 2px rgba(40, 76, 154, 0.2) !important;
        }

        /* Select2 Multiple V2 Tailwind Styling */
        .select2-container--default .select2-selection--multiple {
            position: relative !important;
            min-height: 36px !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important;
            padding: 4px 32px 4px 10px !important;
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 4px !important;
            transition: all 0.15s ease-in-out !important;
            outline: none !important;
            box-shadow: none !important;
        }
        .relative:has([class*="left-0"]) .select2-selection--multiple,
        .relative:has(i[data-lucide]) .select2-selection--multiple,
        .relative:has(svg) .select2-selection--multiple {
            padding-left: 34px !important;
        }
        .dark .select2-container--default .select2-selection--multiple {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 4px !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
            color: #284c9a !important;
            border-radius: 0.375rem !important;
            padding: 2px 8px 2px 22px !important;
            margin: 0 !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            line-height: 1.4 !important;
            position: relative !important;
        }
        .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: rgba(30, 58, 138, 0.4) !important;
            border-color: #1e40af !important;
            color: #93c5fd !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            bottom: 0 !important;
            width: 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-right: 1px solid #bfdbfe !important;
            color: #284c9a !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            transition: all 0.15s !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: #dbeafe !important;
            color: #e11d48 !important;
        }
        .dark .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right-color: #1e40af !important;
            color: #93c5fd !important;
        }
        .select2-container--default .select2-selection--multiple .select2-search--inline {
            flex: 1 !important;
            min-width: 80px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
            margin: 0 !important;
            padding: 0 !important;
            height: 24px !important;
            line-height: 24px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            width: 100% !important;
        }
        .dark .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
            color: #f1f5f9 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field::placeholder {
            color: #94a3b8 !important;
            font-weight: 500 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__clear {
            position: absolute !important;
            right: 10px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
            color: #94a3b8 !important;
            font-size: 14px !important;
            line-height: 1 !important;
            cursor: pointer !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__clear:hover {
            color: #e11d48 !important;
        }
        /* Invalid / Error State (takes precedence over normal, focus, and open) */
        .select2-container--default.is-invalid .select2-selection--single,
        .select2-container.is-invalid .select2-selection,
        .select2-selection.is-invalid,
        .select2-selection.border-rose-500,
        .select2-selection.\!border-rose-500,
        .select2-selection--single.is-invalid,
        .select2-selection--single.border-rose-500,
        .select2-selection--single.\!border-rose-500,
        .select2-container--default .select2-selection--single.is-invalid,
        .select2-container--default .select2-selection--single.\!border-rose-500,
        .select2-container--default .select2-selection--single.border-rose-500,
        .field-group.has-error .select2-container--default .select2-selection--single,
        .has-error .select2-container--default .select2-selection--single,
        .is-invalid + .select2-container--default .select2-selection--single,
        .is-invalid + .select2 .select2-selection,
        .is-invalid + .select2-container .select2-selection,
        .field-group.has-error .select2-selection,
        .has-error .select2-selection {
            border: 1px solid #f43f5e !important;
            border-color: #f43f5e !important;
            box-shadow: 0 0 0 1px #f43f5e !important;
        }
        .select2-container--default.is-invalid.select2-container--focus .select2-selection--single,
        .select2-container--default.is-invalid.select2-container--open .select2-selection--single,
        .has-error .select2-container--default.select2-container--focus .select2-selection--single,
        .has-error .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #f43f5e !important;
            box-shadow: 0 0 0 2px rgba(244, 63, 94, 0.25) !important;
        }
        /* Standard Form Control Invalid / Error State (takes precedence) */
        input.is-invalid,
        select.is-invalid,
        textarea.is-invalid,
        .has-error input,
        .has-error select,
        .has-error textarea,
        .field-group.has-error input,
        .field-group.has-error select,
        .field-group.has-error textarea {
            border: 1px solid #f43f5e !important;
            border-color: #f43f5e !important;
            box-shadow: 0 0 0 1px #f43f5e !important;
        }
        .has-error .input-prefix-icon,
        .field-group.has-error .input-prefix-icon,
        .input-prefix-icon.text-rose-500 {
            color: #f43f5e !important;
        }
        .field-error-msg {
            color: #e11d48 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            margin-top: 4px !important;
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
        }
        /* Rendered Text */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b !important; /* slate-800 */
            font-size: 0.75rem !important; /* text-xs: 12px */
            font-weight: 600 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            line-height: 34px !important;
            width: 100% !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f1f5f9 !important; /* slate-100 */
        }
        /* Placeholder */
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important; /* slate-400 */
            font-weight: 500 !important;
        }
        /* Right Chevron Arrow */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            position: absolute !important;
            top: 0 !important;
            right: 8px !important;
            width: 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border: none !important;
            width: 14px !important;
            height: 14px !important;
            margin: 0 !important;
            top: auto !important;
            left: auto !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            background-size: contain !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
            transition: transform 0.2s ease !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            transform: rotate(180deg) !important;
        }
        /* Clear Button (x) */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            position: absolute !important;
            right: 28px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 18px !important;
            height: 18px !important;
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            line-height: 1 !important;
            color: #94a3b8 !important; /* slate-400 */
            border-radius: 9999px !important;
            cursor: pointer !important;
            z-index: 10 !important;
            transition: all 0.15s ease !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #ffffff !important;
            background-color: #f43f5e !important; /* rose-500 */
        }
        /* Dropdown Panel */
        .select2-dropdown {
            border-radius: 0.5rem !important; /* rounded-lg: 8px */
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1) !important;
            z-index: 99999 !important;
            overflow: hidden !important;
        }
        .dark .select2-dropdown {
            background-color: #0f172a !important; /* slate-900 */
            border-color: #334155 !important;     /* slate-700 */
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5) !important;
        }
        /* Search Box inside Dropdown */
        .select2-search--dropdown {
            padding: 8px !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.375rem !important; /* rounded-md */
            padding: 5px 10px !important;
            font-size: 0.75rem !important; /* text-xs */
            font-weight: 500 !important;
            background-color: #f8fafc !important;
            color: #1e293b !important;
            outline: none !important;
        }
        .dark .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #284c9a !important;
            box-shadow: 0 0 0 2px rgba(40, 76, 154, 0.2) !important;
        }
        /* Results Options */
        .select2-results__options {
            padding: 4px !important;
            max-height: 220px !important;
        }
        .select2-container--default .select2-results__option {
            font-size: 0.75rem !important; /* text-xs */
            font-weight: 500 !important;
            padding: 6px 10px !important;
            border-radius: 0.375rem !important;
            margin-bottom: 2px !important;
            color: #334155 !important;
            transition: all 0.1s ease !important;
        }
        .dark .select2-container--default .select2-results__option {
            color: #cbd5e1 !important;
        }
        /* Highlighted option on hover/keyboard */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #284c9a !important;
            color: #ffffff !important;
            font-weight: 600 !important;
        }
        /* Selected option */
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #eff6ff !important;
            color: #284c9a !important;
            font-weight: 700 !important;
        }
        .dark .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #1e3a8a !important;
            color: #93c5fd !important;
        }

        /* ==========================================================================
           MODAL COMPONENT (BOOTSTRAP 5 COMPATIBILITY FOR TAILWIND)
           ========================================================================== */
        body.modal-open {
            overflow: hidden !important;
            padding-right: 0px !important;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1055;
            display: none;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
            background-color: transparent;
        }

        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            width: 100vw;
            height: 100vh;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: opacity 0.15s linear;
        }

        .modal-backdrop.fade {
            opacity: 0;
        }

        .modal-backdrop.show {
            opacity: 1;
        }

        .modal-dialog {
            position: relative;
            width: 100%;
            max-width: 540px;
            margin: 1.5rem auto;
            pointer-events: auto;
            padding: 0 1rem;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s ease;
        }

        .modal.fade .modal-dialog {
            transform: scale(0.96) translateY(-8px);
            opacity: 0;
        }

        .modal.show .modal-dialog {
            transform: none;
            opacity: 1;
        }

        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 3rem);
        }

        /* Modal Sizing Classes */
        .modal-sm { max-width: 380px !important; }
        .modal-md { max-width: 540px !important; }
        .modal-lg { max-width: 800px !important; }
        .modal-xl { max-width: 1140px !important; }
        .modal-xxl { max-width: 1320px !important; }
        .modal-xxxl { max-width: 95vw !important; }
        .modal-fullscreen {
            width: 100vw !important;
            max-width: none !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .modal-fullscreen .modal-content {
            height: 100vh !important;
            border-radius: 0 !important;
            border: none !important;
        }

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            outline: 0;
            overflow: hidden;
        }

        .dark .modal-content {
            background-color: #0f172a;
            border-color: #334155;
            color: #f8fafc;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            background-color: #ffffff;
        }

        .dark .modal-header {
            border-bottom-color: #1e293b;
            background-color: #0f172a;
        }

        .modal-title {
            margin-bottom: 0;
            line-height: 1.5;
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .dark .modal-title {
            color: #f8fafc;
        }

        .btn-close {
            box-sizing: content-box;
            width: 0.875rem;
            height: 0.875rem;
            padding: 0.35rem;
            color: #64748b;
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%2364748b'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/0.875rem auto no-repeat;
            border: 0;
            border-radius: 0.375rem;
            opacity: 0.6;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-close:hover {
            opacity: 1;
            transform: scale(1.1);
            background-color: #f1f5f9;
        }

        .dark .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .dark .btn-close:hover {
            background-color: #1e293b;
        }

        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 1.25rem;
            max-height: calc(85vh - 75px);
            overflow-y: auto;
        }

        .modal-footer {
            display: flex;
            flex-wrap: wrap;
            flex-shrink: 0;
            align-items: center;
            justify-content: flex-end;
            padding: 0.75rem 1.25rem;
            border-top: 1px solid #f1f5f9;
            background-color: #f8fafc;
            gap: 0.5rem;
        }

        .dark .modal-footer {
            border-top-color: #1e293b;
            background-color: #0b1120;
        }

        /* ==========================================================================
           LEGACY TABLES & TABS (BOOTSTRAP COMPATIBILITY FOR MODAL CONTENT)
           ========================================================================== */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0.5rem;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
            font-size: 0.75rem;
        }

        .table th, .table td {
            padding: 0.5rem 0.75rem;
            vertical-align: middle;
            border: 1px solid #e2e8f0;
        }

        .dark .table th, .dark .table td {
            border-color: #334155;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(248, 250, 252, 0.7);
        }

        .dark .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(15, 23, 42, 0.4);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(241, 245, 249, 0.9);
        }

        .dark .table-hover tbody tr:hover {
            background-color: rgba(30, 41, 59, 0.7);
        }

        .table-dark, thead.table-dark {
            background-color: #1e3a8a !important;
            color: #ffffff !important;
        }

        .table-dark th, thead.table-dark th {
            background-color: #1e3a8a !important;
            color: #ffffff !important;
            font-weight: 700;
            border-color: #172554 !important;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tfoot.table-dark th, tfoot.table-dark td {
            background-color: #1e3a8a !important;
            color: #ffffff !important;
            font-weight: 700;
            border-color: #172554 !important;
        }

        .text-end { text-align: right !important; }
        .text-center { text-align: center !important; }
        .text-start { text-align: left !important; }
        .align-middle { vertical-align: middle !important; }

        /* Modern Tabs inside Modal */
        .nav-align-top, div.nav-tabs {
            display: block !important;
            width: 100% !important;
            border-bottom: none !important;
        }

        ul.nav-tabs, nav.nav-tabs {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            border-bottom: 2px solid #e2e8f0 !important;
            gap: 0.25rem !important;
            margin-bottom: 1rem !important;
            padding: 0 !important;
            list-style: none !important;
            width: 100% !important;
        }

        .dark .nav-tabs {
            border-bottom-color: #334155;
        }

        .nav-tabs .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            border-radius: 0.375rem 0.375rem 0 0;
            transition: all 0.15s ease;
        }

        .nav-tabs .nav-link:hover {
            color: #2563eb;
        }

        .nav-tabs .nav-link.active {
            color: #2563eb !important;
            border-bottom: 2px solid #2563eb !important;
            background-color: transparent !important;
        }

        .dark .nav-tabs .nav-link.active {
            color: #60a5fa !important;
            border-bottom-color: #60a5fa !important;
        }

        .tab-content > .tab-pane {
            display: none;
        }

        .tab-content > .active {
            display: block;
        }

        /* ==========================================================================
           SWEETALERT2 MODERN V2 REDESIGN (WARNING, ERROR, SUCCESS, INFO)
           ========================================================================== */
        body.swal2-height-auto {
            height: 100vh !important;
        }
        .swal2-container {
            z-index: 99999 !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
            background: rgba(15, 23, 42, 0.45) !important;
        }
        .swal2-container.swal2-top-end,
        .swal2-container.swal2-top-start,
        .swal2-container.swal2-bottom-end,
        .swal2-container.swal2-bottom-start {
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            background: transparent !important;
        }

        /* Modern Popup Card */
        .swal2-popup:not(.v2-toast-card) {
            border-radius: 1rem !important; /* rounded-2xl (16px) */
            padding: 1.75rem 1.5rem !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
            width: 100% !important;
            max-width: 25rem !important;
            font-family: inherit !important;
        }
        .swal2-popup.swal2-show:not(.v2-toast-card) {
            animation: swal2ModernIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .swal2-popup.swal2-hide:not(.v2-toast-card) {
            animation: swal2ModernOut 0.15s ease-in forwards !important;
        }
        .swal2-container.swal2-backdrop-hide {
            animation: swal2BackdropOut 0.15s ease-in forwards !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) {
            background: #0f172a !important;
            border-color: #1e293b !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6) !important;
            color: #f8fafc !important;
        }
        @keyframes swal2ModernIn {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        @keyframes swal2ModernOut {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform: scale(0.95);
            }
        }
        @keyframes swal2BackdropOut {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }

        /* Title & Content Typography */
        .swal2-popup:not(.v2-toast-card) .swal2-title {
            font-size: 1.125rem !important; /* 18px */
            font-weight: 800 !important;
            color: #0f172a !important;
            padding: 0 !important;
            margin: 0.75rem 0 0.35rem 0 !important;
            line-height: 1.35 !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-title {
            color: #f8fafc !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-html-container {
            font-size: 0.8125rem !important; /* 13px */
            font-weight: 500 !important;
            color: #64748b !important; /* slate-500 */
            margin: 0.25rem 0 0 0 !important;
            padding: 0 !important;
            line-height: 1.5 !important;
            overflow: visible !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-html-container {
            color: #94a3b8 !important; /* slate-400 */
        }

        /* SweetAlert2 Modern Rounded Minimalist Icons */
        .swal2-popup:not(.v2-toast-card) .swal2-icon {
            font-size: 0.75rem !important;
            width: 3.75rem !important;
            height: 3.75rem !important;
            margin: 0.25rem auto 0.75rem auto !important;
            border-width: 3px !important;
            border-style: solid !important;
            box-sizing: border-box !important;
            position: relative !important;
        }

        /* 1. SUCCESS ICON */
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success {
            border-color: #10b981 !important; /* emerald-500 */
            background-color: #ecfdf5 !important; /* emerald-50 */
            color: #059669 !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success {
            background-color: rgba(16, 185, 129, 0.12) !important;
            border-color: #10b981 !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success .swal2-success-ring {
            display: none !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success .swal2-success-circular-line-left,
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success .swal2-success-circular-line-right,
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success .swal2-success-fix {
            display: none !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: #059669 !important;
            height: 3px !important;
            border-radius: 9999px !important;
            position: absolute !important;
            z-index: 2 !important;
            margin: 0 !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success .swal2-success-line-tip {
            width: 0.85rem !important;
            top: 1.95rem !important;
            left: 0.95rem !important;
            transform: rotate(45deg) !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success .swal2-success-line-long {
            width: 1.45rem !important;
            top: 1.65rem !important;
            left: 1.35rem !important;
            right: auto !important;
            transform: rotate(-45deg) !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-success.swal2-animate-success-icon [class^='swal2-success-line'] {
            animation: swal2ModernSuccessLine 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        @keyframes swal2ModernSuccessLine {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* 2. WARNING ICON */
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-warning {
            border-color: #f59e0b !important; /* amber-500 */
            background-color: #fffbeb !important; /* amber-50 */
            color: #d97706 !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-warning {
            background-color: rgba(245, 158, 11, 0.12) !important;
            border-color: #f59e0b !important;
            color: #fbbf24 !important;
        }

        /* 3. ERROR ICON */
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-error {
            border-color: #f43f5e !important; /* rose-500 */
            background-color: #fff1f2 !important; /* rose-50 */
            color: #e11d48 !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-error {
            background-color: rgba(244, 63, 94, 0.12) !important;
            border-color: #f43f5e !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-error .swal2-x-mark {
            position: absolute !important;
            inset: 0 !important;
            display: block !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            top: 0 !important;
            left: 0 !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            width: 1.625rem !important;
            height: 3px !important;
            border-radius: 9999px !important;
            background-color: #e11d48 !important;
            margin: 0 !important;
            right: auto !important;
            bottom: auto !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-error .swal2-x-mark-line-left {
            transform: translate(-50%, -50%) rotate(45deg) !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-error .swal2-x-mark-line-right {
            transform: translate(-50%, -50%) rotate(-45deg) !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-error.swal2-animate-error-icon .swal2-x-mark {
            animation: swal2ModernXMark 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        @keyframes swal2ModernXMark {
            0% { transform: scale(0.4); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* 4. INFO & QUESTION ICON */
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-info {
            border-color: #284c9a !important; /* Pacific Navy */
            background-color: #eff6ff !important;
            color: #284c9a !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-info {
            background-color: rgba(40, 76, 154, 0.15) !important;
            border-color: #3b82f6 !important;
            color: #60a5fa !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-question {
            border-color: #6366f1 !important;
            background-color: #eef2ff !important;
            color: #4f46e5 !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-question {
            background-color: rgba(99, 102, 241, 0.15) !important;
            border-color: #6366f1 !important;
            color: #818cf8 !important;
        }

        /* Text Content in Icons (Warning, Info, Question) */
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-warning .swal2-icon-content,
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-info .swal2-icon-content,
        .swal2-popup:not(.v2-toast-card) .swal2-icon.swal2-question .swal2-icon-content {
            font-size: 1.875rem !important;
            font-weight: 800 !important;
            font-family: inherit !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            height: 100% !important;
            line-height: 1 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Actions Bar & Pacific Navy Buttons */
        .swal2-popup:not(.v2-toast-card) .swal2-actions {
            margin-top: 1.25rem !important;
            padding-top: 0.875rem !important;
            border-top: 1px solid #f1f5f9 !important;
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
            gap: 0.5rem !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-actions {
            border-top-color: #1e293b !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-confirm {
            height: 36px !important;
            min-width: 90px !important;
            padding: 0 1.25rem !important;
            border-radius: 0.5rem !important; /* rounded-lg (8px) */
            background-color: #284c9a !important; /* Pacific Navy */
            color: #ffffff !important;
            font-size: 0.75rem !important; /* 12px */
            font-weight: 700 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            border: none !important;
            outline: none !important;
            transition: all 0.15s ease-in-out !important;
            cursor: pointer !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-confirm:not([style*="display: none"]):not(.swal2-hidden) {
            display: inline-flex !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-confirm[style*="display: none"],
        .swal2-popup:not(.v2-toast-card) .swal2-confirm.swal2-hidden {
            display: none !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-confirm:hover {
            background-color: #1e3a78 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-confirm:active {
            transform: scale(0.98) !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-deny {
            height: 36px !important;
            min-width: 90px !important;
            padding: 0 1.25rem !important;
            border-radius: 0.5rem !important; /* rounded-lg (8px) */
            background-color: #64748b !important; /* slate-500 */
            color: #ffffff !important;
            font-size: 0.75rem !important; /* 12px */
            font-weight: 700 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            border: none !important;
            outline: none !important;
            transition: all 0.15s ease-in-out !important;
            cursor: pointer !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-deny:not([style*="display: none"]):not(.swal2-hidden) {
            display: inline-flex !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-deny[style*="display: none"],
        .swal2-popup:not(.v2-toast-card) .swal2-deny.swal2-hidden {
            display: none !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-deny:hover {
            background-color: #475569 !important; /* slate-600 */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-deny:active {
            transform: scale(0.98) !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-deny {
            background-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-deny:hover {
            background-color: #475569 !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-cancel {
            height: 36px !important;
            min-width: 80px !important;
            padding: 0 1rem !important;
            border-radius: 0.5rem !important;
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            border: 1px solid #e2e8f0 !important;
            outline: none !important;
            transition: all 0.15s ease-in-out !important;
            cursor: pointer !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-cancel:not([style*="display: none"]):not(.swal2-hidden) {
            display: inline-flex !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-cancel[style*="display: none"],
        .swal2-popup:not(.v2-toast-card) .swal2-cancel.swal2-hidden {
            display: none !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-cancel {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-cancel:hover {
            background-color: #e2e8f0 !important;
            color: #1e293b !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-cancel:hover {
            background-color: #334155 !important;
            color: #f8fafc !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-close {
            border-radius: 0.5rem !important;
            color: #94a3b8 !important;
            font-family: inherit !important;
            transition: all 0.15s ease !important;
            top: 10px !important;
            right: 10px !important;
        }
        .swal2-popup:not(.v2-toast-card) .swal2-close:hover {
            color: #0f172a !important;
            background-color: #f1f5f9 !important;
        }
        .dark .swal2-popup:not(.v2-toast-card) .swal2-close:hover {
            color: #f8fafc !important;
            background-color: #1e293b !important;
        }

        /* Error / Destructive popup confirm button highlight */
        .swal2-popup:not(.v2-toast-card).v2-delete-modal .swal2-confirm,
        .swal2-popup:not(.v2-toast-card):has(.swal2-icon.swal2-error) .swal2-confirm {
            background-color: #e11d48 !important; /* rose-600 */
        }
        .swal2-popup:not(.v2-toast-card).v2-delete-modal .swal2-confirm:hover,
        .swal2-popup:not(.v2-toast-card):has(.swal2-icon.swal2-error) .swal2-confirm:hover {
            background-color: #be123c !important;
        }

        /* ==========================================================================
           CLEAN SOLID V2 TOAST ALERT (ZERO WHITE LINES - ANIMATED CHECKMARK ONLY)
           ========================================================================== */
        .swal2-container.swal2-top-end {
            top: 1.25rem !important;
            right: 1.25rem !important;
            z-index: 99999 !important;
            background: transparent !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
        .swal2-popup.v2-toast-card {
            border-radius: 12px !important;
            padding: 0.85rem 1.15rem !important;
            width: auto !important;
            min-width: 300px !important;
            max-width: 420px !important;
            position: relative !important;
            overflow: hidden !important;
            border: 0 !important;
            outline: 0 !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.2) !important;
            font-family: inherit !important;
            cursor: default;
        }
        .swal2-popup.v2-toast-card * {
            border: 0 !important;
            outline: 0 !important;
        }
        .swal2-popup.v2-toast-card.swal2-show {
            animation: v2ToastFadeIn 0.2s ease-out !important;
        }
        .swal2-popup.v2-toast-card.swal2-hide {
            animation: v2ToastFadeOut 0.2s ease-in forwards !important;
        }
        @keyframes v2ToastFadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes v2ToastFadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-6px);
            }
        }

        /* Solid Colors - No Gradients, No Borders */
        .v2-toast-card.v2-type-success {
            background: #059669 !important;
            color: #ffffff !important;
        }
        .v2-toast-card.v2-type-error {
            background: #e11d48 !important;
            color: #ffffff !important;
        }
        .v2-toast-card.v2-type-warning {
            background: #d97706 !important;
            color: #ffffff !important;
        }
        .v2-toast-card.v2-type-info {
            background: #284c9a !important;
            color: #ffffff !important;
        }

        /* Animated Checkmark Drawing Only */
        .v2-check-path {
            stroke-dasharray: 32;
            stroke-dashoffset: 32;
            animation: v2DrawCheck 0.45s 0.1s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        @keyframes v2DrawCheck {
            to {
                stroke-dashoffset: 0;
            }
        }

        .swal2-popup.v2-toast-card .swal2-html-container {
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            text-align: left !important;
        }
        .swal2-popup.v2-toast-card .swal2-close {
            position: static !important;
            margin-left: 0.5rem !important;
            padding: 0 !important;
            width: 1.25rem !important;
            height: 1.25rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 1.15rem !important;
            color: rgba(255, 255, 255, 0.7) !important;
            transition: color 0.15s ease !important;
        }
        .swal2-popup.v2-toast-card .swal2-close:hover {
            color: #ffffff !important;
        }
        .swal2-popup.v2-toast-card .swal2-actions {
            display: none !important;
            border: 0 !important;
            height: 0 !important;
        }
        .swal2-popup.v2-toast-card .swal2-timer-progress-bar-container {
            position: absolute !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            height: 4px !important;
            background: rgba(0, 0, 0, 0.18) !important;
            overflow: hidden !important;
            border-radius: 0 0 12px 12px !important;
            z-index: 10 !important;
            display: block !important;
        }
        .swal2-popup.v2-toast-card .swal2-timer-progress-bar {
            height: 100% !important;
            background: rgba(255, 255, 255, 0.65) !important;
            display: block !important;
        }

        /* Legacy Bootstrap Badge Danger Support in V2 */
        .badge.bg-danger {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%) !important;
            color: #ffffff !important;
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
            min-width: 1.25rem !important;
            height: 1.15rem !important;
            padding: 0 0.35rem !important;
            border-radius: 9999px !important;
            line-height: 1 !important;
            box-shadow: 0 2px 6px rgba(225, 29, 72, 0.4) !important;
        }

        /* ==========================================================================
           DATATABLES V2 TAILWIND DESIGN SYSTEM OVERRIDES
           ========================================================================== */
        .dataTables_wrapper {
            font-size: 0.75rem; /* text-xs */
            color: #475569;
            width: 100%;
        }
        .dataTables_wrapper:after {
            content: "";
            display: table;
            clear: both;
        }
        .dataTables_wrapper .dataTables_length {
            float: left;
            margin-bottom: 0.875rem;
        }
        .dataTables_wrapper .dataTables_length label {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
        }
        .dark .dataTables_wrapper .dataTables_length label {
            color: #94a3b8;
        }
        .dataTables_wrapper .dataTables_length select {
            height: 34px;
            padding: 0.25rem 1.75rem 0.25rem 0.625rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #1e293b;
            outline: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .dark .dataTables_wrapper .dataTables_length select {
            border-color: #334155;
            background-color: #0f172a;
            color: #f1f5f9;
        }
        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #284c9a;
            box-shadow: 0 0 0 2px rgba(40, 76, 154, 0.2);
        }
        .dataTables_wrapper .dataTables_filter {
            float: right;
            margin-bottom: 0.875rem;
        }
        .dataTables_wrapper .dataTables_filter label {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
        }
        .dark .dataTables_wrapper .dataTables_filter label {
            color: #94a3b8;
        }
        .dataTables_wrapper .dataTables_filter input {
            height: 34px;
            width: 240px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #1e293b;
            outline: none;
            transition: all 0.15s ease;
        }
        .dark .dataTables_wrapper .dataTables_filter input {
            border-color: #334155;
            background-color: #0f172a;
            color: #f1f5f9;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #284c9a;
            box-shadow: 0 0 0 2px rgba(40, 76, 154, 0.2);
            width: 280px;
        }
        .dataTables_wrapper .dataTables_info {
            float: left;
            padding-top: 0.875rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
        }
        .dark .dataTables_wrapper .dataTables_info {
            color: #94a3b8;
        }
        .dataTables_wrapper .dataTables_paginate {
            float: right;
            padding-top: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.5rem !important;
            min-width: 28px !important;
            height: 28px !important;
            padding: 0 0.5rem !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            color: #475569 !important;
            transition: all 0.15s ease;
            cursor: pointer;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-color: #334155 !important;
            background: #0f172a !important;
            color: #94a3b8 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #284c9a !important;
            color: #ffffff !important;
            border-color: #284c9a !important;
            font-weight: 700 !important;
            box-shadow: 0 1px 2px rgba(40, 76, 154, 0.3) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important;
            color: #1e293b !important;
            border-color: #cbd5e1 !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #1e293b !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            opacity: 0.35 !important;
            cursor: not-allowed !important;
            background: transparent !important;
            border-color: #e2e8f0 !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            border-color: #1e293b !important;
        }
        .pilihpelanggan {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 0.375rem;
            background-color: #eff6ff;
            color: #284c9a;
            transition: all 0.15s ease;
        }
        .dark .pilihpelanggan {
            background-color: rgba(30, 58, 138, 0.4);
            color: #93c5fd;
        }
        .pilihpelanggan:hover {
            background-color: #284c9a;
            color: #ffffff;
        }

        /* Cart Table #tabelproduk styling */
        #tabelproduk {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        #tabelproduk th {
            vertical-align: middle !important;
        }
        #tabelproduk td {
            padding: 6px 12px !important; /* py-1.5 px-3 standard */
            vertical-align: middle !important;
            font-size: 12px !important;
        }
        #tabelproduk td:first-child,
        #tabelproduk th:first-child {
            padding-left: 14px !important;
        }
        #tabelproduk td:last-child,
        #tabelproduk th:last-child {
            padding-right: 14px !important;
        }

        /* Promotional item row styling (orange background) */
        #tabelproduk tr.row-promosi,
        #tabelproduk tr.bg-warning {
            background-color: #f97316 !important; /* Tailwind orange-500 */
            color: #ffffff !important;
        }
        #tabelproduk tr.row-promosi:hover,
        #tabelproduk tr.bg-warning:hover {
            background-color: #ea580c !important; /* Tailwind orange-600 */
        }
        #tabelproduk tr.row-promosi > td,
        #tabelproduk tr.bg-warning > td {
            color: #ffffff !important;
        }

        /* Action buttons on promotional row: Edit button */
        #tabelproduk tr.row-promosi a.edit,
        #tabelproduk tr.bg-warning a.edit,
        #tabelproduk tr.row-promosi .btn-edit-item,
        #tabelproduk tr.bg-warning .btn-edit-item {
            background-color: #ffffff !important;
            color: #059669 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1) !important;
        }
        #tabelproduk tr.row-promosi a.edit svg,
        #tabelproduk tr.bg-warning a.edit svg,
        #tabelproduk tr.row-promosi .btn-edit-item svg,
        #tabelproduk tr.bg-warning .btn-edit-item svg,
        #tabelproduk tr.row-promosi a.edit i,
        #tabelproduk tr.bg-warning a.edit i,
        #tabelproduk tr.row-promosi .btn-edit-item i,
        #tabelproduk tr.bg-warning .btn-edit-item i {
            color: #059669 !important;
            stroke: #059669 !important;
        }
        #tabelproduk tr.row-promosi a.edit:hover,
        #tabelproduk tr.bg-warning a.edit:hover,
        #tabelproduk tr.row-promosi .btn-edit-item:hover,
        #tabelproduk tr.bg-warning .btn-edit-item:hover {
            background-color: #059669 !important;
            color: #ffffff !important;
        }
        #tabelproduk tr.row-promosi a.edit:hover svg,
        #tabelproduk tr.bg-warning a.edit:hover svg,
        #tabelproduk tr.row-promosi .btn-edit-item:hover svg,
        #tabelproduk tr.bg-warning .btn-edit-item:hover svg {
            color: #ffffff !important;
            stroke: #ffffff !important;
        }

        /* Action buttons on promotional row: Delete button */
        #tabelproduk tr.row-promosi a.delete,
        #tabelproduk tr.bg-warning a.delete,
        #tabelproduk tr.row-promosi .btn-delete-item,
        #tabelproduk tr.bg-warning .btn-delete-item {
            background-color: #ffffff !important;
            color: #e11d48 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1) !important;
        }
        #tabelproduk tr.row-promosi a.delete svg,
        #tabelproduk tr.bg-warning a.delete svg,
        #tabelproduk tr.row-promosi .btn-delete-item svg,
        #tabelproduk tr.bg-warning .btn-delete-item svg,
        #tabelproduk tr.row-promosi a.delete i,
        #tabelproduk tr.bg-warning a.delete i,
        #tabelproduk tr.row-promosi .btn-delete-item i,
        #tabelproduk tr.bg-warning .btn-delete-item i {
            color: #e11d48 !important;
            stroke: #e11d48 !important;
        }
        #tabelproduk tr.row-promosi a.delete:hover,
        #tabelproduk tr.bg-warning a.delete:hover,
        #tabelproduk tr.row-promosi .btn-delete-item:hover,
        #tabelproduk tr.bg-warning .btn-delete-item:hover {
            background-color: #e11d48 !important;
            color: #ffffff !important;
        }
        #tabelproduk tr.row-promosi a.delete:hover svg,
        #tabelproduk tr.bg-warning a.delete:hover svg,
        #tabelproduk tr.row-promosi .btn-delete-item:hover svg,
        #tabelproduk tr.bg-warning .btn-delete-item:hover svg {
            color: #ffffff !important;
            stroke: #ffffff !important;
        }
    </style>

    @yield('style')
    @stack('styles')
</head>
<body class="h-screen w-screen overflow-hidden text-slate-800 dark:text-slate-100 antialiased bg-[#f8fafc] dark:bg-[#0b1120] transition-colors duration-200">

    <!-- Mobile Backdrop Overlay -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    <!-- Edge-to-Edge True Full Screen Flex Layout -->
    <div class="h-full w-full flex overflow-hidden">
        
        <!-- SIDEBAR -->
        @include('layouts.sidebar')

        <!-- MAIN VIEWPORT WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <!-- TOP NAVBAR -->
            @include('layouts.navbar')

            <!-- SCROLLABLE PAGE BODY -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 flex flex-col justify-between">
                
                <div class="flex-1">
                    <!-- Impersonation Notice Banner -->
                    @if (session()->has('impersonated_by'))
                        <div class="mb-5 p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-center justify-between text-xs text-amber-800 dark:text-amber-200 shadow-sm">
                            <div class="flex items-center gap-2.5">
                                <i data-lucide="eye" class="w-4 h-4 text-amber-600 shrink-0"></i>
                                <div>
                                    Anda sedang melihat sistem sebagai <strong>{{ Auth::user()->name }}</strong> (Role: {{ Auth::user()->roles->pluck('name')->implode(', ') }}).
                                </div>
                            </div>
                            @if(Route::has('users.stop-impersonating'))
                            <a href="{{ route('users.stop-impersonating') }}" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 active:scale-95 text-white font-bold text-xs shadow-xs transition shrink-0">
                                Kembali ke Admin
                            </a>
                            @endif
                        </div>
                    @endif

                    <!-- Content Slot -->
                    @yield('content')
                </div>

                <!-- Simple Footer -->
                <footer class="mt-8 pt-6 pb-2 border-t border-slate-200/80 dark:border-slate-800/80 text-center text-xs text-slate-400 dark:text-slate-500">
                    &copy; {{ date('Y') }} Pacific Portal System • Versi 2.0 (Tailwind)
                </footer>

            </main>

        </div>

    </div>

    <!-- Mobile Bottom Navigation V2 -->
    @hasanyrole(['gm marketing', 'regional sales manager', 'sales marketing manager'])
        <nav class="fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200/80 dark:border-slate-800 md:hidden">
            <div class="grid grid-cols-5 h-14 max-w-md mx-auto">
                <a href="#" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-[#284c9a] dark:hover:text-blue-400 transition">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span class="text-[10px] font-semibold">Profil</span>
                </a>
                <a href="{{ route('aktifitassmm.index') }}" class="flex flex-col items-center justify-center gap-0.5 {{ request()->is('aktifitassmm*') ? 'text-[#284c9a] dark:text-blue-400' : 'text-slate-400 hover:text-[#284c9a] dark:hover:text-blue-400' }} transition">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span class="text-[10px] font-semibold">Aktivitas</span>
                </a>
                <a href="/dashboard" class="flex flex-col items-center justify-center gap-0.5 {{ request()->is('dashboard') ? 'text-[#284c9a] dark:text-blue-400' : 'text-slate-400 hover:text-[#284c9a] dark:hover:text-blue-400' }} transition">
                    <div class="w-8 h-8 rounded-full {{ request()->is('dashboard') ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800' }} flex items-center justify-center">
                        <i data-lucide="home" class="w-4 h-4"></i>
                    </div>
                </a>
                <a href="#" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-[#284c9a] dark:hover:text-blue-400 transition">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <span class="text-[10px] font-semibold">Pesan</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-[#284c9a] dark:hover:text-blue-400 transition">
                    <i data-lucide="help-circle" class="w-4 h-4"></i>
                    <span class="text-[10px] font-semibold">Bantuan</span>
                </a>
            </div>
        </nav>
    @endhasanyrole

    <!-- Core Scripts Initializer -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                lucide.createIcons();
            }

            // Mobile Sidebar drawer handlers
            const sidebar = document.getElementById('layout-menu-v2') || document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const openBtn = document.getElementById('openMobileSidebarBtn');
            const closeBtn = document.getElementById('closeMobileSidebarBtn');

            function openSidebar() {
                if (sidebar && backdrop) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                }
            }

            function closeSidebar() {
                if (sidebar && backdrop) {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }

            if (openBtn) openBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Dark Mode handlers
            const navThemeBtn = document.getElementById('navThemeToggleBtn');
            if (navThemeBtn) {
                navThemeBtn.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                });
            }
        });
    </script>

    <!-- Popper & Bootstrap Core JS for existing modal, tooltip & dropdown compatibility -->
    <script src="{{ asset('/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('/assets/vendor/js/bootstrap.js') }}"></script>

    <!-- Modal & Tabs Bridge Script for V2 -->
    <script>
        (function($) {
            if (!$) return;

            // Universal modal bridge
            $.fn.modal = function(action, arg) {
                return this.each(function() {
                    var modalEl = this;
                    var bsModal = window.bootstrap && window.bootstrap.Modal
                        ? bootstrap.Modal.getOrCreateInstance(modalEl, {
                            backdrop: $(modalEl).data('bs-backdrop') || 'static',
                            keyboard: $(modalEl).data('bs-keyboard') !== false
                        })
                        : null;

                    if (typeof action === 'string') {
                        if (action === 'show') {
                            if (bsModal) {
                                bsModal.show();
                            } else {
                                $(modalEl).addClass('show').css('display', 'flex');
                                $('body').addClass('modal-open');
                                if (!$('.modal-backdrop').length) {
                                    $('body').append('<div class="modal-backdrop fade show"></div>');
                                }
                            }
                        } else if (action === 'hide') {
                            if (bsModal) {
                                bsModal.hide();
                            } else {
                                $(modalEl).removeClass('show').css('display', 'none');
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                            }
                        }
                    }
                });
            };

            // Global Close button handler
            $(document).on('click', '[data-bs-dismiss="modal"], [data-dismiss="modal"], .btn-close', function(e) {
                var $modal = $(this).closest('.modal');
                if ($modal.length) {
                    $modal.modal('hide');
                }
            });

            // Global Tab switching handler for AJAX-loaded Bootstrap tabs
            $(document).on('click', '[data-bs-toggle="tab"], [data-toggle="tab"]', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var target = $btn.data('bs-target') || $btn.data('target') || $btn.attr('href');
                if (target) {
                    $btn.closest('.nav-tabs').find('.nav-link').removeClass('active').attr('aria-selected', 'false');
                    $btn.addClass('active').attr('aria-selected', 'true');
                    
                    var $tabContainer = $btn.closest('.nav-align-top').find('.tab-content');
                    if (!$tabContainer.length) $tabContainer = $('.tab-content');
                    $tabContainer.find('.tab-pane').removeClass('show active').css('display', 'none');
                    $(target).addClass('show active').css('display', 'block');
                }
            });

            // Global Modern Confirm Delete for V2
            window.confirmDelete = function(formOrCallback, options) {
                const opts = Object.assign({
                    title: "Hapus Data Ini?",
                    text: "Data yang telah dihapus tidak dapat dipulihkan kembali.",
                    confirmText: "Ya, Hapus Data",
                    cancelText: "Batal",
                    itemName: ""
                }, options || {});

                const htmlContent = `
                    <div class="flex flex-col items-center text-center">
                        <div class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-500 dark:text-rose-400 flex items-center justify-center mb-3 ring-8 ring-rose-50/50 dark:ring-rose-950/30">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider mb-1">${opts.title}</h3>
                        ${opts.itemName ? `<p class="text-xs font-bold text-rose-600 dark:text-rose-400 font-mono-num mb-1.5 px-2.5 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/50 border border-rose-100 dark:border-rose-900 inline-block">${opts.itemName}</p>` : ''}
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed max-w-[280px]">${opts.text}</p>
                    </div>
                `;

                if (typeof Swal === 'undefined') {
                    if (confirm(opts.title + "\n" + opts.text)) {
                        if (typeof formOrCallback === 'function') formOrCallback();
                        else if (formOrCallback) $(formOrCallback).submit();
                    }
                    return;
                }

                return Swal.fire({
                    html: htmlContent,
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: opts.confirmText,
                    cancelButtonText: opts.cancelText,
                    buttonsStyling: false,
                    reverseButtons: true,
                    customClass: {
                        popup: 'v2-delete-modal',
                        htmlContainer: 'swal2-html-container',
                        actions: 'swal2-actions',
                        confirmButton: 'inline-flex items-center justify-center h-[34px] px-4 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-xs cursor-pointer',
                        cancelButton: 'inline-flex items-center justify-center h-[34px] px-4 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold transition cursor-pointer'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (typeof formOrCallback === 'function') {
                            formOrCallback();
                        } else if (formOrCallback) {
                            var $form = $(formOrCallback);
                            if ($form.length) {
                                $form.data('confirmed', true);
                                $form[0].submit();
                            }
                        }
                    }
                    return result;
                });
            };

            // Global Delegated Handler for Delete Buttons
            $(document).on('click', '.btn-delete, .delete-confirm', function(e) {
                var $btn = $(this);
                var $form = $btn.closest('form');
                if (!$form.length) return;
                
                if ($form.data('confirmed')) {
                    return true;
                }
                
                e.preventDefault();
                var itemName = $btn.data('name') || $btn.data('kode') || $btn.attr('title') || '';
                window.confirmDelete($form, {
                    title: "Hapus Data Ini?",
                    text: "Data yang telah dihapus tidak dapat dipulihkan kembali. Anda yakin ingin melanjutkan?",
                    itemName: itemName
                });
            });
            // Universal Clean Toast Notification for V2 (Solid Color - Zero White Lines - Animated Checkmark Only)
            window.showToast = function(type, message, customTitle) {
                type = (type || 'success').toLowerCase();
                let iconSvg, tagText;

                if (type === 'success') {
                    tagText = customTitle || 'Berhasil';
                    iconSvg = `
                        <svg class="w-6 h-6 text-white shrink-0" viewBox="0 0 24 24" fill="none">
                            <path class="v2-check-path" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    `;
                } else if (type === 'error') {
                    tagText = customTitle || 'Gagal';
                    iconSvg = `
                        <svg class="w-6 h-6 text-white shrink-0" viewBox="0 0 24 24" fill="none">
                            <path class="v2-check-path" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    `;
                } else if (type === 'warning') {
                    tagText = customTitle || 'Peringatan';
                    iconSvg = `
                        <svg class="w-6 h-6 text-white shrink-0" viewBox="0 0 24 24" fill="none">
                            <path class="v2-check-path" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" d="M12 8v5m0 3.5h.01" />
                        </svg>
                    `;
                } else {
                    tagText = customTitle || 'Informasi';
                    iconSvg = `
                        <svg class="w-6 h-6 text-white shrink-0" viewBox="0 0 24 24" fill="none">
                            <path class="v2-check-path" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01" />
                        </svg>
                    `;
                }

                const htmlContent = `
                    <div class="flex items-center gap-3 w-full pr-1">
                        <div class="shrink-0 flex items-center justify-center">
                            ${iconSvg}
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <div class="text-[10px] font-black uppercase tracking-wider text-white/75 leading-tight">${tagText}</div>
                            <div class="text-xs font-bold text-white mt-0.5 leading-snug">${message}</div>
                        </div>
                    </div>
                `;

                if (typeof Swal === 'undefined') {
                    console.log(`[Toast ${type}]`, message);
                    return;
                }

                const duration = type === 'error' ? 4500 : 3500;

                const swalInstance = Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: duration,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'swal2-show'
                    },
                    hideClass: {
                        popup: 'swal2-hide'
                    },
                    html: htmlContent,
                    customClass: {
                        popup: `v2-toast-card v2-type-${type}`,
                        htmlContainer: 'p-0 m-0 w-full',
                        closeButton: 'text-white/70 hover:text-white transition focus:outline-hidden text-sm shrink-0'
                    },
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', () => Swal.stopTimer());
                        toast.addEventListener('mouseleave', () => Swal.resumeTimer());
                    }
                });

                // Safety self-dismissal guarantee: fires when progress bar finishes
                setTimeout(() => {
                    try {
                        if (typeof Swal !== 'undefined' && typeof Swal.isVisible === 'function' && Swal.isVisible()) {
                            // If timer is elapsed (no time left), trigger close
                            if (typeof Swal.getTimerLeft === 'function' && !Swal.getTimerLeft()) {
                                Swal.close();
                            }
                        }
                        // Secondary cleanup fallback if DOM element hangs after close
                        setTimeout(() => {
                            const stuckPopups = document.querySelectorAll('.v2-toast-card');
                            stuckPopups.forEach(el => {
                                const container = el.closest('.swal2-container');
                                if (container && (!Swal.isVisible() || (typeof Swal.getTimerLeft === 'function' && !Swal.getTimerLeft()))) {
                                    container.remove();
                                }
                            });
                        }, 250);
                    } catch (e) {}
                }, duration + 50);

                return swalInstance;
            };

            // Toastr Compatibility Bridge for V2
            window.toastr = window.toastr || {
                success: function(msg, title) { window.showToast('success', msg, title); },
                error: function(msg, title) { window.showToast('error', msg, title); },
                warning: function(msg, title) { window.showToast('warning', msg, title); },
                info: function(msg, title) { window.showToast('info', msg, title); }
            };

            // Global Double-Submit Protector & Button Loading Helper for V2
            window.setButtonSubmitting = function($btn, loadingText) {
                if (!$btn || !$btn.length) return;
                loadingText = loadingText || 'Menyimpan...';
                if (!$btn.data('original-html')) {
                    $btn.data('original-html', $btn.html());
                }
                $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
                $btn.html(`
                    <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>${loadingText}</span>
                `);
            };

            window.resetButtonSubmitting = function($btn) {
                if (!$btn || !$btn.length) return;
                const originalHtml = $btn.data('original-html');
                if (originalHtml) {
                    $btn.html(originalHtml);
                }
                $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
            };

            // Global Submit Listener: Auto disable & show spinner on valid submit
            $(document).on('submit', 'form:not(.deleteform, .no-auto-loading)', function(e) {
                if (!e.isDefaultPrevented()) {
                    const $submitBtn = $(this).find('button[type="submit"]:not([disabled])').first();
                    if ($submitBtn.length) {
                        const loadingText = $submitBtn.attr('data-loading-text') || 'Menyimpan...';
                        window.setButtonSubmitting($submitBtn, loadingText);
                    }
                }
            });

            // Global Flatpickr Auto-Init for V2 .flatpickr-date inputs
            if (typeof flatpickr !== 'undefined') {
                if (flatpickr.l10ns && flatpickr.l10ns.id) {
                    flatpickr.localize(flatpickr.l10ns.id);
                }
                $('.flatpickr-date').each(function() {
                    if (!this._flatpickr) {
                        flatpickr(this, {
                            dateFormat: 'Y-m-d',
                            allowInput: false
                        });
                    }
                });
            }

            // Auto-close any open Select2 when modal is dismissed
            $(document).on('click', '.btn-close-modal, [data-bs-dismiss="modal"]', function() {
                if ($.fn.select2) {
                    $('.select2-hidden-accessible').select2('close');
                }
            });

            // Prevent Bootstrap modal enforceFocus from stealing focus from Select2 search input
            document.addEventListener('focusin', function(e) {
                if (e.target && (e.target.classList.contains('select2-search__field') || e.target.closest('.select2-container'))) {
                    e.stopPropagation();
                }
            }, true);

            // Auto-align prefix icon padding for any Select2 in DOM
            function syncSelect2PrefixPadding() {
                $('.select2-container').each(function() {
                    const $c = $(this);
                    const $parent = $c.parent();
                    const hasIcon = $parent.find('.input-prefix-icon, [class*="left-0"], [data-lucide]').not('.select2-selection *').length > 0;
                    if (hasIcon) {
                        $c.find('.select2-selection--single').css('padding-left', '36px');
                    }
                });
            }
            syncSelect2PrefixPadding();
            $(document).on('select2:open select2:close change', syncSelect2PrefixPadding);
            setTimeout(syncSelect2PrefixPadding, 200);
            setTimeout(syncSelect2PrefixPadding, 800);
            $(document).ajaxComplete(function() {
                setTimeout(syncSelect2PrefixPadding, 150);
            });
        })(jQuery);

        // =========================================================================
        // GLOBAL STATIC BACKDROP & EXPLICIT CLOSE PROTECTION FOR ALL MODALS (V2)
        // =========================================================================
        (function() {
            // Helper to trigger gentle bounce/pulse feedback animation on modal dialog
            function pulseModalBox(modalEl) {
                if (!modalEl) return;
                const modalBox = modalEl.querySelector('.relative.w-full, .modal-dialog, > div');
                if (modalBox) {
                    modalBox.classList.add('scale-[1.015]', 'shadow-2xl');
                    setTimeout(function() {
                        modalBox.classList.remove('scale-[1.015]', 'shadow-2xl');
                    }, 150);
                }
            }

            // 1. Capture-phase click interceptor for backdrop clicks:
            // Intercepts click BEFORE any local jQuery/DOM outside-click listeners run
            document.addEventListener('click', function(e) {
                const target = e.target;
                if (!target) return;

                // Detect if the clicked target is the dark backdrop container itself
                const isBackdrop = (
                    (target.classList && target.classList.contains('fixed') && target.classList.contains('inset-0')) ||
                    (target.classList && target.classList.contains('modal') && !target.closest('.modal-dialog')) ||
                    (target.id && target.id.toLowerCase().includes('modal') && target.classList && target.classList.contains('fixed'))
                );

                if (isBackdrop) {
                    // STOP event from reaching any local outside-click listener that calls closeModal()
                    e.stopImmediatePropagation();
                    e.preventDefault();

                    // Provide visual feedback indicating the modal is locked
                    pulseModalBox(target);
                }
            }, true); // Use capture phase!

            // 2. Global Escape key interceptor: prevents accidental closing via keyboard
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    const visibleModals = document.querySelectorAll(
                        '.fixed.inset-0:not(.hidden), .modal.show, [id*="modal"]:not(.hidden)'
                    );
                    let intercepted = false;
                    visibleModals.forEach(function(modal) {
                        if (window.getComputedStyle(modal).display !== 'none') {
                            intercepted = true;
                            pulseModalBox(modal);
                        }
                    });

                    if (intercepted) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                    }
                }
            }, true); // Use capture phase!

            // 3. For Bootstrap modals if present: enforce static backdrop and disable keyboard dismiss
            if (typeof jQuery !== 'undefined' && jQuery.fn && jQuery.fn.modal && jQuery.fn.modal.Constructor) {
                if (jQuery.fn.modal.Constructor.Default) {
                    jQuery.fn.modal.Constructor.Default.backdrop = 'static';
                    jQuery.fn.modal.Constructor.Default.keyboard = false;
                }
            }
        })();
    </script>

    <!-- Global Helpers (SwalWarning & maskMoney) -->
    <script>
        window.SwalWarning = function(name, message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: "Oops!",
                    text: message,
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: function() {
                        if (name) {
                            $("#" + name).focus();
                        }
                    }
                });
            } else {
                alert(message);
                if (name) {
                    $("#" + name).focus();
                }
            }
        };

        $(function() {
            if ($.fn.maskMoney) {
                $('.money').maskMoney({thousands: '.', decimal: ',', precision: 0});
            }
            if (typeof easyNumberSeparator === 'function') {
                easyNumberSeparator({
                    selector: '.number-separator',
                    separator: '.',
                    decimalSeparator: ',',
                });
            }
        });
    </script>

    <!-- Global Laravel Session Flash Notifications -->
    @if ($message = Session::get('success'))
        <script>
            $(function() {
                if (typeof window.showToast === 'function') {
                    window.showToast('success', "{!! addslashes($message) !!}");
                }
            });
        </script>
    @endif

    @if ($message = Session::get('error'))
        <script>
            $(function() {
                if (typeof window.showToast === 'function') {
                    window.showToast('error', "{!! addslashes($message) !!}");
                }
            });
        </script>
    @endif

    @if ($message = Session::get('warning'))
        <script>
            $(function() {
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', "{!! addslashes($message) !!}");
                }
            });
        </script>
    @endif

    @if ($message = Session::get('info'))
        <script>
            $(function() {
                if (typeof window.showToast === 'function') {
                    window.showToast('info', "{!! addslashes($message) !!}");
                }
            });
        </script>
    @endif

    @if ($errors->any())
        @php
            $errList = implode('<br>', $errors->all());
        @endphp
        <script>
            $(function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: '{!! addslashes($errList) !!}',
                        customClass: {
                            popup: 'v2-delete-modal',
                            confirmButton: 'inline-flex items-center justify-center h-[34px] px-4 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-xs cursor-pointer'
                        },
                        buttonsStyling: false
                    });
                }
            });
        </script>
    @endif

    @yield('scripts')
    @stack('scripts')
    @stack('myscript')
</body>
</html>
