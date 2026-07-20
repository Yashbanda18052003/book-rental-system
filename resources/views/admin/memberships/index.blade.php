@extends('admin.layout')

@section('content')

<h2>Membership Plans</h2>

<a href="{{ route('plans.create') }}"
   class="btn btn-primary mb-3">
   Add Plan
</a>

<table class="table table-bordered">

    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Duration</th>
            <th>Rental Limit</th>
        </tr>
    </thead>

    <tbody>

    @forelse($plans as $plan)

        <tr>
            <td>{{ $plan->name }}</td>
            <td>₹{{ $plan->price }}</td>
            <td>{{ ucfirst($plan->duration) }}</td>
            <td>{{ $plan->rental_limit }}</td>
        </tr>

    @empty

        <tr>
            <td colspan="4" class="text-center">
                No Plans Found
            </td>
        </tr>

    @endforelse

    </tbody>

</table>

@endsection