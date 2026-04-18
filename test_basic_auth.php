<?php
$email = 'admin@example.com';
$password = 'password123';

echo "=== HTTP BASIC AUTH TEST ===\n\n";

// 1. Test Auth
$auth = base64_encode("$email:$password");
echo "1. Testing /api/auth-test\n";
echo "   Credentials: $email:$password\n";
echo "   Base64: $auth\n\n";

$opts = [
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Basic $auth",
        'ignore_errors' => true
    ]
];
$ctx = stream_context_create($opts);
$response = file_get_contents('http://127.0.0.1:8000/api/auth-test', false, $ctx);
echo "   Response: $response\n\n";

// 2. Test Events
echo "2. Testing /api/events (100 records)\n";
$response = file_get_contents('http://127.0.0.1:8000/api/events', false, $ctx);
$events = json_decode($response, true);
if (is_array($events)) {
    echo "   SUCCESS: Got " . count($events) . " events\n\n";
} else {
    echo "   ERROR: " . $response . "\n\n";
}

// 3. Test Participants
echo "3. Testing /api/participants (1000 records)\n";
$response = file_get_contents('http://127.0.0.1:8000/api/participants', false, $ctx);
$participants = json_decode($response, true);
if (is_array($participants)) {
    echo "   SUCCESS: Got " . count($participants) . " participants\n\n";
} else {
    echo "   ERROR: " . $response . "\n\n";
}

// 4. Test without auth (should fail)
echo "4. Testing WITHOUT authentication (should fail)\n";
$optsNoAuth = ['http' => ['method' => 'GET', 'ignore_errors' => true]];
$ctxNoAuth = stream_context_create($optsNoAuth);
$response = file_get_contents('http://127.0.0.1:8000/api/events', false, $ctxNoAuth);
echo "   Response: $response\n";
echo "   HTTP Status: " . ($http_response_header[0] ?? 'Unknown') . "\n";

echo "\n=== TEST COMPLETE ===\n";
