:root {
    --bg: #08111f;
    --bg-soft: #0c1830;
    --panel: #0d1726;
    --panel-alt: #0b1420;
    --panel-deep: #0c1624;
    --line: #1e3148;
    --line-strong: #29527a;
    --text: #e8f1fb;
    --muted: #9db4ce;
    --muted-soft: #6f87a4;
    --blue: #4dbff2;
    --blue-strong: #2458d3;
    --green: #5ce2a0;
    --green-strong: #1f8b46;
    --amber: #f6c56a;
    --rose: #f39ab0;
    --violet: #c4b5fd;
    --white: #ffffff;
    --shadow: 0 20px 55px rgba(2, 12, 26, 0.28);
}

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    font-family: "Instrument Sans", "Segoe UI", sans-serif;
}

a {
    color: inherit;
    text-decoration: none;
}

button,
input,
select,
textarea {
    font: inherit;
}

button {
    cursor: pointer;
}

.crm-auth {
    min-height: 100vh;
    background: #f6faff;
    color: #111827;
}

.auth-shell {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1.05fr 1.15fr;
}

.auth-brand {
    position: relative;
    overflow: hidden;
    background:
        linear-gradient(180deg, rgba(7, 20, 38, 0.72), rgba(7, 20, 38, 0.72)),
        linear-gradient(135deg, #071527 0%, #102b49 42%, #07101c 100%);
}

.auth-brand::before,
.auth-brand::after {
    content: "";
    position: absolute;
    inset: auto auto auto auto;
    pointer-events: none;
}

.auth-brand::before {
    width: 130%;
    height: 34px;
    left: -8%;
    top: 108px;
    background: rgba(107, 120, 144, 0.24);
    transform: rotate(-18deg);
}

.auth-brand::after {
    width: 122%;
    height: 28px;
    left: 6%;
    top: 336px;
    background: rgba(51, 71, 95, 0.26);
    transform: rotate(-12deg);
}

.auth-brand-inner {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-rows: 1fr auto;
    min-height: 100%;
    padding: 54px 64px 54px 74px;
    color: #fff;
}

.auth-brand-stack {
    align-self: center;
    max-width: 520px;
}

.auth-logo-row {
    display: flex;
    align-items: center;
    gap: 22px;
}

.auth-logo-badge {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: #2563eb;
    display: grid;
    place-items: center;
    position: relative;
}

.auth-logo-badge::before {
    content: "";
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 12px solid #93c5fd;
    position: absolute;
}

.auth-logo-badge::after {
    content: "";
    width: 22px;
    height: 22px;
    background: #2563eb;
    position: absolute;
}

.auth-wordmark {
    margin: 0;
    font-size: 44px;
    line-height: 1;
    font-weight: 700;
}

.auth-wordmark span {
    color: #2563eb;
}

.auth-submark {
    margin-top: 4px;
    font-size: 25px;
    letter-spacing: 10px;
    color: #d8e4f6;
}

.auth-hero {
    margin-top: 34px;
}

.auth-hero h2 {
    margin: 0 0 14px;
    font-size: 29px;
    line-height: 1.25;
}

.auth-hero p {
    margin: 0;
    font-size: 21px;
    line-height: 1.52;
    color: #d3deec;
}

.auth-feature-grid {
    margin-top: 44px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}

.auth-feature {
    text-align: center;
}

.auth-feature-icon {
    width: 50px;
    height: 50px;
    margin: 0 auto 12px;
    border-radius: 12px;
    background: #1d5fdb;
    display: grid;
    place-items: center;
    font-size: 14px;
    font-weight: 700;
}

.auth-feature-title {
    margin: 0 0 6px;
    font-size: 13px;
    font-weight: 700;
}

.auth-feature-copy {
    margin: 0;
    font-size: 13px;
    line-height: 1.55;
    color: #c5d1e0;
}

.auth-copyright {
    margin: 0;
    font-size: 13px;
    color: #b6c5d6;
}

.auth-form-wrap {
    display: grid;
    place-items: center;
    padding: 44px 74px 72px 0;
}

.auth-card {
    width: min(520px, 100%);
    background: #fff;
    border: 1px solid #ebf1f8;
    border-radius: 14px;
    box-shadow: 0 18px 45px rgba(31, 51, 74, 0.13);
    padding: 56px 58px;
}

.auth-card-header {
    text-align: center;
}

.auth-card-header h1 {
    margin: 0 0 10px;
    font-size: 29px;
    color: #111827;
}

.auth-card-header p {
    margin: 0;
    font-size: 16px;
    color: #475569;
}

.auth-form {
    margin-top: 22px;
}

.auth-field {
    margin-bottom: 18px;
}

.auth-label {
    display: block;
    margin-bottom: 9px;
    font-size: 13px;
    font-weight: 600;
    color: #111827;
}

.auth-input {
    width: 100%;
    min-height: 56px;
    padding: 14px 16px;
    border-radius: 10px;
    border: 1px solid #d7e0ec;
    background: #fff;
    color: #111827;
    font-size: 15px;
}

.auth-input:focus {
    outline: none;
    border-color: #93b6f7;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
}

.auth-helper {
    margin: 10px 0 0;
    font-size: 12px;
    line-height: 1.5;
    color: #64748b;
}

.auth-error {
    margin: 8px 0 0;
    font-size: 12px;
    color: #dc2626;
}

.auth-button {
    margin: 10px auto 0;
    min-width: 156px;
    min-height: 56px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border: 1px solid #2563eb;
    border-radius: 8px;
    background: #2563eb;
    color: #fff;
    font-size: 15px;
    font-weight: 600;
}

.crm-body {
    min-height: 100vh;
    color: var(--text);
    background:
        radial-gradient(circle at top right, rgba(77, 191, 242, 0.18), transparent 26%),
        radial-gradient(circle at left center, rgba(120, 219, 169, 0.1), transparent 24%),
        linear-gradient(180deg, var(--bg) 0%, var(--bg-soft) 100%);
}

.crm-shell {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 286px 1fr;
    gap: 18px;
    padding: 18px;
}

.crm-sidebar {
    background: rgba(10, 19, 32, 0.95);
    border: 1px solid rgba(41, 81, 125, 0.72);
    border-radius: 24px;
    padding: 18px 18px 16px;
    display: grid;
    grid-template-rows: auto 1fr auto;
    gap: 18px;
    box-shadow: var(--shadow);
}

.crm-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.crm-brand-mark {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #2b66ee 0%, #123ea4 100%);
    color: #fff;
    font-size: 15px;
    font-weight: 700;
}

.crm-brand-copy h1 {
    margin: 0;
    font-size: 18px;
}

.crm-brand-copy p {
    margin: 2px 0 0;
    font-size: 11px;
    color: var(--muted);
}

.crm-nav-section {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.crm-nav-kicker {
    margin: 2px 0 4px 2px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: var(--muted);
    opacity: 0.7;
}

.crm-nav-divider {
    height: 1px;
    margin: 6px 0;
    background: rgba(41, 81, 125, 0.54);
}

.crm-nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border: 1px solid transparent;
    border-radius: 16px;
    color: var(--text);
    transition: background 120ms ease, border-color 120ms ease, transform 120ms ease;
}

.crm-nav-link:hover {
    background: rgba(19, 39, 66, 0.75);
    border-color: rgba(41, 81, 125, 0.6);
    transform: translateX(1px);
}

.crm-nav-link.is-active {
    background: linear-gradient(180deg, rgba(20, 46, 77, 0.94), rgba(15, 34, 57, 0.94));
    border-color: rgba(77, 191, 242, 0.38);
    box-shadow: inset 0 0 0 1px rgba(77, 191, 242, 0.08);
}

.crm-nav-icon {
    width: 28px;
    height: 28px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    background: #18283e;
    border: 1px solid #22344c;
    font-size: 10px;
    font-weight: 700;
}

.crm-nav-link.is-active .crm-nav-icon {
    background: #132742;
    border-color: #2f5d8d;
}

.crm-nav-label {
    font-size: 14px;
    font-weight: 500;
}

.crm-workspace-card {
    background: #0b1320;
    border: 1px solid rgba(41, 81, 125, 0.54);
    border-radius: 18px;
    padding: 14px 12px;
}

.crm-workspace-card p {
    margin: 0;
}

.crm-workspace-card .eyebrow {
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
}

.crm-workspace-card .title {
    margin-top: 4px;
    font-size: 13px;
    font-weight: 600;
}

.crm-workspace-card .subtitle {
    margin-top: 4px;
    font-size: 11px;
    color: var(--muted);
}

.crm-main {
    display: grid;
    grid-template-rows: auto 1fr;
    gap: 16px;
    min-width: 0;
}

.crm-topbar {
    background: rgba(13, 23, 38, 0.96);
    border: 1px solid rgba(41, 81, 125, 0.6);
    border-radius: 22px;
    padding: 18px 22px;
    box-shadow: var(--shadow);
}

.crm-topbar-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.crm-topbar-copy {
    display: flex;
    align-items: center;
    gap: 14px;
}

.crm-topbar-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: #12253d;
    border: 1px solid #22405e;
    font-size: 10px;
    font-weight: 700;
}

.crm-topbar-copy h2 {
    margin: 0;
    font-size: 26px;
}

.crm-topbar-copy p {
    margin: 4px 0 0;
    font-size: 13px;
    color: var(--muted);
}

.crm-topbar-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.crm-range-card {
    background: #101d30;
    border: 1px solid rgba(41, 81, 125, 0.56);
    border-radius: 14px;
    padding: 10px 14px;
}

.crm-range-card p {
    margin: 0;
}

.crm-range-card .label {
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
}

.crm-range-card .value {
    margin-top: 2px;
    font-size: 13px;
    font-weight: 600;
}

.crm-notice-badge {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: #101d30;
    border: 1px solid rgba(41, 81, 125, 0.56);
    color: var(--blue);
    font-size: 10px;
    font-weight: 700;
}

.crm-user-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #13233a;
    border: 1px solid #244061;
    border-radius: 16px;
    padding: 8px 12px;
}

.crm-user-avatar {
    width: 34px;
    height: 34px;
    border-radius: 11px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #14365c, #0d223b);
    border: 1px solid #2b4d75;
    font-size: 11px;
    font-weight: 700;
}

.crm-user-card p {
    margin: 0;
}

.crm-user-card .name {
    font-size: 13px;
    font-weight: 600;
}

.crm-user-card .role {
    margin-top: 2px;
    font-size: 11px;
    color: var(--muted);
}

.crm-logout {
    min-height: 38px;
    padding: 0 14px;
    border-radius: 12px;
    border: 1px solid rgba(41, 81, 125, 0.56);
    background: #101d30;
    color: var(--text);
}

.crm-page {
    min-width: 0;
}

.crm-panel {
    background: rgba(13, 23, 38, 0.95);
    border: 1px solid rgba(41, 81, 125, 0.6);
    border-radius: 22px;
    padding: 22px;
    box-shadow: var(--shadow);
}

.crm-kicker {
    margin: 0 0 6px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #6fa3d9;
}

.page-header-grid {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 24px;
}

.page-header-grid h3 {
    margin: 0;
    font-size: 34px;
}

.page-header-grid p {
    margin: 6px 0 0;
    font-size: 15px;
    color: var(--muted);
}

.compact-chip {
    align-self: center;
    min-width: 260px;
    background: rgba(16, 29, 48, 0.96);
    border: 1px solid rgba(41, 81, 125, 0.56);
    border-radius: 16px;
    padding: 12px 16px;
}

.compact-chip .row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.compact-chip .icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: #13253b;
    border: 1px solid #203955;
    color: #8fb7e2;
    font-size: 11px;
    font-weight: 700;
}

.compact-chip .label {
    margin: 0;
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
}

.compact-chip .value {
    margin: 2px 0 0;
    font-size: 13px;
    font-weight: 600;
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 14px;
}

.kpi-card {
    min-height: 136px;
    border-radius: 20px;
    border: 1px solid transparent;
    padding: 18px 16px;
}

.kpi-card .head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.kpi-card .label {
    font-size: 13px;
    font-weight: 600;
    color: #eaf3ff;
}

.kpi-card .icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    font-size: 10px;
    font-weight: 700;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.kpi-card .value {
    margin: 18px 0 0;
    font-size: 26px;
    font-weight: 700;
    color: #fff;
}

.kpi-card .trend {
    margin: 18px 0 0;
    font-size: 12px;
    font-weight: 600;
}

.kpi-card .comparison {
    margin: 4px 0 0;
    font-size: 11px;
    color: #a8bad0;
}

.theme-blue {
    background: #14274a;
    border-color: #234170;
}

.theme-blue .icon {
    background: #173a6a;
    color: #9fd4ff;
}

.theme-blue .trend {
    color: #7dd3fc;
}

.theme-green {
    background: #163126;
    border-color: #274c3d;
}

.theme-green .icon {
    background: #173d2e;
    color: #8be4a8;
}

.theme-green .trend {
    color: #6ee7b7;
}

.theme-rose {
    background: #3b2328;
    border-color: #5b3941;
}

.theme-rose .icon {
    background: #4a2426;
    color: #ffb8b8;
}

.theme-rose .trend {
    color: #fda4af;
}

.theme-mint {
    background: #183126;
    border-color: #2c4f3c;
}

.theme-mint .icon {
    background: #183c2b;
    color: #9cf3b8;
}

.theme-mint .trend {
    color: #6ee7b7;
}

.theme-violet {
    background: #332348;
    border-color: #4a3566;
}

.theme-violet .icon {
    background: #3b2858;
    color: #d4c1ff;
}

.theme-violet .trend {
    color: #c4b5fd;
}

.analytics-grid {
    margin-top: 20px;
    display: grid;
    grid-template-columns: 1.15fr 1fr 0.92fr;
    gap: 18px;
}

.panel-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 18px;
}

.panel-head h4 {
    margin: 0;
    font-size: 18px;
}

.panel-head p {
    margin: 3px 0 0;
    font-size: 11px;
    color: var(--muted);
}

.panel-chip {
    background: #12253b;
    border: 1px solid #21384f;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    white-space: nowrap;
}

.source-layout {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 18px;
}

.source-ring {
    width: 212px;
    height: 212px;
    padding: 8px;
    border-radius: 24px;
    background: #0c1624;
    border: 1px solid #1d3149;
    display: grid;
    place-items: center;
}

.source-donut {
    width: 188px;
    height: 188px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    background: conic-gradient(#2853d8 0 55%, #f97316 55% 70%, #22c55e 70% 80%, #93c5fd 80% 89%, #c084fc 89% 100%);
    box-shadow: inset 0 0 0 24px rgba(9, 17, 29, 0.9);
}

.source-donut-inner {
    width: 86px;
    height: 86px;
    border-radius: 50%;
    background: #101a28;
    border: 1px solid #1a2c44;
    display: grid;
    place-items: center;
    text-align: center;
}

.source-donut-inner strong {
    display: block;
    font-size: 24px;
    color: #fff;
}

.source-donut-inner span {
    display: block;
    margin-top: 2px;
    font-size: 11px;
    color: var(--muted);
}

.legend-card {
    background: #0d1724;
    border: 1px solid #1e3148;
    border-radius: 18px;
    padding: 16px 14px;
}

.legend-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.legend-item:last-child {
    margin-bottom: 0;
}

.legend-item .dot {
    width: 10px;
    height: 10px;
    border-radius: 999px;
}

.legend-item .label {
    font-size: 13px;
}

.legend-item .value {
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
}

.trend-card {
    background: #0c1624;
    border: 1px solid #1e3148;
    border-radius: 18px;
    padding: 16px 14px;
}

.trend-bars {
    height: 230px;
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    align-items: end;
    gap: 14px;
}

.trend-col {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.trend-track {
    width: 100%;
    height: 170px;
    border-radius: 16px;
    background:
        linear-gradient(180deg, rgba(27, 42, 64, 0.3), rgba(27, 42, 64, 0.3)),
        #09111d;
    border: 1px solid rgba(30, 49, 72, 0.9);
    display: flex;
    align-items: end;
    padding: 10px;
}

.trend-fill {
    width: 100%;
    border-radius: 12px;
    background: linear-gradient(180deg, #35b6ff, #143253);
}

.trend-value {
    font-size: 12px;
    color: #8fd9ff;
    font-weight: 600;
}

.trend-month {
    font-size: 12px;
    color: var(--muted);
}

.top-employee-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.top-employee {
    background: #0d1724;
    border: 1px solid #1e3148;
    border-radius: 18px;
    padding: 14px;
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 12px;
    align-items: center;
}

.mini-avatar {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 12px;
    font-weight: 700;
}

.mini-avatar.amber { background: rgba(245, 158, 11, 0.18); color: #fcd34d; }
.mini-avatar.rose { background: rgba(251, 113, 133, 0.16); color: #fda4af; }
.mini-avatar.gold { background: rgba(251, 191, 36, 0.16); color: #fcd34d; }
.mini-avatar.orange { background: rgba(249, 115, 22, 0.18); color: #fdba74; }

.top-employee h5 {
    margin: 0;
    font-size: 14px;
}

.top-employee p {
    margin: 4px 0 0;
    font-size: 11px;
    color: var(--muted);
}

.top-employee .count {
    font-size: 13px;
    font-weight: 700;
    color: #d7e7fb;
}

.toolbar-form,
.toolbar-row {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    align-items: center;
}

.toolbar-form .grow,
.toolbar-row .grow {
    flex: 1 1 280px;
}

.toolbar-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
}

.crm-input,
.crm-select,
.crm-textarea {
    width: 100%;
    min-height: 44px;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid #1e3148;
    background: #0d1724;
    color: var(--text);
}

.crm-textarea {
    min-height: 112px;
    resize: vertical;
}

.crm-input::placeholder,
.crm-textarea::placeholder {
    color: var(--muted);
}

.crm-input:focus,
.crm-select:focus,
.crm-textarea:focus {
    outline: none;
    border-color: #3e78d6;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
}

.primary-button,
.secondary-button,
.ghost-button,
.success-button {
    min-height: 44px;
    padding: 10px 18px;
    border-radius: 14px;
    border: 1px solid transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
}

.primary-button {
    background: #2458d3;
    border-color: #3b74f0;
    color: #fff;
}

.secondary-button,
.ghost-button {
    background: #122033;
    border-color: #1e3148;
    color: var(--text);
}

.success-button {
    background: #5ce2a0;
    border-color: #4acb8d;
    color: #0a1b22;
}

.summary-chip {
    min-height: 40px;
    padding: 8px 12px;
    border-radius: 12px;
    border: 1px solid #1e3148;
    background: #0f1d30;
    color: var(--text);
    display: inline-flex;
    align-items: center;
    font-size: 12px;
    font-weight: 600;
}

.assignment-overview {
    margin-bottom: 14px;
}

.integration-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-top: 16px;
}

.integration-card {
    min-height: 178px;
    padding: 16px;
    border-radius: 16px;
    border: 1px solid #1e3148;
    background: #0b1420;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.integration-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.integration-card-head strong,
.integration-card > span {
    font-size: 12px;
    color: #c9d7ea;
}

.integration-card p {
    margin: 0;
    font-size: 12px;
    line-height: 1.55;
    color: var(--muted);
}

.integration-method {
    color: var(--text) !important;
    font-weight: 700;
}

.assignment-flow,
.routing-rules {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    margin-top: 16px;
}

.assignment-step,
.routing-rules > div {
    min-height: 104px;
    padding: 14px;
    border-radius: 14px;
    border: 1px solid #1e3148;
    background: #101d30;
}

.assignment-step span,
.routing-rules span {
    display: inline-flex;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 700;
    color: #8cb9ff;
}

.assignment-step strong,
.routing-rules strong {
    display: block;
    font-size: 13px;
}

.assignment-step p,
.routing-rules p {
    margin: 7px 0 0;
    font-size: 12px;
    line-height: 1.45;
    color: var(--muted);
}

.crm-alert {
    margin-bottom: 14px;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid rgba(42, 122, 85, 0.94);
    background: rgba(18, 56, 40, 0.92);
    color: #9af0c3;
    font-size: 13px;
    font-weight: 600;
}

.table-card-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
}

.table-card-title h4 {
    margin: 0;
    font-size: 18px;
}

.table-card-title p {
    margin: 0;
    font-size: 12px;
    color: var(--muted);
}

.crm-table-wrap {
    overflow: auto;
    border-radius: 18px;
    border: 1px solid #1e3148;
    background: #0b1420;
}

.crm-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 920px;
}

.assignment-table {
    min-width: 1480px;
}

.crm-table th,
.crm-table td {
    padding: 14px;
    border-bottom: 1px solid #162637;
    text-align: left;
    font-size: 13px;
    vertical-align: top;
}

.crm-table th {
    background: #111d2d;
    color: #c9d7ea;
    font-weight: 600;
}

.crm-table tbody tr {
    background: #0d1724;
}

.crm-table tbody tr:hover {
    background: #122033;
}

.crm-table tbody tr:last-child td {
    border-bottom: 0;
}

.table-link {
    color: #9fd4ff;
    font-weight: 600;
}

.platform-ref {
    margin-bottom: 4px;
    font-weight: 700;
    color: var(--text);
}

.platform-ref + small {
    display: block;
    max-width: 170px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.35;
}

.assignment-form {
    display: grid;
    grid-template-columns: minmax(150px, 1fr);
    gap: 8px;
    min-width: 190px;
}

.compact-select {
    min-height: 36px;
    padding: 8px 10px;
    border-radius: 10px;
    font-size: 12px;
}

.compact-button {
    min-height: 36px;
    padding: 8px 12px;
    border-radius: 10px;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    min-height: 28px;
    padding: 4px 10px;
    border-radius: 999px;
    border: 1px solid transparent;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.status-pill--blue {
    background: rgba(19, 46, 87, 0.95);
    border-color: rgba(34, 74, 124, 0.95);
    color: #8cb9ff;
}

.status-pill--slate {
    background: rgba(17, 29, 45, 0.96);
    border-color: rgba(49, 70, 98, 0.9);
    color: #d0dceb;
}

.status-pill--amber {
    background: rgba(73, 44, 16, 0.9);
    border-color: rgba(160, 100, 40, 0.74);
    color: #f6c56a;
}

.status-pill--green,
.status-pill--success,
.status-pill--mint {
    background: rgba(18, 56, 40, 0.92);
    border-color: rgba(42, 122, 85, 0.94);
    color: #5ce2a0;
}

.status-pill--rose {
    background: rgba(58, 30, 38, 0.92);
    border-color: rgba(136, 70, 90, 0.94);
    color: #f3a3b6;
}

.status-pill--orange {
    background: rgba(75, 40, 15, 0.92);
    border-color: rgba(172, 98, 39, 0.88);
    color: #fdba74;
}

.status-pill--violet {
    background: rgba(59, 40, 88, 0.92);
    border-color: rgba(101, 74, 149, 0.88);
    color: #d4c1ff;
}

.lead-back-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    min-height: 40px;
    padding: 7px 12px;
    border-radius: 12px;
    border: 1px solid #1e3148;
    background: #122033;
    font-size: 12px;
    font-weight: 600;
}

.breadcrumb {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}

.lead-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 392px;
    gap: 28px;
    align-items: center;
}

.hero-profile {
    display: flex;
    align-items: center;
    gap: 18px;
}

.hero-avatar {
    width: 72px;
    height: 72px;
    border-radius: 22px;
    display: grid;
    place-items: center;
    background: #17385b;
    border: 1px solid #2d567a;
    font-size: 18px;
    font-weight: 700;
}

.hero-copy h3 {
    margin: 0;
    font-size: 30px;
}

.hero-copy p {
    margin: 5px 0 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--muted);
}

.hero-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.hero-actions {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px 12px;
}

.detail-columns {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-top: 18px;
}

.detail-card {
    background: #0c1624;
    border: 1px solid #1e3148;
    border-radius: 20px;
    padding: 20px 18px;
}

.detail-card h4 {
    margin: 0 0 14px;
    font-size: 16px;
}

.detail-card p.description {
    margin: -4px 0 14px;
    font-size: 13px;
    color: var(--muted);
}

.field-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    align-items: start;
}

.field-block {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.field-block.full {
    grid-column: 1 / -1;
}

.field-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}

.field-value {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
}

.field-input,
.field-select,
.field-textarea {
    width: 100%;
    min-height: 38px;
    padding: 9px 10px;
    border-radius: 10px;
    border: 1px solid #1e3148;
    background: #111d2d;
    color: var(--text);
}

.field-textarea {
    min-height: 88px;
    resize: vertical;
}

.detail-grid-two {
    display: grid;
    grid-template-columns: 1.15fr 0.95fr;
    gap: 16px;
    margin-top: 16px;
}

.detail-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-item {
    background: #111d2d;
    border: 1px solid #1e3148;
    border-radius: 16px;
    padding: 14px 12px;
}

.detail-item h5 {
    margin: 0 0 6px;
    font-size: 14px;
}

.detail-item p {
    margin: 0;
    font-size: 12px;
    line-height: 1.6;
    color: var(--muted);
}

.detail-meta {
    margin-top: 8px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 11px;
    color: var(--muted);
}

.timeline {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.timeline-item {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 12px;
    align-items: start;
    background: #111d2d;
    border: 1px solid #1e3148;
    border-radius: 16px;
    padding: 14px 12px;
}

.timeline-icon {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: #17385b;
    border: 1px solid #2d567a;
    font-size: 11px;
    font-weight: 700;
}

.timeline-item h5 {
    margin: 0;
    font-size: 14px;
}

.timeline-item .stamp {
    margin-top: 2px;
    font-size: 11px;
    color: var(--muted);
}

.timeline-item .copy {
    margin-top: 6px;
    font-size: 12px;
    line-height: 1.6;
    color: var(--muted);
}

.project-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.25fr) 420px;
    gap: 18px;
}

.drawer-card {
    background: rgba(13, 23, 38, 0.98);
    border: 1px solid rgba(41, 81, 125, 0.6);
    border-radius: 22px;
    padding: 20px 18px;
    box-shadow: var(--shadow);
}

.drawer-card h4 {
    margin: 0;
    font-size: 18px;
}

.drawer-card .subtle {
    margin: 4px 0 0;
    font-size: 12px;
    color: var(--muted);
}

.drawer-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 16px;
}

.completed-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 14px;
}

.completed-card {
    display: block;
    background: #101d30;
    border: 1px solid #1e3148;
    border-radius: 16px;
    padding: 18px 16px;
}

.completed-card:hover {
    background: #182e4a;
}

.completed-card h5 {
    margin: 0;
    font-size: 15px;
}

.completed-card p {
    margin: 4px 0 0;
    font-size: 12px;
    color: var(--muted);
}

.completed-card .foot {
    margin-top: 12px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.helper-copy {
    margin: 6px 0 0;
    font-size: 13px;
    color: var(--muted);
}

.notice-banner {
    margin-top: 16px;
    background: rgba(19, 39, 66, 0.96);
    border: 1px solid rgba(41, 81, 125, 0.72);
    border-radius: 16px;
    padding: 14px 16px;
    font-size: 13px;
    line-height: 1.6;
    color: #cdddee;
}

.cards-two {
    display: grid;
    grid-template-columns: 1.3fr 1fr;
    gap: 18px;
}

.cards-half {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-top: 18px;
}

.simple-card h4 {
    margin: 0 0 8px;
    font-size: 16px;
}

.simple-card p {
    margin: 0;
    font-size: 13px;
    line-height: 1.65;
    color: var(--muted);
}

.muted-empty {
    margin: 0;
    font-size: 13px;
    color: var(--muted);
}

@media (max-width: 1380px) {
    .kpi-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .analytics-grid {
        grid-template-columns: 1fr 1fr;
    }

    .integration-grid,
    .assignment-flow,
    .routing-rules {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .analytics-grid > :last-child {
        grid-column: 1 / -1;
    }
}

@media (max-width: 1220px) {
    .crm-shell {
        grid-template-columns: 1fr;
    }

    .project-layout,
    .lead-hero,
    .cards-two,
    .detail-grid-two {
        grid-template-columns: 1fr;
    }

    .crm-sidebar {
        order: 2;
    }
}

@media (max-width: 1024px) {
    .auth-shell {
        grid-template-columns: 1fr;
    }

    .auth-brand-inner {
        padding: 48px 28px 28px;
    }

    .auth-form-wrap {
        padding: 28px;
    }

    .kpi-grid,
    .detail-columns,
    .cards-half {
        grid-template-columns: 1fr;
    }

    .analytics-grid {
        grid-template-columns: 1fr;
    }

    .source-layout {
        grid-template-columns: 1fr;
    }

    .integration-grid,
    .assignment-flow,
    .routing-rules {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 760px) {
    .crm-shell {
        padding: 12px;
        gap: 12px;
    }

    .crm-topbar-row,
    .page-header-grid,
    .hero-profile {
        flex-direction: column;
        align-items: flex-start;
    }

    .crm-topbar-actions {
        width: 100%;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .hero-actions {
        grid-template-columns: 1fr;
    }

    .field-row {
        grid-template-columns: 1fr;
    }

    .auth-card {
        padding: 38px 24px;
    }

    .auth-feature-grid {
        grid-template-columns: 1fr;
    }
}
