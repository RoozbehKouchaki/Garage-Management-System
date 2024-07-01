<?php 

namespace Utils;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JwtHandler {
    protected $jwt_secret;
    protected $issuedAt;
    protected $expire;
    protected $jwt;
    protected $token;

    public function __construct() {
        $this->issuedAt = time();
        $this->expire = $this->issuedAt + 3600;
        $this->jwt_secret = 'roozbez';
    }

    public function jwtEncodeData($iss, $data) {
        $this->token = array(
            "iss" => $iss,
            "aud" => $iss,
            "iat" => $this->issuedAt,
            "exp" => $this->expire,
            "data" => $data
        );

        return JWT::encode($this->token, $this->jwt_secret, 'HS256');
    }

    public function jwtDecodeData($jwt_token) {
        try {
            $decoded = JWT::decode($jwt_token, new Key($this->jwt_secret, 'HS256'));
            return [
                "status" => true,
                "data" => $decoded->data
            ];
        } catch (\Exception $e) {
            return [
                "status" => false,
                "error" => $e->getMessage()
            ];
        }
    }
}
