@extends('admin.layout')

@section('content')

<h2>Activity Logs</h2>

<table class="table table-bordered">

    <thead>
        <tr>
            <th>User</th>
            <th>Action</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>

    @forelse($logs as $log)

        <tr>
            <td>{{ $log->user_name }}</td>
            <td>{{ $log->action }}</td>
            <td>{{ $log->description }}</td>
            <td>{{ $log->created_at }}</td>
        </tr>

    @empty

        <tr>
            <td colspan="4" class="text-center">
                No Activity Found
            </td>
        </tr>

    @endforelse

    </tbody>

</table>

{{ $logs->links() }}

@endsection