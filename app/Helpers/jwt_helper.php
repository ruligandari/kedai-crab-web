<?php

namespace App\Helpers;

use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;


function getJWT($otentikasiHeader)
{
    if($otentikasiHeader == null){

        throw new Exception('Authorization header tidak ditemukan');
    }

    return explode(' ', $otentikasiHeader)[1];
}

function validateJWT($token)
{
        try {
        $key = getenv('JWT_SECRET_KEY');
        $decoded = JWT::decode($token, new Key($key, 'HS256')); // Validasi JWT
        $userModel = new UserModel();
        $user = $userModel->where('email', $decoded->email)->first();
        if ($user){
            return $user;
        } else {
            throw new Exception('User tidak ditemukan');
        }
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }

}

function createJWT($email)
{
    $waktuRequest = time();
    $waktuJWT = getenv('JWT_TIME_REQUEST');
    $waktuExpired = $waktuRequest + $waktuJWT;
    $payload = [
        'email' => $email,
        'iat' => $waktuRequest,
        'exp' => $waktuExpired
    ];

    $jwt  = JWT::encode($payload, getenv('JWT_SECRET_KEY'), 'HS256');
    return $jwt;
}