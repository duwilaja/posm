<?php

require __DIR__ . '/../vendor/autoload.php';

use League\OAuth2\Client\Provider\Google;

// Replace these with your token settings
// Create a project at https://console.developers.google.com/
$clientId     = 'your-client-id';
$clientId     = '515277141619-vk2eekihg4b51r465aiub1fjidhaqjd5.apps.googleusercontent.com';
$clientSecret = 'your-client-secret';
$clientSecret = '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';

// Change this if you are not using the built-in PHP server
$redirectUri  = 'http://omgdemo.website';

// Start the session
session_start();

// Initialize the provider
$provider = new Google(compact('clientId', 'clientSecret', 'redirectUri'));

// No HTML for demo, prevents any attempt at XSS
header('Content-Type', 'text/plain');

return $provider;
