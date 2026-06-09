@extends('layouts.crm')

@section('content')
    <div class="crm-panel">
        <div class="toolbar-row">
            <div class="grow">
                <p class="helper-copy" style="margin:0">{{ $followUps['subtitle'] }}</p>
            </div>

            <form class="toolbar-row" method="get" action="{{ route('follow-ups.index') }}">
                <div style="min-width: 260px">
                    <input
                        class="crm-input"
                        type="text"
                        name="search"
                        value="{{ $followUps['filters']['search'] }}"
                        placeholder="Search follow ups..."
                    >
                </div>
                <div class="summary-chip">{{ $followUps['count_summary'] }}</div>
                <button class="primary-button" type="submit">Filter</button>
                <button class="secondary-button" type="button">+ Add Follow-up</button>
            </form>
        </div>
    </div>

    <div class="crm-panel" style="margin-top: 18px">
        <div class="table-card-title">
            <div>
                <h4>Follow-up List</h4>
                <p>{{ $followUps['count_summary'] }}</p>
            </div>
        </div>

        <div class="crm-table-wrap">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Lead Name</th>
                        <th>Follow-up Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Note</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($followUps['rows'] as $row)
                        <tr>
                            <td>
                                <a class="table-link" href="{{ route('leads.show', ['leadId' => $row['lead_id'], 'origin' => 'follow-ups']) }}">
                                    {{ $row['lead_name'] }}
                                </a>
                            </td>
                            <td>{{ $row['follow_up_date'] }}</td>
                            <td>{{ $row['time'] }}</td>
                            <td>{{ $row['type'] }}</td>
                            <td>{{ $row['note'] }}</td>
                            <td>{{ $row['assigned_to'] }}</td>
                            <td><span class="status-pill status-pill--{{ $row['status_tone'] }}">{{ $row['status'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
