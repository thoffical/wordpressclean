<?php
// CHANGE THIS
$site = "https://yoursite.com";

// Force HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    exit("HTTPS required");
}

// Require HTTP Basic Auth
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="WP Clean Admin"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$endpoint = $_GET['endpoint'] ?? '';

// Only allow what we need
$allowed = [
    'wp/v2/posts',
    'wp/v2/pages',
];

$allowed_single = [
    'wp/v2/posts/',
    'wp/v2/pages/',
];

$ok = in_array($endpoint, $allowed);
foreach ($allowed_single as $a) {
    if (strpos($endpoint, $a) === 0) $ok = true;
}

if (!$ok) {
    http_response_code(403);
    exit("Blocked endpoint");
}

$url = $site . "/wp-json/" . $endpoint . "?context=edit";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
}

$response = curl_exec($ch);
curl_close($ch);

echo $response;