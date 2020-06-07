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
//        $this->fakes();
        $this->url = config('contract.url');
        $this->login = config('contract.login');
        $this->password = config('contract.password');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($this->url.'auth', [
            'username' => $this->login,
            'password' => $this->password
        ]);

        if ($response->successful()){
            $this->token = $response['access_token'];
        }
    }

    public function getTemplates(){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'JWT ' . $this->token
            ])->get($this->url.'templates');

        if ($response->successful()){
            return $response->json();
        }
        else {
            return false;
        }
    }

    public function getTemplate($id){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'JWT ' . $this->token
        ])->get($this->url.'templates/'.$id);

        if ($response->successful()){
            return $response->json();
        }
        else {
            return false;
        }
    }

    public function createContract($template_id, $fields){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'JWT ' . $this->token
        ])->post($this->url.'create-contract', [
            'template_id' => $template_id,
            'parameters' => $fields
        ]);

        if ($response->successful()){
            return $response['id'];
        }
        else {
            return false;
        }
    }

    public function getContracts($ids){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'JWT ' . $this->token
        ])->post($this->url.'contracts', [
            'ids' => $ids
        ]);

        if ($response->successful()){
            return $response->json();
        }
        else {
            return false;
        }
    }

    public function destroyContract($id){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'JWT ' . $this->token
        ])->get($this->url.'contracts/'.$id.'/destroy');
        if ($response->successful()){
            return true;
        }
        else {
            return false;
        }
    }


//    private function fakes(){
//        Http::fake([
//            'test.ru/auth' => Http::response(['token' => '12321432543534gdsfhh'], 200, ['Headers']),
//            'test.ru/templates' => Http::response([
//                ['id' => '1', 'name' => 'Обмен токенов', 'description' => 'Обмен токенов через смарт-контракт'],
//                ['id' => '2', 'name' => 'Продажа токенов', 'description' => 'Продажа токенов через смарт-контракт']
//            ], 200, ['Headers']),
//            'test.ru/templates/1' => Http::response([
//                'id' => '1',
//                'name' => 'Обмен токенов',
//                'description' => 'Обмен токенов через смарт-контракт',
//                'parameters_list' => [
//                    ['name' => 'Ваш адрес кошелька', 'type' => 'address']
//                ]
//            ], 200, ['Headers']),
//            'test.ru/templates/2' => Http::response([
//                'id' => '2',
//                'name' => 'Продажа токенов',
//                'description' => 'Продажа токенов через смарт-контракт',
//                'parameters_list' => [
//                    ['name' => 'Ваш адрес кошелька', 'type' => 'address'],
//                    ['name' => 'Стоимость токена', 'type' => 'price']
//                ]
//            ], 200, ['Headers']),
//            'test.ru/create-contract' => Http::response(['id' => rand(1, 10)], 200, ['Headers']),
//            'test.ru/contracts' => Http::response([
//                [
//                    'id' => '1',
//                    'template_id' => '1',
//                    'created' => '19:11 01.06.2020',
//                    'address' => '0x1543d0F83489e82A1344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция лалалалалала'
//                ],
//                [
//                    'id' => '2',
//                    'template_id' => '2',
//                    'created' => '19:12 02.06.2020',
//                    'address' => '0x1543d0F83489e7771344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция'
//                ],
//                [
//                    'id' => '3',
//                    'template_id' => '1',
//                    'created' => '19:13 03.06.2020',
//                    'address' => '0x1543d0F83489e82A1344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция лалалалалала'
//                ],
//                [
//                    'id' => '4',
//                    'template_id' => '2',
//                    'created' => '19:14 04.06.2020',
//                    'address' => '0x1543d0F83489e7771344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция'
//                ],
//                [
//                    'id' => '5',
//                    'template_id' => '1',
//                    'created' => '19:15 05.06.2020',
//                    'address' => '0x1543d0F83489e82A1344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция лалалалалала'
//                ],
//                [
//                    'id' => '6',
//                    'template_id' => '2',
//                    'created' => '19:16 06.06.2020',
//                    'address' => '0x1543d0F83489e7771344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция'
//                ],
//                [
//                    'id' => '7',
//                    'template_id' => '1',
//                    'created' => '19:17 07.06.2020',
//                    'address' => '0x1543d0F83489e82A1344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция лалалалалала'
//                ],
//                [
//                    'id' => '8',
//                    'template_id' => '2',
//                    'created' => '19:18 08.06.2020',
//                    'address' => '0x1543d0F83489e7771344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция'
//                ],
//                [
//                    'id' => '9',
//                    'template_id' => '1',
//                    'created' => '19:19 09.06.2020',
//                    'address' => '0x1543d0F83489e82A1344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция лалалалалала'
//                ],
//                [
//                    'id' => '10',
//                    'template_id' => '2',
//                    'created' => '19:20 10.06.2020',
//                    'address' => '0x1543d0F83489e7771344DF6827B23d541F235A50',
//                    'status' => 'Создан',
//                    'guide' => 'Инструкция'
//                ]
//            ], 200, ['Headers']),
//            'test.ru/contracts/*/destroy' => Http::response(['True'], 200, ['Headers']),
//        ]);
//    }
}
