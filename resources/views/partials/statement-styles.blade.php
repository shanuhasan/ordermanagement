    <style>
        .st-toolbar {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .st-toolbar form {
            display: flex;
            align-items: flex-end;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .st-toolbar .form-control {
            min-width: 140px;
        }

        .st-sheet {
            background: #fff;
            max-width: 1000px;
            margin: 0 auto 2rem;
            border: 1px solid var(--pm-border);
            border-radius: 16px;
            box-shadow: var(--pm-shadow-lg);
            overflow: hidden;
            color: #0f172a;
        }

        .st-band {
            height: 6px;
            background: linear-gradient(90deg, #10b981, #047857);
        }

        .st-pad {
            padding: 32px 36px;
        }

        .st-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            flex-wrap: wrap;
            padding-bottom: 24px;
            border-bottom: 1px solid #e6e9f0;
        }

        .st-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .st-brand .st-mark {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #10b981, #047857);
            color: #fff;
            font-weight: 800;
            font-size: 20px;
        }

        .st-company {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .st-muted {
            color: #64748b;
            font-size: 13px;
        }

        .st-title {
            text-align: right;
        }

        .st-title h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #047857;
        }

        .st-meta {
            margin-top: 8px;
            font-size: 13px;
            color: #475569;
        }

        .st-meta b {
            color: #0f172a;
        }

        .st-party {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
            padding: 22px 0;
        }

        .st-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .st-party-name {
            font-size: 18px;
            font-weight: 700;
        }

        .st-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .st-box {
            border: 1px solid #e6e9f0;
            border-radius: 12px;
            padding: 14px 16px;
            background: #f8fafc;
        }

        .st-box .st-val {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -.01em;
            font-variant-numeric: tabular-nums;
        }

        .st-box--due {
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .st-box--due .st-val {
            color: #047857;
        }

        .st-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            font-variant-numeric: tabular-nums;
        }

        .st-table th {
            background: #0f172a;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: 10px 12px;
            text-align: left;
            white-space: nowrap;
        }

        .st-table th:first-child {
            border-top-left-radius: 8px;
        }

        .st-table th:last-child {
            border-top-right-radius: 8px;
        }

        .st-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eef1f6;
            vertical-align: top;
        }

        .st-table tbody tr:nth-child(even) td {
            background: #fafbfd;
        }

        .st-table .num {
            text-align: right;
            white-space: nowrap;
        }

        .st-table .c {
            text-align: center;
        }

        .st-credit {
            color: #047857;
        }

        .st-pay td {
            background: #f0fdf4 !important;
        }

        .st-pay-tag {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #047857;
            background: #d1fae5;
            border-radius: 999px;
            padding: 1px 8px;
            margin-left: 6px;
        }

        .st-table tfoot td {
            border-top: 2px solid #0f172a;
            border-bottom: 0;
            font-weight: 700;
            padding-top: 12px;
            background: #fff;
        }

        .st-table tfoot .st-grand {
            color: #047857;
            font-size: 15px;
        }

        .st-empty {
            text-align: center;
            color: #94a3b8;
            padding: 28px !important;
        }

        .st-sign {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            margin-top: 56px;
        }

        .st-sign div {
            width: 220px;
            border-top: 1px solid #94a3b8;
            padding-top: 6px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }

        .st-foot {
            margin-top: 28px;
            padding-top: 14px;
            border-top: 1px dashed #e2e8f0;
            text-align: center;
            font-size: 11.5px;
            color: #94a3b8;
        }

        .st-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 28px 0 10px;
        }

        .st-section h3 {
            margin: 0;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #0f172a;
        }

        .st-section span {
            font-size: 12px;
            color: #94a3b8;
        }

        .st-box--paid .st-val {
            color: #0284c7;
        }

        .st-box--neg {
            background: #fff1f2;
            border-color: #fecdd3;
        }

        .st-box--neg .st-val {
            color: #e11d48;
        }

        @media (max-width: 767.98px) {
            .st-pad {
                padding: 20px 16px;
            }

            .st-title {
                text-align: left;
            }

            .st-summary {
                grid-template-columns: 1fr;
            }

            .st-table-wrap {
                overflow-x: auto;
            }
        }

        @page {
            size: A4;
            margin: 12mm;
        }

        @media print {
            .no-print,
            .content-header,
            .main-footer {
                display: none !important;
            }

            body,
            .content-wrapper {
                background: #fff !important;
            }

            .content-wrapper,
            .content {
                padding: 0 !important;
                margin: 0 !important;
            }

            .st-sheet {
                max-width: none;
                margin: 0;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .st-pad {
                padding: 0;
            }

            .st-band {
                display: none;
            }

            .st-sheet,
            .st-sheet * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .st-table thead {
                display: table-header-group;
            }

            .st-table tr {
                page-break-inside: avoid;
            }
        }
    </style>
