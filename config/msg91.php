<?php

return [
    // Account Authkey — from MSG91 dashboard > API > Authkey.
    // Used server-side to verify the access token the widget hands back.
    'auth_key' => env('MSG91_AUTH_KEY'),

    // Widget ID and Widget Token — created under OTP > OTP Widget in the MSG91 dashboard.
    // These are safe to expose in the frontend (they identify the widget, not your account).
    'widget_id' => env('MSG91_WIDGET_ID'),
    'widget_token' => env('MSG91_WIDGET_TOKEN'),
];
