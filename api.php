<?php
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

$site = "https://yoursite.com";
$endpoint = $_GET['endpoint'] ?? '';

// ✅ Whitelist only what you need
$allowed = [
    'wp/v2/posts',
    'wp/v2/pages',
    'wp/v2/plugins',
    'wp/v2/themes'
];

if (!in_array($endpoint, $allowed)) {
    http_response_code(403);
    exit("Blocked endpoint");
}

$url = $site . "/wp-json/" . $endpoint;

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