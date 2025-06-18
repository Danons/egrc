<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends _Controller
{
    public function __construct()
    {
    }

    public function Token($sid = '')
    {
        $this->isTrueMethod("GET");

        $key = $this->keyjwt;

        $payload = [
            'aud' => $_SERVER['SERVER_NAME'],
            'iat' => time(),
        ];

        $jwt = $this->jwt::encode($payload, $key, 'HS256');

        $this->success($jwt);
    }

    public function Login()
    {
        $payload = $this->isTrustMethod("POST");

        $post_data = $this->requestData();
        $this->load->model("AuthModel", "auth");
        $response = $this->auth->Login($post_data['username'], $post_data['password']);
        if ($response['error'])
            $this->fail($response['error']);

        unset($_SESSION[SESSION_APP]['menu']);
        unset($_SESSION[SESSION_APP]['child_jabatan']);
        unset($_SESSION[SESSION_APP]['']);
        unset($_SESSION[SESSION_APP]['asd']);
        unset($_SESSION[SESSION_APP]['t']);
        unset($_SESSION[SESSION_APP]['is_notification']);
        
        foreach ($_SESSION[SESSION_APP] as $k => $v) {
            $payload[SESSION_APP][$k] = $v;
        }

        $payload['iat'] = time();
        $key = $this->keyjwt;
        $jwt = $this->jwt::encode($payload, $key, 'HS256');
        $this->success($jwt);
    }
}
