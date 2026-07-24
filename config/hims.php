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
    | Post-midnight grace: admissions from 00:00 until this boundary on the same
    | calendar day are not billable until the boundary (see BedBillingPeriod).
    |
    */
    'bed_billing_hour' => (int) env('BED_BILLING_HOUR', 11),
    'bed_billing_minute' => (int) env('BED_BILLING_MINUTE', 0),

    /*
    |--------------------------------------------------------------------------
    | Insurance discharge bed charge
    |--------------------------------------------------------------------------
    |
    | For insurance IPD, discharge-day bed charge is excluded unless discharge
    | time is at or after this hour (default 15 = 3:00 PM).
    |
    */
    'insurance_discharge_bed_charge_after_hour' => (int) env('INSURANCE_DISCHARGE_BED_CHARGE_AFTER_HOUR', 15),

];
