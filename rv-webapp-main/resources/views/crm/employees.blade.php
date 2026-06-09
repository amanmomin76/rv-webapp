@extends('layouts.crm')

@section('content')
    <div class="crm-panel">
        <div class="table-card-title">
            <div>
                <h4>Manage your employees</h4>
                <p>{{ $employees['body'] }}</p>
            </div>
            @if ($employees['can_add_employee'])
                <button class="primary-button" type="button">+ Add Employee</button>
            @endif
        </div>

        <div class="crm-table-wrap">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Manager</th>
                        <th>Leads</th>
                        <th>Status</th>
                        <th>Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees['rows'] as $row)
                        <tr>
                            <td>{{ $row['employee_id'] }}</td>
                            <td><strong>{{ $row['name'] }}</strong></td>
                            <td>{{ $row['email'] }}</td>
                            <td>{{ $row['phone'] }}</td>
                            <td>{{ $row['role'] }}</td>
                            <td>{{ $row['manager'] }}</td>
                            <td>{{ $row['lead_count'] }}</td>
                            <td><span class="status-pill status-pill--{{ $row['status_tone'] }}">{{ $row['status'] }}</span></td>
                            <td>{{ $row['joined_date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
