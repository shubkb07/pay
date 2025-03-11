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
$createdAt = date('Y-m-d H:i:s') . '.' . sprintf('%03d', (int) (microtime(true) * 1000) % 1000);
$data = [
	'headers' => $headers,
	'get' => $get,
	'post' => $post,
	'body' => $body,
	'created_at' => $createdAt
];

// Convert array to JSON
$jsonData = json_encode($data, JSON_PRETTY_PRINT);

// Generate filename with current date and time
$filename = 'record_' . date('YmdHis') . '.json';

// Save JSON data to file
file_put_contents(INC . 'dup/' . $filename, $jsonData);

echo "Request data has been saved to $filename";
?>