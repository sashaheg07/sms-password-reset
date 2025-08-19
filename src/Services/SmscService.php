<?php

namespace sashaheg07\SmsPasswordReset\Services;

use Illuminate\Support\Facades\Http;

class SmscService
{
    protected $login;
    protected $password;

    public function __construct()
    {
        $this->login = config('smsc.login');
        $this->password = config('smsc.password');
    }

    public function send($phone, $message)
    {
        $response = Http::get("https://smsc.kz/sys/send.php", [
            'login' => $this->login,
            'psw'   => $this->password,
            'phones'=> $phone,
            'mes'   => $message,
            'fmt'   => 3
        ]);

        return $response->json();
    }
}
