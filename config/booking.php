<?php
return [
    'booking_route_prefix'=>env("BOOKING_ROUTER_PREFIX",'booking'),
    'default_payment_gateway' =>env("DEFAULT_PAYMENT_GATEWAY",'rozarpay'),
    'default_payment_currency' =>env("DEFAULT_PAYMENT_CURRENCY",'INR'),
    'statuses'=>[
        'completed',
        'processing',
        'confirmed',
        'cancelled',
        'paid',
        'unpaid'
    ],
];
