<?php

namespace App\Http\Controllers;

use App\Field;
use App\Template;
use Illuminate\Http\Request;
use App\Classes\EthereumValidator;
use App\Classes\Contract;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createContract()
    {

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
                elseif ($key === 'price'){
                    if(floatval($field) <= 0){
                        $error[$key] = 'Стоимость товара должна быть положительной';
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

    public function getListTemplates(){
        $contract = new Contract();
        $templates = $contract->getTemplates();
        $templates_view = view('includes.source.contract-templates', ['templates' => $templates])->render();
        return array('ret_status' => 'ok', 'templates_view' => $templates_view);
    }

    public function getTemplate()
    {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $contract = new Contract();
            $template = $contract->getTemplate($_POST['id']);
            $fields = self::genFields($template['parameters_list']);
            $fields_view = view('includes.source.contract-fields', ['fields' => $fields])->render();
            return array('ret_status' => 'ok', 'description_template' => $template['description'], 'fields_view' => $fields_view);
        }
        else {
            return array('ret_status' => 'error', 'error_text' => 'Empty post');
        }
    }

    private function genFields($data)
    {
        $fields = array();
        $idx = 1;
        foreach ($data as $field){
            if($field['type'] === 'address'){
                $HTMLtype = 'text';
                $purpose = 'wallet';
                $HTMLattr = 'placeholder="0x493c4afb73b490e988650b9758e7736c72af748f"';
            }
            elseif ($field['type'] === 'price'){
                $HTMLtype = 'number';
                $purpose = 'price';
                $HTMLattr = 'value="0.000001" min="0.000001" max="1000" step="0.000001"';
            }
            else {
                $HTMLtype = 'text';
                $purpose = '';
            }
            $fields[] = array('id'=> $idx, 'name' => $field['name'], 'type' => $HTMLtype, 'attr' => $HTMLattr, 'purpose' => $purpose);
            ++$idx;
        }
        return $fields;
    }
}
