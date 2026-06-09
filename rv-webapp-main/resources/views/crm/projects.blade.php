@extends('layouts.crm')

@section('content')
    <div class="crm-panel">
        <form class="toolbar-form" method="get" action="{{ route('projects.index') }}">
            <input type="hidden" name="tab" value="{{ $projects['tab'] }}">
            <div class="grow">
                <input
                    class="crm-input"
                    type="text"
                    name="search"
                    value="{{ $projects['filters']['search'] }}"
                    placeholder="Search projects..."
                >
            </div>

            <div style="flex: 1 1 180px">
                <select class="crm-select" name="status">
                    @foreach ($projects['status_options'] as $option)
                        <option value="{{ $option }}" @selected($projects['filters']['status'] === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1 1 180px">
                <select class="crm-select" name="owner">
                    @foreach ($projects['owner_options'] as $option)
                        <option value="{{ $option }}" @selected($projects['filters']['owner'] === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="toolbar-actions">
                <button class="secondary-button" type="submit">Apply Filters</button>
                @if ($projects['can_add_project'])
                    <a class="primary-button" href="{{ route('leads.create') }}">+ Add Project</a>
                @endif
                <a class="{{ $projects['tab'] === 'completed' ? 'success-button' : 'ghost-button' }}" href="{{ route('projects.index', ['tab' => 'completed']) }}">Completed Projects</a>
                <a class="{{ $projects['tab'] === 'active' ? 'secondary-button' : 'ghost-button' }}" href="{{ route('projects.index', ['tab' => 'active']) }}">Projects List</a>
            </div>
        </form>
    </div>

    @if ($projects['tab'] === 'completed')
        <div class="crm-panel" style="margin-top: 14px">
            <div class="table-card-title">
                <div>
                    <h4>Completed Projects</h4>
                    <p>Finished project work shown as quick review cards.</p>
                </div>
                <div class="summary-chip">{{ $projects['completed_count_summary'] }}</div>
            </div>

            <div class="completed-grid">
                @foreach ($projects['completed_cards'] as $project)
                    <a class="completed-card" href="{{ route('projects.index', ['tab' => 'completed', 'selected' => $project['project_id']]) }}">
                        <h5>{{ $project['project_name'] }}</h5>
                        <p>{{ $project['customer'] }}</p>
                        <div class="foot">
                            <span class="status-pill status-pill--{{ $project['po_tone'] }}">{{ $project['po_status'] }}</span>
                            <span class="status-pill status-pill--{{ $project['project_tone'] }}">{{ $project['project_status'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="project-layout" style="margin-top: 14px">
        <div class="crm-panel">
            <div class="table-card-title">
                <div>
                    <h4>{{ $projects['tab'] === 'completed' ? 'Completed Project Records' : 'Projects List' }}</h4>
                    <p>{{ $projects['page_summary'] }}</p>
                </div>
                <div class="summary-chip">
                    {{ $projects['tab'] === 'completed' ? $projects['completed_count_summary'] : $projects['active_count_summary'] }}
                </div>
            </div>

            <div class="crm-table-wrap">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Project ID</th>
                            <th>Project Name</th>
                            <th>Customer</th>
                            <th>PO Status</th>
                            <th>Project Status</th>
                            <th>Value</th>
                            <th>Delivery Date</th>
                            <th>Owner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects['rows'] as $row)
                            <tr>
                                <td>{{ $row['project_id'] }}</td>
                                <td>
                                    <a class="table-link" href="{{ route('projects.index', ['tab' => $projects['tab'], 'selected' => $row['project_id'], 'search' => $projects['filters']['search'], 'status' => $projects['filters']['status'], 'owner' => $projects['filters']['owner']]) }}">
                                        {{ $row['project_name'] }}
                                    </a>
                                </td>
                                <td>{{ $row['customer'] }}</td>
                                <td><span class="status-pill status-pill--{{ $row['po_tone'] }}">{{ $row['po_status'] }}</span></td>
                                <td><span class="status-pill status-pill--{{ $row['project_tone'] }}">{{ $row['project_status'] }}</span></td>
                                <td>{{ $row['value'] }}</td>
                                <td>{{ $row['delivery_date'] }}</td>
                                <td>{{ $row['owner'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($projects['selected_project'])
            <aside class="drawer-card">
                <h4>Project Details</h4>
                <p class="subtle">{{ $projects['selected_project']['company_name'] }}</p>

                <div class="hero-badges" style="margin-top: 14px">
                    <span class="status-pill status-pill--{{ $projects['selected_project']['po_tone'] }}">{{ $projects['selected_project']['po_status'] }}</span>
                    <span class="status-pill status-pill--{{ $projects['selected_project']['project_tone'] }}">{{ $projects['selected_project']['project_status'] }}</span>
                </div>

                <div class="drawer-actions">
                    <a
                        class="secondary-button"
                        href="{{ route('leads.show', ['leadId' => $projects['selected_project']['inquiry_id'], 'origin' => $projects['tab'] === 'completed' ? 'completed-projects' : 'projects', 'project' => $projects['selected_project']['project_id']]) }}"
                    >
                        Lead Details
                    </a>
                    <button class="success-button" type="button">Set Reminder</button>
                </div>

                <div class="detail-card" style="margin-top: 16px; padding: 18px 16px">
                    <div class="field-list">
                        <div class="field-row">
                            <div class="field-block">
                                <span class="field-label">Inquiry ID</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['inquiry_id'] }}" readonly>
                            </div>
                            <div class="field-block">
                                <span class="field-label">Inquiry Date & Time</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['inquiry_date_time'] }}" readonly>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field-block">
                                <span class="field-label">Customer Name</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['customer_name'] }}" readonly>
                            </div>
                            <div class="field-block">
                                <span class="field-label">Mobile Number</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['mobile_number'] }}" readonly>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field-block">
                                <span class="field-label">Email Address</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['email_address'] }}" readonly>
                            </div>
                            <div class="field-block">
                                <span class="field-label">Company Name</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['company_name'] }}" readonly>
                            </div>
                        </div>
                        <div class="field-block full">
                            <span class="field-label">City / State / Country</span>
                            <input class="field-input" type="text" value="{{ $projects['selected_project']['city_state_country'] }}" readonly>
                        </div>
                        <div class="field-row">
                            <div class="field-block">
                                <span class="field-label">Product Name</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['product_name'] }}" readonly>
                            </div>
                            <div class="field-block">
                                <span class="field-label">Product Category</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['product_category'] }}" readonly>
                            </div>
                        </div>
                        <div class="field-block full">
                            <span class="field-label">Requirement Message</span>
                            <textarea class="field-textarea" readonly>{{ $projects['selected_project']['requirement_message'] }}</textarea>
                        </div>
                        <div class="field-row">
                            <div class="field-block">
                                <span class="field-label">Lead Source</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['lead_source'] }}" readonly>
                            </div>
                            <div class="field-block">
                                <span class="field-label">Inquiry Status</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['inquiry_status'] }}" readonly>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field-block">
                                <span class="field-label">Buyer Type</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['buyer_type'] }}" readonly>
                            </div>
                            <div class="field-block">
                                <span class="field-label">GST / Website</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['gst_or_website'] }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-card" style="margin-top: 16px; padding: 18px 16px">
                    <h4 style="margin-bottom: 14px">Set a Reminder</h4>
                    <div class="field-list">
                        <div class="field-block full">
                            <span class="field-label">Subject</span>
                            <input class="field-input" type="text" value="Follow up">
                        </div>
                        <div class="field-block full">
                            <span class="field-label">Company (from selected project)</span>
                            <input class="field-input" type="text" value="{{ $projects['selected_project']['company_name'] }}" readonly>
                        </div>
                        <div class="field-row">
                            <div class="field-block">
                                <span class="field-label">Reminder Date</span>
                                <input class="field-input" type="text" value="{{ $projects['selected_project']['delivery_date'] }}">
                            </div>
                            <div class="field-block">
                                <span class="field-label">Reminder Time</span>
                                <input class="field-input" type="text" value="11:00 AM">
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        @endif
    </div>
@endsection
