<?php
$site = "https://yoursite.com";
$user = "yourusername";
$app_pass = "abcd efgh ijkl mnop";

$endpoint = $_GET['endpoint'];
$method = $_SERVER['REQUEST_METHOD'];

$url = $site . "/wp-json/" . $endpoint;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$user:$app_pass");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

if ($method === "POST") {
    $data = file_get_contents("php://input");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
}

$response = curl_exec($ch);
curl_close($ch);

echo $response;