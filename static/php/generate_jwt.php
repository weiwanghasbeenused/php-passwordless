<?php

require_once(__DIR__ . '/bootstrap.php');

function generate_jwt($user_id, $exp=600){
    // get the local secret key
    $secret = getenv('SECRET');

    // Create the token header
    $header = json_encode([
        'typ' => 'JWT',
        'alg' => 'HS256'
    ]);

    // Create the token payload
    $payload = json_encode([
        'user_id' => $user_id,
        'role' => 'applicant',
        'exp' => time() + $exp
    ]);

    // Encode Header
    $base64UrlHeader = base64UrlEncode($header);

    // Encode Payload
    $base64UrlPayload = base64UrlEncode($payload);

    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    // Encode Signature to Base64Url String
    $base64UrlSignature = base64UrlEncode($signature);

    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
}
