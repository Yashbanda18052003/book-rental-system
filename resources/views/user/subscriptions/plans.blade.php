@extends('user.layout')

@section('content')

<div class="mb-5 text-center fade-in-el">
    <h2 class="mb-2">Membership Plans</h2>
    <p class="text-secondary mx-auto" style="max-width: 600px;">
        Unlock premium access, increased rental limits, and special privileges by subscribing to one of our classic tiers.
    </p>
</div>

<div class="row justify-content-center fade-in-el">

@forelse($plans as $plan)

    <div class="col-md-4 mb-4">

        <div class="card h-100 p-3 text-center d-flex flex-column justify-content-between"
             style="border-top: 4px solid var(--accent-gold) !important;">

            <div class="card-body d-flex flex-column justify-content-between">

                <div>

                    <h3 class="classic-serif mb-3">
                        {{ $plan->name }}
                    </h3>

                    <div class="my-4">
                        <span class="h1 brand-accent-text fw-bold">
                            ₹{{ $plan->price }}
                        </span>

                        <span class="text-secondary">
                            / {{ strtolower($plan->duration) }}
                        </span>
                    </div>

                    <p class="text-secondary mb-4">
                        {{ $plan->description }}
                    </p>

                    <hr class="my-4 border-secondary opacity-25">

                    <ul class="list-unstyled text-start mb-4">

                        <li class="mb-3 text-secondary d-flex align-items-center">
                            <span class="brand-accent-text me-2">✔</span>
                            Rental Limit:
                            &nbsp;<strong>{{ $plan->rental_limit }} Books</strong>
                        </li>

                        <li class="mb-3 text-secondary d-flex align-items-center">
                            <span class="brand-accent-text me-2">✔</span>
                            Billing Period:
                            &nbsp;<strong>{{ ucfirst($plan->duration) }}</strong>
                        </li>

                    </ul>

                </div>

                @if(isset($activeSubscription) && $activeSubscription)

                    <button class="btn btn-secondary w-100 py-3" disabled>
                        Active Subscription Exists
                    </button>

                @else

                    <form action="{{ route('subscription.checkout', $plan->id) }}"
                          method="GET"
                          class="subscribeForm">

                        <button type="submit"
                                class="btn btn-warning w-100 py-3">
                            Subscribe Now
                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

@empty

    <div class="col-12 text-center text-secondary py-5">
        <p class="h5">No Plans Available</p>
    </div>

@endforelse

</div>

<script>
$(document).on('submit', '.subscribeForm', function(e){

    e.preventDefault();

    let form = this;

    Swal.fire({
        title: 'Proceed to Payment?',
        text: 'You will be redirected to Stripe Checkout.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Continue'
    }).then((result) => {

        if(result.isConfirmed){
            form.submit();
        }

    });

});
</script>

@endsection