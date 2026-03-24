<?php

/**
 * Vercel Serverless Bridge for Laravel
 * This file handles all requests for the /api route and redirects them to Laravel.
 */

// Define where the backend lives relative to this file
$backend_path = __DIR__ . '/../backend';

// Set up the autoloader and bootstrap
require $backend_path . '/vendor/autoload.php';
$app = require_once $backend_path . '/bootstrap/app.php';

// Handle the incoming request
use Illuminate\Http\Request;
$app->handleRequest(Request::capture());
