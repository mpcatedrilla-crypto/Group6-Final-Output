<?php
echo "=== API VERIFICATION ===\n\n";

// 1. Test Login
$loginOpts = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode(['email' => 'admin@example.com', 'password' => 'password123']),
        'ignore_errors' => true
    ]
];
$ctx = stream_context_create($loginOpts);
$response = file_get_contents('http://127.0.0.1:8000/api/login', false, $ctx);
$loginData = json_decode($response, true);

echo "1. LOGIN TEST:\n";
if (isset($loginData['token'])) {
    echo "   Status: PASS - Token received\n";
    $token = $loginData['token'];
} else {
    echo "   Status: FAIL - " . ($response ?: "No response") . "\n";
    exit;
}

// 2. Test Events with token
$eventOpts = [
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer $token\r\nAccept: application/json",
        'ignore_errors' => true
    ]
];
$ctx = stream_context_create($eventOpts);
$response = file_get_contents('http://127.0.0.1:8000/api/events', false, $ctx);
$events = json_decode($response, true);

echo "\n2. EVENTS API TEST:\n";
if (is_array($events)) {
    echo "   Status: PASS - Got " . count($events) . " events\n";
} else {
    echo "   Status: FAIL - " . substr($response, 0, 100) . "\n";
}

// 3. Test Participants with token
$response = file_get_contents('http://127.0.0.1:8000/api/participants', false, $ctx);
$participants = json_decode($response, true);

echo "\n3. PARTICIPANTS API TEST:\n";
if (is_array($participants)) {
    echo "   Status: PASS - Got " . count($participants) . " participants\n";
} else {
    echo "   Status: FAIL - " . substr($response, 0, 100) . "\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
