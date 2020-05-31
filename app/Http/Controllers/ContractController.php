<?php

namespace App\Http\Controllers;

use App\Field;
use App\Template;
use Illuminate\Http\Request;
use App\Classes\EthereumValidator;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function checkFields()
    {
        $error = array();
        if (!empty($_POST)){
            foreach ($_POST as $key => $field){
                if ($key === 'wallet'){
                    $eth_valid = new EthereumValidator();
                    if($eth_valid->isAddress($field) !== true){
                        $error[$key] = 'Некорректный адрес кошелька Ethereum';
                    }
                }
                elseif ($key === 'product_id'){
                    if($field == ''){
                        $error[$key] = 'Некорректный id товара';
                    }
                }
                elseif ($key === 'product_price'){
                    if(floatval($field) <= 0){
                        $error[$key] = 'Стоимость товара должна быть положительной';
                    }
                }
                elseif ($key === 'days'){
                    if(intval($field) <= 0){
                        $error[$key] = 'Количество дней должно быть положительным';
                    }
                }
            }
            if (empty($error)){
                return array('ret_status' => 'ok');
            }
            else {
                return array('ret_status' => 'not ok', 'error_array' => $error);
            }
        }
        else {
            return array('ret_status' => 'error', 'error_text' => 'Empty post');
        }
    }


    public function getTemplate()
    {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $template = Template::select('description')->where('id', $_POST['id'])->where('active', 1)->get();
            $fields = self::getFields($_POST['id']);
            $fields_view = view('includes.source.contract-fields', ['fields' => $fields])->render();
            return array('ret_status' => 'ok', 'description_template' => $template[0]->description, 'fields_view' => $fields_view);
        }
        else {
            return array('ret_status' => 'error', 'error_text' => 'Empty post');
        }
    }

    private function getFields($template_id)
    {
        $data = Field::where('active', 1)->where('template_id', $template_id)->orderBy('order', 'asc')->get();
        $fields = array();
        foreach ($data as $field){
            $fields[] = array('id'=> $field->id, 'name' => $field->name, 'type' => $field->type, 'attr' => $field->attr, 'purpose' => $field->purpose);
        }
        return $fields;
    }
}
