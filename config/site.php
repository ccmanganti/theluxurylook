<?php
return [
    'canonical_host' => env('SITE_CANONICAL_HOST', 'www.theluxurylook.com'),
    'launch_ready' => filter_var(env('SITE_LAUNCH_READY', false), FILTER_VALIDATE_BOOLEAN),
    'allow_preview' => filter_var(env('SITE_ALLOW_PREVIEW', true), FILTER_VALIDATE_BOOLEAN),
    'index_new_pages' => filter_var(env('SITE_INDEX_NEW_PAGES', false), FILTER_VALIDATE_BOOLEAN),
    'contact_email' => env('SITE_CONTACT_EMAIL', 'kristine@theluxurylook.com'),
    'ga4_id' => env('SITE_ANALYTICS_GA4_ID', ''),
    'google_site_verification' => env('SITE_GOOGLE_SITE_VERIFICATION', ''),
    'company' => [
        'name' => 'The Luxury Look',
        'phone' => '+1 (602) 578-6707',
        'address' => '4343 N Scottsdale Rd. Suite 150, Scottsdale, AZ 85251',
        'email' => 'kristine@theluxurylook.com',
    ],
];
