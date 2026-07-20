@extends('admin.layout')

@section('content')

<h2>Subscription Management</h2>

<div class="d-flex justify-content-between mb-3">

    <form method="GET"
          action="{{ route('admin.subscriptions') }}"
          class="d-flex gap-2">

        <input type="text"
               name="search"
               class="form-control"
               placeholder="Search User, Plan or Status"
               value="{{ request('search') }}">

        <button class="btn btn-primary">
            Search
        </button>

    </form>

</div>

<table class="table table-bordered">

    <thead>
        <tr>
          <th>User</th>
<th>Plan</th>
<th>Status</th>
<th>End Date</th>
<th>Action</th>
        </tr>
    </thead>

   <tbody>

@forelse($subscriptions as $subscription)

<tr>

    <td>{{ $subscription->user->name }}</td>

    <td>{{ $subscription->plan->name }}</td>

    <td>
        {{ ucfirst($subscription->status) }}
    </td>

    <td>
        {{ $subscription->end_date }}
    </td>

    <td>

        @if($subscription->status == 'active')

            <form action="{{ route('admin.subscription.cancel',$subscription->id) }}"
                  method="POST">

                @csrf

                <button class="btn btn-danger btn-sm">
                    Cancel
                </button>

            </form>

        @endif

    </td>

</tr>

@empty

<tr>
    <td colspan="5" class="text-center">
        No Subscriptions Found
    </td>
</tr>

@endforelse

</tbody>

</table>
<div class="mt-3">
    {{ $subscriptions->links() }}
</div>

<script>

let timer;

$('input[name="search"]').on('keyup', function () {

    clearTimeout(timer);

    timer = setTimeout(() => {

        $(this).closest('form').submit();

    }, 500);

});

</script>
@endsection