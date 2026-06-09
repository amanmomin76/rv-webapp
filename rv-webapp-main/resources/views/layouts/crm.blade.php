<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $sectionTitle }} | {{ $appTitle }}</title>
    <style>@include('partials.crm.styles')</style>
</head>
<body class="crm-body">
    <div class="crm-shell">
        <aside class="crm-sidebar">
            <div class="crm-brand">
                <div class="crm-brand-mark">LF</div>
                <div class="crm-brand-copy">
                    <h1>{{ $appTitle }}</h1>
                    <p>{{ $currentUserRole }} workspace</p>
                </div>
            </div>

            <nav class="crm-nav-section" aria-label="Primary">
                <p class="crm-nav-kicker">WORKSPACE</p>
                @foreach ($navigation as $item)
                    @if ($item['divider_before'])
                        <div class="crm-nav-divider"></div>
                    @endif
                    <a
                        class="crm-nav-link {{ $activeNav === $item['key'] ? 'is-active' : '' }}"
                        href="{{ route($item['route']) }}"
                    >
                        <span class="crm-nav-icon">{{ $item['abbr'] }}</span>
                        <span class="crm-nav-label">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="crm-workspace-card">
                <p class="eyebrow">Workspace</p>
                <p class="title">{{ $workspaceVersion }}</p>
                <p class="subtitle">{{ $workspaceSubtitle }}</p>
            </div>
        </aside>

        <main class="crm-main">
            <header class="crm-topbar">
                <div class="crm-topbar-row">
                    <div class="crm-topbar-copy">
                        <div class="crm-topbar-icon">CRM</div>
                        <div>
                            <h2>{{ $sectionTitle }}</h2>
                            <p>{{ $sectionSubtitle }}</p>
                        </div>
                    </div>

                    <div class="crm-topbar-actions">
                        <div class="crm-range-card">
                            <p class="label">{{ $headerRangeLabel }}</p>
                            <p class="value">{{ $headerRangeValue }}</p>
                        </div>
                        <div class="crm-notice-badge">NT</div>
                        <div class="crm-user-card">
                            <div class="crm-user-avatar">{{ $currentUserInitials }}</div>
                            <div>
                                <p class="name">{{ $currentUserName }}</p>
                                <p class="role">{{ $currentUserRole }}</p>
                            </div>
                        </div>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button class="crm-logout" type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <section class="crm-page">
                @yield('content')
            </section>
        </main>
    </div>
</body>
</html>
