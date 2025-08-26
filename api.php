<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // We'll restrict this later
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Store your API key securely
$SUPABASE_URL = 'https://rwfgdtwbzkglfljyvgbt.supabase.co/rest/v1';
$SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJ3ZmdkdHdiemtnbGZsanl2Z2J0Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTYwMDIwNjMsImV4cCI6MjA3MTU3ODA2M30.C3HRKtnQ_ivS7md7hJoOyusegbkusRCdhe5UonremY0'; // Move this to environment variable

// Get the endpoint from query parameters
$endpoint = $_GET['endpoint'] ?? '';

// Whitelist allowed endpoints
$allowed_endpoints = [
    'collections',
    'items',
    'fabric_brands',
    'fabric_collections',
    'fabric_colors',
    'woods',
    'metals',
    'stones',
    'weaving_materials',
    'carvings',
    'additional_specs'
];

// Check if endpoint is allowed
$table = explode('?', $endpoint)[0];
if (!in_array($table, $allowed_endpoints)) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden endpoint']);
    exit;
}

// Make the request to Supabase
$ch = curl_init($SUPABASE_URL . '/' . $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $SUPABASE_KEY,
    'Authorization: Bearer ' . $SUPABASE_KEY
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpcode);
echo $response;
?>