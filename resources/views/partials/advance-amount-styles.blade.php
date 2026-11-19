<style>
    .aa-head {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .aa-avatar {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        flex: none;
        background: linear-gradient(135deg, #10b981, #047857);
        color: #fff;
        font-weight: 800;
        font-size: 20px;
        box-shadow: 0 8px 20px rgba(16, 185, 129, .3);
    }

    .aa-card {
        background: #fff;
        border: 1px solid var(--pm-border);
        border-radius: 16px;
        box-shadow: var(--pm-shadow);
        margin-bottom: 1.5rem;
    }

    .aa-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--pm-border);
    }

    .aa-card-head h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
    }

    .aa-card-body {
        padding: 22px;
    }

    .aa-amount {
        position: relative;
    }

    .aa-amount span {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 700;
        color: #64748b;
    }

    .aa-amount .form-control {
        padding-left: 30px;
        font-size: 18px;
        font-weight: 700;
        height: 50px;
    }

    .aa-amount .form-control.is-invalid {
        background-position: right 12px center;
    }

    .aa-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }

    .aa-chip {
        border: 1px solid var(--pm-border);
        background: #f8fafc;
        color: #334155;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        cursor: pointer;
        transition: background .12s ease, border-color .12s ease;
    }

    .aa-chip:hover {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #047857;
    }

    .aa-methods {
        display: flex;
        gap: 8px;
    }

    .aa-methods label {
        flex: 1;
        margin: 0 !important;
        cursor: pointer;
    }

    .aa-methods input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .aa-methods .aa-opt {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 42px;
        border: 1px solid #dbe1ea;
        border-radius: 10px;
        font-weight: 600;
        color: #475569;
        transition: all .12s ease;
    }

    .aa-methods input:checked + .aa-opt {
        border-color: var(--pm-primary);
        background: #ecfdf5;
        color: #047857;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, .12);
    }

    .aa-balance {
        background: linear-gradient(160deg, #0b1220 0%, #1e293b 100%);
        color: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--pm-shadow-lg);
        margin-bottom: 1.5rem;
    }

    .aa-balance .st-label {
        color: #94a3b8;
    }

    .aa-balance-big {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -.02em;
        line-height: 1.15;
        font-variant-numeric: tabular-nums;
    }

    .aa-balance-big.is-over {
        color: #fda4af;
    }

    .aa-balance .pm-progress {
        background: rgba(255, 255, 255, .1);
        height: 8px;
        margin: 16px 0 6px;
    }

    .aa-lines {
        margin-top: 18px;
        border-top: 1px solid rgba(255, 255, 255, .08);
    }

    .aa-line {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, .08);
        font-variant-numeric: tabular-nums;
    }

    .aa-line:last-child {
        border-bottom: 0;
    }

    .aa-line span:first-child {
        color: #cbd5e1;
    }

    .aa-line b {
        font-weight: 700;
    }

    .aa-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }
</style>
