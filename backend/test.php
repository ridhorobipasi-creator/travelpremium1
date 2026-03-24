<?php
$url = 'http://localhost:8000/api/auth/login';
$data = json_encode(['email' => 'admin@wonderfultoba.id', 'password' => 'admin123']);

$options = [
    'http' => [
        'method'  => 'POST',
        'content' => $data,
        'header'  => "Content-Type: application/json\r\n" .
                     "Accept: application/json\r\n",
        'ignore_errors' => true
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo "RESPONSE FROM LARAVEL:\n";
echo $result;
