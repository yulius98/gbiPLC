<?php

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

try {
    echo "=== JWT LOGIN DEBUG ===\n";
    
    // Step 1: Get user
    $user = User::first();
    if (!$user) {
        echo "ERROR: No user found\n";
        exit;
    }
    echo "✓ User found: " . $user->email . "\n";
    
    // Step 2: Generate token
    echo "Generating JWT token...\n";
    $token = JWTAuth::fromUser($user);
    echo "✓ Token generated: " . substr($token, 0, 50) . "...\n";
    
    // Step 3: Parse token back
    echo "Parsing token back...\n";
    JWTAuth::setToken($token);
    $parsedUser = JWTAuth::parseToken()->authenticate();
    echo "✓ Token parsed successfully, user: " . $parsedUser->email . "\n";
    
    // Step 4: Test cookie creation (simulate)
    echo "Testing cookie parameters...\n";
    $ttl = config('jwt.ttl');
    echo "✓ JWT TTL: " . $ttl . " (type: " . gettype($ttl) . ")\n";
    
    echo "=== ALL TESTS PASSED ===\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}