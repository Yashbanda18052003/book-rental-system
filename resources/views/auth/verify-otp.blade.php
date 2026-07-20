<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Phone | BookNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon"
      type="image/png"
      href="{{ asset('images/booknest-logo.png') }}">
</head>
<body class="auth-page">
<div class="container fade-in-el">
    <div class="auth-split-container">

        <!-- Form Side -->
        <div class="auth-form-side">
            <div class="text-center mb-4">
                <img src="{{ asset('images/booknest-logo.png') }}"
                     alt="BookNest"
                     width="100"
                     height="100"
                     class="mb-3 rounded-circle border border-3 border-warning shadow">

                <h2 class="brand-accent-text mb-1">Verify Your Phone</h2>
                <p class="text-secondary">
                    We're sending a one-time code to
                    <strong>{{ $user->phone }}</strong> via WhatsApp.
                </p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div id="otpStatus" class="alert alert-info d-none"></div>

            <form id="otpForm" method="POST" action="{{ route('verify.phone.post') }}">
                @csrf
                <input type="hidden" name="access_token" id="access_token">

                <div class="mb-3">
                    <label class="form-label">Enter OTP <span class="text-danger">*</span></label>
                    <input type="text"
                           id="otpInput"
                           class="form-control"
                           inputmode="numeric"
                           maxlength="6"
                           placeholder="6-digit code"
                           autocomplete="one-time-code">
                </div>

                <button type="button" id="verifyBtn" class="btn btn-warning w-100 mb-2">
                    Verify & Activate Account
                </button>

                <button type="button" id="resendBtn" class="btn btn-outline-light w-100">
                    Resend OTP
                </button>
            </form>

            <div class="text-center mt-3">
                <span class="text-secondary small">Wrong number?</span>
                <a href="{{ route('register') }}" class="brand-accent-text text-decoration-none small fw-bold ms-1">
                    Register again
                </a>
            </div>
        </div>

        <!-- Image Illustration Side -->
        <div class="auth-image-side">
            <div class="auth-image-content">
                <h2>One Last Step</h2>
                <p>Verifying your phone number lets us reach you for rental confirmations, due-date reminders, and fine alerts.</p>
            </div>
        </div>
    </div>
</div>

{{--
    MSG91 OTP Widget — Custom UI mode.
    widgetId / tokenAuth come from your MSG91 dashboard (OTP > OTP Widget) and
    are safe to expose client-side; they identify the widget, not your account.
--}}
<script>
    var configuration = {
        widgetId: "{{ config('msg91.widget_id') }}",
        tokenAuth: "{{ config('msg91.widget_token') }}",
        identifier: "91{{ $user->phone }}", // country code + number, no '+'
        exposeMethods: true,
        success: function (data) {
            // data.message is the access-token — send it to our server to verify
            document.getElementById('access_token').value = data.message;
            document.getElementById('otpForm').submit();
        },
        failure: function (error) {
            var status = document.getElementById('otpStatus');
            status.classList.remove('d-none', 'alert-info');
            status.classList.add('alert-danger');
            status.textContent = 'Verification failed. Check the code and try again.';
        }
    };
</script>
<script src="https://verify.msg91.com/otp-provider.js"
        onload="initSendOTP(configuration)"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var status = document.getElementById('otpStatus');

        function showStatus(msg, type) {
            status.classList.remove('d-none', 'alert-info', 'alert-danger', 'alert-success');
            status.classList.add('alert-' + type);
            status.textContent = msg;
        }

        // Auto-trigger sending the OTP the moment the page loads.
        window.addEventListener('load', function () {
            if (typeof window.sendOtp === 'function') {
                window.sendOtp(
                    "91{{ $user->phone }}",
                    function () { showStatus('OTP sent on WhatsApp. Enter the code below.', 'success'); },
                    function () { showStatus('Could not send OTP. Try Resend.', 'danger'); }
                );
            }
        });

        document.getElementById('verifyBtn').addEventListener('click', function () {
            var code = document.getElementById('otpInput').value.trim();
            if (!code) {
                showStatus('Enter the code sent to your phone.', 'danger');
                return;
            }
            window.verifyOtp(
                code,
                function () { /* handled by configuration.success above */ },
                function () { showStatus('Incorrect or expired code. Try again.', 'danger'); }
            );
        });

        document.getElementById('resendBtn').addEventListener('click', function () {
            window.retryOtp(
                null,
                function () { showStatus('OTP resent.', 'success'); },
                function () { showStatus('Could not resend right now. Wait a moment and try again.', 'danger'); }
            );
        });
    });
</script>

</body>
</html>
