<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bed billing day boundary
    |--------------------------------------------------------------------------
    |
    | Each "charge day" row uses the window: (previous calendar day at this time,
    | current calendar day at this time], i.e. 11:00 yesterday → 11:00 today.
    |
    */
    'bed_billing_hour' => (int) env('BED_BILLING_HOUR', 11),
    'bed_billing_minute' => (int) env('BED_BILLING_MINUTE', 0),

];
