<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $targetApp }}</title>
    <style>
        :root {
            --bg: #08111f;
            --panel: #10213a;
            --panel-soft: #132946;
            --line: #29517d;
            --text: #e8f1fb;
            --muted: #9db4ce;
            --accent: #4dbff2;
            --success: #78dba9;
            --warn: #f6c56a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Instrument Sans", "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top right, rgba(77, 191, 242, 0.18), transparent 26%),
                radial-gradient(circle at left center, rgba(120, 219, 169, 0.10), transparent 24%),
                linear-gradient(180deg, #08111f 0%, #0c1830 100%);
            color: var(--text);
        }

        .shell {
            max-width: 1180px;
            margin: 0 auto;
            padding: 40px 24px 64px;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.3fr 0.9fr;
            gap: 24px;
            margin-bottom: 28px;
        }

        .card {
            background: rgba(16, 33, 58, 0.94);
            border: 1px solid var(--line);
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(4, 10, 21, 0.34);
        }

        .hero-copy {
            padding: 28px;
        }

        .eyebrow {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(77, 191, 242, 0.12);
            color: var(--accent);
            font-size: 13px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        h1 {
            margin: 16px 0 12px;
            font-size: clamp(2rem, 4vw, 3.8rem);
            line-height: 0.98;
            letter-spacing: -0.04em;
        }

        p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .hero-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 24px;
        }

        .meta-box {
            padding: 14px 16px;
            border-radius: 16px;
            background: var(--panel-soft);
            border: 1px solid rgba(77, 191, 242, 0.16);
        }

        .meta-label {
            display: block;
            margin-bottom: 6px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted);
        }

        .meta-value {
            font-size: 15px;
            font-weight: 650;
            color: var(--text);
        }

        .hero-side {
            padding: 24px;
        }

        .hero-side h2,
        .section h2 {
            margin: 0 0 14px;
            font-size: 1.05rem;
            letter-spacing: 0.02em;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .chip {
            padding: 10px 12px;
            border-radius: 999px;
            background: rgba(120, 219, 169, 0.10);
            border: 1px solid rgba(120, 219, 169, 0.28);
            color: var(--success);
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
        }

        .section {
            padding: 24px;
        }

        .module-list {
            display: grid;
            gap: 14px;
        }

        .module {
            padding: 16px;
            border-radius: 16px;
            background: #0d1a2f;
            border: 1px solid rgba(157, 180, 206, 0.14);
        }

        .module-top {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 8px;
            align-items: baseline;
        }

        .module-name {
            font-weight: 700;
        }

        .module-source {
            color: var(--accent);
            font-size: 13px;
        }

        .module-status {
            color: var(--muted);
            font-size: 14px;
        }

        .notes {
            display: grid;
            gap: 12px;
        }

        .note {
            padding: 14px 16px;
            border-left: 3px solid var(--warn);
            border-radius: 0 14px 14px 0;
            background: rgba(246, 197, 106, 0.08);
            color: var(--muted);
        }

        code {
            color: var(--text);
            font-family: "SFMono-Regular", ui-monospace, monospace;
            font-size: 0.95em;
        }

        @media (max-width: 900px) {
            .hero,
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="hero">
            <article class="card hero-copy">
                <span class="eyebrow">Migration Baseline</span>
                <h1>Laravel rebuild started for the full RV workflow.</h1>
                <p>
                    The desktop Avalonia CRM has been audited and the new web codebase is now running on Laravel with
                    a MySQL-first schema plan. This app is the new migration target for all future CRM work.
                </p>

                <div class="hero-meta">
                    <div class="meta-box">
                        <span class="meta-label">Source App</span>
                        <span class="meta-value">{{ $sourceApp }}</span>
                    </div>
                    <div class="meta-box">
                        <span class="meta-label">Target App</span>
                        <span class="meta-value">{{ $targetApp }}</span>
                    </div>
                    <div class="meta-box">
                        <span class="meta-label">Backend</span>
                        <span class="meta-value">Laravel 13 + Eloquent</span>
                    </div>
                    <div class="meta-box">
                        <span class="meta-label">Database</span>
                        <span class="meta-value">MySQL-first schema baseline</span>
                    </div>
                </div>
            </article>

            <aside class="card hero-side">
                <h2>Initial SQL Tables</h2>
                <div class="chips">
                    @foreach ($tables as $table)
                        <span class="chip">{{ $table }}</span>
                    @endforeach
                </div>
            </aside>
        </section>

        <section class="grid">
            <article class="card section">
                <h2>Module Inventory</h2>
                <div class="module-list">
                    @foreach ($modules as $module)
                        <div class="module">
                            <div class="module-top">
                                <span class="module-name">{{ $module['name'] }}</span>
                                <span class="module-source"><code>{{ $module['source'] }}</code></span>
                            </div>
                            <div class="module-status">{{ $module['status'] }}</div>
                        </div>
                    @endforeach
                </div>
            </article>

            <aside class="card section">
                <h2>What’s Ready</h2>
                <div class="notes">
                    <div class="note">
                        Laravel 13 has been scaffolded as the new web foundation.
                    </div>
                    <div class="note">
                        MySQL configuration is now the default local environment target.
                    </div>
                    <div class="note">
                        Core CRM entities and indexes are being mapped from the Avalonia view models.
                    </div>
                    <div class="note">
                        The source app currently stores state in memory, so the Laravel schema becomes the first real persistence layer.
                    </div>
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
