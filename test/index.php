<?php

header('Content-Type: application/json');

$host = 'srv1640.hstgr.io';
$port = '3306';
$dbname = 'u788505671_api';
$username = 'u788505671_api';
$password = 'Shubham07@kb#api';

$mysqli = new mysqli($host, $username, $password, $dbname, $port);

if ($mysqli->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $mysqli->connect_error]));
}

$countries = [];
$states = [];

// Fetch countries
$countries_query = "SELECT id, name, iso2, phonecode, currency, currency_name, currency_symbol, emojiU FROM countries";
$countries_result = $mysqli->query($countries_query);

if ($countries_result) {
    while ($country = $countries_result->fetch_assoc()) {
        $countries[$country['iso2']] = $country;
        $countries[$country['iso2']]['states'] = []; // Initialize states array
    }
    $countries_result->free_result();
} else {
    die(json_encode(['error' => 'Countries query failed: ' . $mysqli->error]));
}

// Fetch states
$states_query = "SELECT name, country_id FROM states";
$states_result = $mysqli->query($states_query);

if ($states_result) {
    while ($state = $states_result->fetch_assoc()) {
        $states[] = $state;
    }
    $states_result->free_result();
} else {
    die(json_encode(['error' => 'States query failed: ' . $mysqli->error]));
}

// Organize states by country
foreach ($states as $state) {
    foreach ($countries as $iso2 => $country) {
        if ($state['country_id'] == $country['id']) {
            $countries[$iso2]['states'][] = ['name' => $state['name']];
            break;
        }
    }
}

echo json_encode($countries, JSON_UNESCAPED_UNICODE); // Important for emojiU

$mysqli->close();

?>