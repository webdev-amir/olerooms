<?php

use Illuminate\Support\Arr;

return [

    /*
    |--------------------------------------------------------------------------
    | custom data records
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */
    'default_date_formate' => 'm/d/Y',
    'backed_campaign_formate' => 'd M, Y',
    'default_date_time_formate' => 'd/m/Y h:i A',
    'default_date_time_only_formate' => 'd/m/Y h:i',
    'default_time_formate' => 'h:i A',
    'default_month_time_formate' => 'M Y',
    'is_loacation_required_for_search'  => false,
    'default-search-radious-miles' => env('DEFAULT_RADIUS', 5), //KM
    'default_latitude' => 0,
    'default_longitude' => 0,
    'default_miles' => 0.621371192,
    'default_pagination' => 10,
    'is_company_logo_show' => true,
    'filter_booking_status'  => array('pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'upcoming' => 'Upcoming', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'),
    'filter_schedulevisit_status'  => array('confirmed' => 'Pending', 'cancelled' => 'Cancelled'),
    'filter_property_status'  => array('featured' => 'Featured', 'pending' => 'Pending', 'publish' => 'Publish', 'reject' => 'Reject'),
    'payment_search_status'  => array('active' => 'Active Accounts', 'suspended' => 'Suspended Accounts'),
    'state_search_status'  => array('active' => 'Active', 'inactive' => 'In-Active'),
    'area_search_status'  => array('active' => 'Active', 'inactive' => 'In-Active'),
    'filter_city_status'  => array('active' => 'Active', 'inactive' => 'In-Active', 'coming' => 'Coming Soon'),
    'property_status'  => array('pending' => 'Pending', 'publish' => 'Publish', 'reject' => 'Reject'),
    'currency-sign' => env('CURRENCY_SIGN', '₹'),
    'vendor_selfie_agreement_status' => array('pending' => 'Pending', 'approved' => 'Approve', 'rejected' => 'Reject'),
    'gender-list' => array('male' => 'Male', 'female' => 'Female', 'other' => 'Other'),
    'marital-status-list' => array('unmarried' => 'Unmarried', 'married' => 'Married'),
    'occupation-list' => array('student' => 'Student', 'working-professional' => 'Working Professional'),
    'is_customer_page' => array('search', 'property'),

    'all_available_search_filter' => array(
        'state_filter',
        'city_filter',
        'location_filter',
        'property_type_filter',
        'occupancy_filter',
        'room_ac_type_filter',
        'flat_size_filter',
        'furniture_filter',
        'price_filter',
        'rating_filter',
        'available_for_filter',
        'check_in_filter',
        'check_out_filter',
        'room_standard_filter',
        'no_of_room_filter',
        'guest_capacity_filter',
        'bhk_type_filter',
        'guests_capacity'
    ),

    'occupancy_filter' =>  array(
        'single' => 'Single',
        'double' => 'Double',
        'triple' => 'Triple',
        'quadruple' => 'Quadruple'
    ),

    'price_filter' => array(
        '0-2000' => '₹0 - ₹2,000',
        '2000-5000' => '₹2,000 - ₹5,000',
        '5000-7500' => '₹5,000 - ₹7,500',
        '7500-10000' => '₹7,500 - ₹10,000',
        '10000-15000' => '₹10,000 - ₹15,000',
        '15000' => '₹15000+',
    ),

    'rating_filter' => array(
        '1' => '1+ Stars ',
        '2' => '2+ Stars ',
        '3' => '3+ Stars ',
        '4' => '4+ Stars',
        '5' => '5+ Stars'
    ),

    'flat_size_filter' => array(
        '1' => '1 BHK',
        '2' => '2 BHK',
        '3' => '3 BHK',
        '4' => '4 BHK',
        '5' => '5 BHK'
    ),

    'room_no_filter' => array(
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
    ),


    'capacity_filter' => array(
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
    ),

    'furniture_filter' => array(
        'furnished' => 'Furnished',
        'not_furnished' => 'Not Furnished',
        'semi_furnished' => 'Semi Furnished'
    ),

    'room_ac_type_filter' => array(
        'ac' => 'AC',
        'non-ac' => 'Non AC'
    ),

    'room_standard_filter' => array(
        'standard' => 'Standard',
        'deluxe' => 'Deluxe',
        'suite' => 'Suite'
    ),

    'search_sort_by' => array(
        'price-low-to-high' => 'Price Low to High',
        'price-high-to-low' => 'Price High to Low',
        'a-to-z' => 'Alphabetically (A-Z)',
        'z-to-a' => 'Alphabetically (Z-A)',
        'recommended' => 'Most Recommended',
    ),

    'default_sort_by' => 'a-to-z',

    'search_layout' => array(
        'grid' => 'Grid View',
        'row' => 'Row View',
    ),


    'homestay_type' => array('farm-house' => 'Farm House', 'flat' => 'Flat', 'resort' => 'Resort'),
    'hostelpg_room_types' => array('single', 'double', 'triple', 'quadruple'),
    'hotel_room_types' => array('standard', 'deluxe', 'suite'),
    'guest_capacity' => array(),

    'default_search_layout' => 'grid',
    'row_search_layout' => 'row',
    'property_available_for' => array('onlyboys' => 'Boys', 'onlygirls' => 'Girls', 'family' => 'Family', 'coliving' => 'Coliving'),
    'bhk_type' => array('1bhk' => '1BHK', '2bhk' => '2BHK', '2bhk' => '2BHK', '3bhk' => '3BHK', '4bhk' => '4BHK', '5bhk' => '5BHK', '6bhk' => '6BHK', '7bhk' => '7BHK'),
    'default_propery_type_search' => '3',
    'token-expire-code' => 433,

    'redeem_credit_request_status'    => array('pending' => 'Pending', 'completed' => 'Completed', 'rejected' => 'Rejected'),
];
