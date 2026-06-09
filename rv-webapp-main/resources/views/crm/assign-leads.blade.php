@extends('layouts.crm')

@section('content')
    @if ($assignLeads['can_view_intake'])
        <div class="crm-panel assignment-overview">
            <div class="table-card-title">
                <div>
                    <h4>Live Lead Intake</h4>
                    <p>Connect marketplace enquiries, normalize them, then route each lead to a manager and employee.</p>
                </div>
                <div class="summary-chip">API-ready queue</div>
            </div>

            <div class="integration-grid">
                @foreach ($assignLeads['integration_cards'] as $card)
                    <div class="integration-card">
                        <div class="integration-card-head">
                            <span class="status-pill status-pill--{{ $card['tone'] }}">{{ $card['source'] }}</span>
                            <strong>{{ $card['count'] }} leads</strong>
                        </div>
                        <p class="integration-method">{{ $card['method'] }}</p>
                        <p>{{ $card['detail'] }}</p>
                        <span>{{ $card['state'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="assignment-flow">
                @foreach ($assignLeads['assignment_flow'] as $step)
                    <div class="assignment-step">
                        <span>{{ $step['step'] }}</span>
                        <strong>{{ $step['label'] }}</strong>
                        <p>{{ $step['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="crm-panel">
        @if (session('assignment_updated'))
            <div class="crm-alert">{{ session('assignment_updated') }}</div>
        @endif

        <form class="toolbar-form" method="get" action="{{ route('assign-leads.index') }}">
            <div class="grow">
                <input
                    class="crm-input"
                    type="text"
                    name="search"
                    value="{{ $assignLeads['filters']['search'] }}"
                    placeholder="Search leads..."
                >
            </div>

            <div style="flex: 1 1 180px">
                <select class="crm-select" name="source">
                    @foreach ($assignLeads['source_options'] as $option)
                        <option value="{{ $option }}" @selected($assignLeads['filters']['source'] === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1 1 180px">
                <select class="crm-select" name="status">
                    @foreach ($assignLeads['status_options'] as $option)
                        <option value="{{ $option }}" @selected($assignLeads['filters']['status'] === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            @if ($assignLeads['can_filter_manager'])
                <div style="flex: 1 1 180px">
                    <select class="crm-select" name="manager">
                        @foreach ($assignLeads['manager_options'] as $option)
                            <option value="{{ $option }}" @selected($assignLeads['filters']['manager'] === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($assignLeads['can_filter_owner'])
                <div style="flex: 1 1 180px">
                    <select class="crm-select" name="owner">
                        @foreach ($assignLeads['owner_options'] as $option)
                            <option value="{{ $option }}" @selected($assignLeads['filters']['owner'] === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="toolbar-actions">
                <button class="secondary-button" type="submit">Apply Filters</button>
                @if ($assignLeads['can_add_lead'])
                    <a class="primary-button" href="{{ route('leads.create') }}">+ Add Lead</a>
                @endif
            </div>
        </form>
    </div>

    <div class="crm-panel" style="margin-top: 14px">
        <div class="table-card-title">
            <div>
                <h4>{{ $assignLeads['assignment_title'] }}</h4>
                <p>{{ $assignLeads['assignment_subtitle'] }}</p>
            </div>
            <div class="summary-chip">{{ $assignLeads['count_summary'] }}</div>
        </div>

        @if ($assignLeads['can_view_routing_rules'])
            <div class="routing-rules">
                @foreach ($assignLeads['routing_rules'] as $rule)
                    <div>
                        <strong>{{ $rule['rule'] }}</strong>
                        <span>{{ $rule['owner'] }}</span>
                        <p>{{ $rule['reason'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="crm-table-wrap">
            <table class="crm-table assignment-table">
                <thead>
                    <tr>
                        <th>Lead ID</th>
                        <th>Customer Name</th>
                        <th>Company</th>
                        <th>Mobile</th>
                        <th>Source</th>
                        <th>Platform Ref</th>
                        <th>Status</th>
                        <th>Manager</th>
                        <th>Employee</th>
                        <th>Created Date</th>
                        <th>Assign</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assignLeads['rows'] as $row)
                        <tr>
                            <td>{{ $row['lead_id'] }}</td>
                            <td>
                                <a class="table-link" href="{{ route('leads.show', ['leadId' => $row['lead_id']]) }}">
                                    {{ $row['customer_name'] }}
                                </a>
                            </td>
                            <td>{{ $row['company'] }}</td>
                            <td>{{ $row['mobile'] }}</td>
                            <td><span class="status-pill status-pill--{{ $row['source_tone'] }}">{{ $row['source'] }}</span></td>
                            <td>
                                <div class="platform-ref">{{ $row['platform_ref'] }}</div>
                                <small>{{ $row['assignment_rule'] }}</small>
                            </td>
                            <td><span class="status-pill status-pill--{{ $row['status_tone'] }}">{{ $row['status'] }}</span></td>
                            <td>{{ $row['manager'] }}</td>
                            <td>{{ $row['assigned_to'] }}</td>
                            <td>{{ $row['created_date'] }}</td>
                            <td>
                                <form class="assignment-form" method="post" action="{{ route('assign-leads.assignment.update', ['lead' => $row['id']]) }}">
                                    @csrf
                                    @if ($assignLeads['can_assign_manager'])
                                        <select class="crm-select compact-select" name="manager_user_id" aria-label="Manager for {{ $row['lead_id'] }}">
                                            <option value="">Select manager</option>
                                            @foreach ($assignLeads['manager_users'] as $manager)
                                                <option value="{{ $manager['id'] }}" @selected((int) $row['manager_user_id'] === $manager['id'])>
                                                    {{ $manager['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif

                                    @if ($assignLeads['can_assign_employee'])
                                        <select class="crm-select compact-select" name="assigned_to_user_id" aria-label="Employee for {{ $row['lead_id'] }}">
                                            <option value="">Select employee</option>
                                            @foreach ($assignLeads['employee_users'] as $employee)
                                                <option value="{{ $employee['id'] }}" @selected((int) $row['assigned_to_user_id'] === $employee['id'])>
                                                    {{ $employee['name'] }} - {{ $employee['role'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <select class="crm-select compact-select" name="status" aria-label="Status for {{ $row['lead_id'] }}">
                                        @foreach ($assignLeads['status_options'] as $option)
                                            @continue($option === 'All Statuses')
                                            <option value="{{ $option }}" @selected($row['status'] === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>

                                    <button class="secondary-button compact-button" type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
