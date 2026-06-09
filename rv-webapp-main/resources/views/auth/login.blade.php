<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <style>@include('partials.crm.styles')</style>
</head>
<body class="crm-auth">
    <div class="auth-shell">
        <section class="auth-brand">
            <div class="auth-brand-inner">
                <div class="auth-brand-stack">
                    <div class="auth-logo-row">
                        <div class="auth-logo-badge"></div>
                        <div>
                            <h1 class="auth-wordmark">Lead<span>Flow</span></h1>
                            <div class="auth-submark">C R M</div>
                        </div>
                    </div>

                    <div class="auth-hero">
                        <h2>Manage Leads.<br>Close Deals. Grow Business.</h2>
                        <p>A complete CRM solution for industrial businesses.</p>
                    </div>

                    <div class="auth-feature-grid">
                        <div class="auth-feature">
                            <div class="auth-feature-icon">LM</div>
                            <p class="auth-feature-title">Lead Management</p>
                            <p class="auth-feature-copy">Track and manage all your leads.</p>
                        </div>
                        <div class="auth-feature">
                            <div class="auth-feature-icon">FU</div>
                            <p class="auth-feature-title">Follow Ups</p>
                            <p class="auth-feature-copy">Never miss a follow-up with reminders.</p>
                        </div>
                        <div class="auth-feature">
                            <div class="auth-feature-icon">AN</div>
                            <p class="auth-feature-title">Analytics</p>
                            <p class="auth-feature-copy">Get insights and grow your business.</p>
                        </div>
                    </div>
                </div>

                <p class="auth-copyright">© 2026 LeadFlow CRM. All rights reserved.</p>
            </div>
        </section>

        <section class="auth-form-wrap">
            <div class="auth-card">
                <div class="auth-card-header">
                    <h1>Welcome Back!</h1>
                    <p>Sign in to continue to LeadFlow CRM</p>
                </div>

                <form class="auth-form" method="post" action="{{ route('login.store') }}">
                    @csrf
                    <div class="auth-field">
                        <label class="auth-label" for="username">Username</label>
                        <input
                            class="auth-input"
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username', 'owner@leadflowcrm.com') }}"
                            placeholder="Enter your username"
                            required
                        >
                        @error('username')
                            <p class="auth-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label class="auth-label" for="password">Password</label>
                        <input
                            class="auth-input"
                            id="password"
                            name="password"
                            type="password"
                            value="12345"
                            placeholder="Enter your password"
                            required
                        >
                        @error('password')
                            <p class="auth-error">{{ $message }}</p>
                        @enderror
                        <p class="auth-helper">All seeded CRM users use the same password: <strong>12345</strong>.</p>
                    </div>

                    @if ($sampleUsers->isNotEmpty())
                        <div class="auth-helper" style="margin-top: 14px">
                            <strong>Sample users:</strong>
                            @foreach ($sampleUsers as $sampleUser)
                                <div>{{ $sampleUser->email }} — {{ $sampleUser->role }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div style="text-align:center">
                        <button class="auth-button" type="submit">▣ Sign In</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
