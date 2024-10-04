<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Config;

class JwtService
{
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET');
    }

    public function createToken(array $payload)
    {
        // dd($payload);
        

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        // Menambahkan klaim standar ke payload
        $new_payload = array(
            'id' => $payload['id'],
            'username' => $payload['username'],
            'role' => $payload['role'],
            'iduser_role' => $payload['iduser_role'],
            'idruang' => $payload['idruang'],
            'iat' => time(),
            'exp' => time() + (60 * 60 * 2)
        );

        // dd($new_payload);

        // Encode header dan payload menjadi Base64Url
        $headerEncoded = base64_encode(json_encode($header));
        $payloadEncoded = base64_encode(json_encode($new_payload));

        // Membuat signature
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secretKey, true);
        $signatureEncoded = base64_encode($signature);

        // Menggabungkan semuanya untuk mendapatkan JWT
        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    public function extendsToken($token){
        // dd($token); 
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;
        $payload = $this->decodeToken($token);
        // dd($payload);

        $payload['iat'] = time(); // Issued at
        $payload['exp'] = time() + (60 * 60 * 2); // Expiration time (2 hour)

        $payloadEncoded = base64_encode(json_encode($payload));

        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secretKey, true);
        $signatureEncoded = base64_encode($signature);

        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    public function verifyToken($token)
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;

        $signature = base64_decode($signatureEncoded);
        $expectedSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secretKey, true);

        return hash_equals($signature, $expectedSignature);
    }

    public function decodeToken($token)
    {
        if (!$this->verifyToken($token)) {
            return null;
        }

        $parts = explode('.', $token);
        $payload = base64_decode($parts[1]);

        return json_decode($payload, true);
    }
}
