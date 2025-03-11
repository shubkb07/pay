<?php
// Get request headers
$headers = getallheaders();

// Get GET parameters
$get = $_GET;

// Get POST parameters
$post = $_POST;

// Get raw body
$body = file_get_contents('php://input');

// Combine all data into an array
$data = [
	'headers' => $headers,
	'get' => $get,
	'post' => $post,
	'body' => $body
];

// Convert array to JSON
$jsonData = json_encode($data, JSON_PRETTY_PRINT);

// Generate filename with current date and time
$filename = 'record_' . date('YmdHis') . '.json';

// Save JSON data to file
file_put_contents(__DIR__ . '/' . $filename, $jsonData);

echo "Request data has been saved to $filename";
?>