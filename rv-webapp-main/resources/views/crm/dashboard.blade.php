@extends('layouts.crm')

@section('content')
    <div class="crm-panel">
        <div class="page-header-grid">
            <div>
                <p class="crm-kicker">OWNER OVERVIEW</p>
                <h3>{{ $dashboard['title'] }}</h3>
                <p>{{ $dashboard['subtitle'] }}</p>
            </div>

            <div class="compact-chip">
                <div class="row">
                    <div class="icon">DR</div>
                    <div>
                        <p class="label">{{ $dashboard['date_range_label'] }}</p>
                        <p class="value">{{ $dashboard['date_range_text'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="kpi-grid">
            @foreach ($dashboard['metrics'] as $metric)
                <article class="kpi-card {{ $metric['theme'] }}">
                    <div class="head">
                        <div class="label">{{ $metric['label'] }}</div>
                        <div class="icon">{{ $metric['icon'] }}</div>
                    </div>
                    <div class="value">{{ $metric['value'] }}</div>
                    <p class="trend">{{ $metric['trend'] }}</p>
                    <p class="comparison">{{ $metric['comparison'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="analytics-grid">
            <article class="crm-panel" style="padding:22px 20px">
                <div class="panel-head">
                    <div>
                        <h4>Leads by Source</h4>
                        <p>Primary channel mix across incoming enquiries</p>
                    </div>
                    <div class="panel-chip">Live mix</div>
                </div>

                <div class="source-layout">
                    <div class="source-ring">
                        <div class="source-donut">
                            <div class="source-donut-inner">
                                <div>
                                    <strong>55%</strong>
                                    <span>IndiaMART</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="legend-card">
                        @foreach ($dashboard['sources'] as $source)
                            <div class="legend-item">
                                <span class="dot" style="background: {{ $source['color'] }}"></span>
                                <span class="label">{{ $source['label'] }}</span>
                                <span class="value">{{ $source['percentage'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>

            <article class="crm-panel" style="padding:22px 20px">
                <div class="panel-head">
                    <div>
                        <h4>Leads Trend</h4>
                        <p>Month-wise enquiry movement</p>
                    </div>
                    <div class="panel-chip">Monthly trend</div>
                </div>

                <div class="trend-card">
                    <div class="trend-bars">
                        @foreach ($dashboard['trend'] as $point)
                            <div class="trend-col">
                                <div class="trend-value">{{ $point['value'] }}</div>
                                <div class="trend-track">
                                    <div class="trend-fill" style="height: {{ $point['bar_height'] }}%"></div>
                                </div>
                                <div class="trend-month">{{ $point['month'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>

            <article class="crm-panel" style="padding:22px 20px">
                <div class="panel-head">
                    <div>
                        <h4>Top Employees</h4>
                        <p>Best lead ownership and response output</p>
                    </div>
                    <div class="panel-chip">Top performers</div>
                </div>

                <div class="top-employee-list">
                    @foreach ($dashboard['employees'] as $employee)
                        <div class="top-employee">
                            <div class="mini-avatar {{ $employee['tone'] }}">{{ $employee['initials'] }}</div>
                            <div>
                                <h5>{{ $employee['name'] }}</h5>
                                <p>Lead owner</p>
                            </div>
                            <div class="count">{{ $employee['leads'] }} leads</div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </div>
@endsection
