<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;

class Contract
{
    private $url;
    private $login;
    private $password;
    private $token;

    public function __construct()
    {
        $this->fakes();
        $this->url = config('contract.url');
        $this->login = config('contract.login');
        $this->password = config('contract.password');

        $response = Http::post($this->url.'auth', [
            'username' => $this->login,
            'password' => $this->password
        ]);

        if ($response->successful()){
            $this->token = $response['token'];
        }
    }

    public function getTemplates(){
        $response = Http::withToken($this->token)->get($this->url.'templates');
        if ($response->successful()){
            return $response->json();
        }
        else {
            return [];
        }
    }

    public function getTemplate($id){
        $response = Http::withToken($this->token)->get($this->url.'templates/'.$id);
        if ($response->successful()){
            return $response->json();
        }
        else {
            return [];
        }
    }

    private function fakes(){
        Http::fake([
            'test.ru/auth' => Http::response(['token' => '12321432543534gdsfhh'], 200, ['Headers']),
            'test.ru/templates' => Http::response([
                ['id' => '1', 'name' => 'Обмен токенов', 'description' => 'Обмен токенов через смарт-контракт'],
                ['id' => '2', 'name' => 'Продажа токенов', 'description' => 'Продажа токенов через смарт-контракт']
            ], 200, ['Headers']),
            'test.ru/templates/1' => Http::response([
                'id' => '1',
                'name' => 'Обмен токенов',
                'description' => 'Обмен токенов через смарт-контракт',
                'parameters_list' => [
                    ['name' => 'Ваш адрес кошелька', 'type' => 'address']
                ]
            ], 200, ['Headers']),
            'test.ru/templates/2' => Http::response([
            'id' => '2',
            'name' => 'Продажа токенов',
            'description' => 'Продажа токенов через смарт-контракт',
            'parameters_list' => [
                ['name' => 'Ваш адрес кошелька', 'type' => 'address'],
                ['name' => 'Стоимость токена', 'type' => 'price']
            ]
        ], 200, ['Headers'])
        ]);
    }
}
