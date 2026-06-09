@extends('layouts.crm')

@section('content')
    <div class="crm-panel">
        <div class="page-header-grid">
            <div>
                <p class="crm-kicker">{{ $reports['is_agent'] ? 'EMPLOYEE WORK REPORT' : 'MANAGER / OWNER REVIEW' }}</p>
                <h3>{{ $reports['title'] }}</h3>
                <p>{{ $reports['subtitle'] }}</p>
            </div>

            <div class="compact-chip">
                <div class="row">
                    <div class="icon">{{ $reports['is_agent'] ? 'SR' : 'RP' }}</div>
                    <div>
                        <p class="label">{{ $reports['is_agent'] ? 'Send To' : 'Visible' }}</p>
                        <p class="value">{{ $reports['is_agent'] ? $reports['manager_name'].' + Owner' : $reports['count_summary'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="report-summary-grid">
            @foreach ($reports['summary_cards'] as $card)
                <article class="report-summary-card">
                    <span>{{ $card['label'] }}</span>
                    <strong>{{ $card['value'] }}</strong>
                    <p>{{ $card['detail'] }}</p>
                </article>
            @endforeach
        </div>
    </div>

    @if (session('report_saved'))
        <div class="crm-panel" style="margin-top: 14px">
            <div class="crm-alert">{{ session('report_saved') }}</div>
        </div>
    @endif

    @if (session('report_reviewed'))
        <div class="crm-panel" style="margin-top: 14px">
            <div class="crm-alert">{{ session('report_reviewed') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="crm-panel" style="margin-top: 14px">
            <div class="crm-alert">
                <strong>Please fix these report fields:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($reports['is_agent'])
        <div class="detail-grid-two">
            <div class="crm-panel">
                <div class="table-card-title">
                    <div>
                        <h4>Submit Work Report</h4>
                        <p>Fill today’s working-hour output. Submitting again updates the same date report.</p>
                    </div>
                    <span class="status-pill status-pill--{{ $reports['form']['status'] === 'Not submitted' ? 'slate' : 'blue' }}">
                        {{ $reports['form']['status'] }}
                    </span>
                </div>

                @if ($reports['form']['manager_feedback'])
                    <div class="crm-alert" style="margin-bottom: 14px">
                        <strong>Manager feedback:</strong> {{ $reports['form']['manager_feedback'] }}
                    </div>
                @endif

                <form method="post" action="{{ route('reports.store') }}">
                    @csrf

                    <div class="field-list">
                        <div class="field-row">
                            <label class="field-block">
                                <span class="field-label">Report Date</span>
                                <input class="field-input" type="date" name="report_date" value="{{ old('report_date', $reports['form']['report_date']) }}" required>
                            </label>
                            <label class="field-block">
                                <span class="field-label">Leads Contacted</span>
                                <input class="field-input" type="number" min="0" max="999" name="leads_contacted" value="{{ old('leads_contacted', $reports['form']['leads_contacted']) }}" required>
                            </label>
                        </div>

                        <div class="field-row">
                            <label class="field-block">
                                <span class="field-label">Work Started</span>
                                <input class="field-input" type="time" name="work_started_at" value="{{ old('work_started_at', $reports['form']['work_started_at']) }}">
                            </label>
                            <label class="field-block">
                                <span class="field-label">Work Ended</span>
                                <input class="field-input" type="time" name="work_ended_at" value="{{ old('work_ended_at', $reports['form']['work_ended_at']) }}">
                            </label>
                        </div>

                        <div class="report-metric-inputs">
                            <label class="field-block">
                                <span class="field-label">Follow Ups Completed</span>
                                <input class="field-input" type="number" min="0" max="999" name="follow_ups_completed" value="{{ old('follow_ups_completed', $reports['form']['follow_ups_completed']) }}" required>
                            </label>
                            <label class="field-block">
                                <span class="field-label">Notes Added</span>
                                <input class="field-input" type="number" min="0" max="999" name="notes_added" value="{{ old('notes_added', $reports['form']['notes_added']) }}" required>
                            </label>
                            <label class="field-block">
                                <span class="field-label">Quotations Shared</span>
                                <input class="field-input" type="number" min="0" max="999" name="quotations_shared" value="{{ old('quotations_shared', $reports['form']['quotations_shared']) }}" required>
                            </label>
                        </div>

                        <div class="report-metric-inputs">
                            <label class="field-block">
                                <span class="field-label">Calls Made</span>
                                <input class="field-input" type="number" min="0" max="999" name="calls_made" value="{{ old('calls_made', $reports['form']['calls_made']) }}" required>
                            </label>
                            <label class="field-block">
                                <span class="field-label">WhatsApp Messages</span>
                                <input class="field-input" type="number" min="0" max="999" name="whatsapp_messages" value="{{ old('whatsapp_messages', $reports['form']['whatsapp_messages']) }}" required>
                            </label>
                            <label class="field-block">
                                <span class="field-label">Emails Sent</span>
                                <input class="field-input" type="number" min="0" max="999" name="emails_sent" value="{{ old('emails_sent', $reports['form']['emails_sent']) }}" required>
                            </label>
                        </div>

                        <label class="field-block full">
                            <span class="field-label">Today Work Summary</span>
                            <textarea class="field-textarea" name="summary" placeholder="Example: Called assigned leads, shared quotation, updated follow-up status..." required>{{ old('summary', $reports['form']['summary']) }}</textarea>
                        </label>

                        <label class="field-block full">
                            <span class="field-label">Issues / Blockers</span>
                            <textarea class="field-textarea" name="issues" placeholder="Customer pending, quotation approval, stock issue, technical query...">{{ old('issues', $reports['form']['issues']) }}</textarea>
                        </label>

                        <label class="field-block full">
                            <span class="field-label">Tomorrow Plan</span>
                            <textarea class="field-textarea" name="tomorrow_plan" placeholder="What you will close or follow up tomorrow">{{ old('tomorrow_plan', $reports['form']['tomorrow_plan']) }}</textarea>
                        </label>
                    </div>

                    <div class="toolbar-actions" style="margin-top: 16px">
                        <button class="primary-button" type="submit">Submit Report</button>
                    </div>
                </form>
            </div>

            <div class="crm-panel">
                <div class="table-card-title">
                    <div>
                        <h4>Auto Work Snapshot</h4>
                        <p>CRM counts that help employees fill accurate reports.</p>
                    </div>
                    <div class="summary-chip">Live from MySQL</div>
                </div>

                <div class="report-snapshot-list">
                    <div>
                        <span>Assigned leads</span>
                        <strong>{{ $reports['auto_metrics']['assigned_leads'] }}</strong>
                    </div>
                    <div>
                        <span>Pending follow ups</span>
                        <strong>{{ $reports['auto_metrics']['pending_follow_ups'] }}</strong>
                    </div>
                    <div>
                        <span>Completed today</span>
                        <strong>{{ $reports['auto_metrics']['completed_follow_ups_today'] }}</strong>
                    </div>
                    <div>
                        <span>Notes added today</span>
                        <strong>{{ $reports['auto_metrics']['notes_added_today'] }}</strong>
                    </div>
                </div>

                <div class="crm-panel" style="margin-top: 16px; padding: 16px">
                    <h4>How this works</h4>
                    <p style="margin: 8px 0 0; color: var(--muted); font-size: 13px">
                        Employee submits once per day. Manager sees only their team reports, while owner sees all reports.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="crm-panel" style="margin-top: 14px">
            <form class="toolbar-form" method="get" action="{{ route('reports.index') }}">
                <div class="grow">
                    <input class="crm-input" type="text" name="search" value="{{ $reports['filters']['search'] }}" placeholder="Search employee, email, summary...">
                </div>

                <div style="flex: 1 1 220px">
                    <select class="crm-select" name="status">
                        @foreach ($reports['status_options'] as $option)
                            <option value="{{ $option }}" @selected($reports['filters']['status'] === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="toolbar-actions">
                    <button class="secondary-button" type="submit">Apply Filters</button>
                </div>
            </form>
        </div>
    @endif

    <div class="crm-panel" style="margin-top: 14px">
        <div class="table-card-title">
            <div>
                <h4>{{ $reports['is_agent'] ? 'My Submitted Reports' : 'Employee Reports' }}</h4>
                <p>{{ $reports['is_agent'] ? 'Your recent report history and review status.' : 'Manager and owner review queue for daily employee output.' }}</p>
            </div>
            <div class="summary-chip">{{ $reports['count_summary'] }}</div>
        </div>

        <div class="crm-table-wrap">
            <table class="crm-table report-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        @unless ($reports['is_agent'])
                            <th>Employee</th>
                            <th>Manager</th>
                        @endunless
                        <th>Hours</th>
                        <th>Activity</th>
                        <th>Status</th>
                        <th>Summary</th>
                        @if ($reports['can_review'])
                            <th>Review</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports['rows'] as $row)
                        <tr>
                            <td>{{ $row['report_date'] }}</td>
                            @unless ($reports['is_agent'])
                                <td>
                                    <strong>{{ $row['employee'] }}</strong>
                                    <small>{{ $row['employee_email'] }}</small>
                                </td>
                                <td>{{ $row['manager'] }}</td>
                            @endunless
                            <td>{{ $row['hours'] }}</td>
                            <td>
                                <strong>{{ $row['activity_total'] }} actions</strong>
                                <small>{{ $row['leads_contacted'] }} leads · {{ $row['follow_ups_completed'] }} follow ups · {{ $row['quotations_shared'] }} quotes</small>
                            </td>
                            <td><span class="status-pill status-pill--{{ $row['status_tone'] }}">{{ $row['status'] }}</span></td>
                            <td>
                                <strong>{{ $row['summary'] }}</strong>
                                <small>{{ $row['issues'] }}</small>
                                @if ($row['manager_feedback'])
                                    <small>Feedback: {{ $row['manager_feedback'] }}</small>
                                @endif
                            </td>
                            @if ($reports['can_review'])
                                <td>
                                    <form class="report-review-form" method="post" action="{{ route('reports.review', ['report' => $row['id']]) }}">
                                        @csrf
                                        <select class="crm-select compact-select" name="status" aria-label="Review status for {{ $row['employee'] }}">
                                            @foreach ($reports['review_status_options'] as $option)
                                                <option value="{{ $option }}" @selected($row['status'] === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        <textarea class="crm-textarea" name="manager_feedback" placeholder="Feedback for employee">{{ $row['manager_feedback'] }}</textarea>
                                        <button class="secondary-button compact-button" type="submit">Update</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $reports['can_review'] ? 8 : 5 }}">No reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
