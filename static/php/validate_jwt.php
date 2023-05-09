<?php

require_once(__DIR__ . '/bootstrap.php');
use Carbon\Carbon;

function validate_jwt($token = ''){
    $output = array();
    // get the local secret key
    $secret = $_ENV['JWT_SECRET'];

    if (!$token) {
        $output['status'] = 'error';
        $output['message'] = 'Please provide a token to verify';
        return $output;
    }

    $jwt = $token;

    // split the token
    $tokenParts = explode('.', $jwt);
    if(count($tokenParts) !== 3)
    {
        $output['status'] = 'error';
        $output['message'] = 'The token is not in the correct format.';
        return $output;
    }
    $header = base64_decode($tokenParts[0]);
    $payload = base64_decode($tokenParts[1]);
    $signatureProvided = $tokenParts[2];

    // check the expiration time - note this will cause an error if there is no 'exp' claim in the token
    if(isset(json_decode($payload)->exp))
    {
        $expiration = Carbon::createFromTimestamp(json_decode($payload)->exp);
        $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 0);
        if ($tokenExpired) {
            $output['status'] = 'error';
            $output['message'] = 'The token has expired.';
            return $output;
        }
    }
    

    // build a signature based on the header and payload using the secret
    $base64UrlHeader = base64UrlEncode($header);
    $base64UrlPayload = base64UrlEncode($payload);
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignature = base64UrlEncode($signature);

    // verify it matches the signature provided in the token
    $signatureValid = ($base64UrlSignature === $signatureProvided);

    if ($signatureValid) {
        $output['status'] = 'success';
        $output['message'] = 'The signature is valid.';
        $output['payload'] = $payload;
    } else {
        $output['status'] = 'error';
        $output['message'] = 'The signature is NOT valid.';
    }

    return $output;
}
