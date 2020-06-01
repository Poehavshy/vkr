<?php

namespace App\Http\Controllers;

use App\Field;
use App\Template;
use App\User;
use App\Contract as DBContract;
use Illuminate\Http\Request;
use App\Classes\EthereumValidator;
use App\Classes\Contract;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function removeContract()
    {
        if (!empty($_POST) && isset($_POST['id'])){
            $contract = new Contract();
            if ($contract->destroyContract($_POST['id'])){
                DBContract::where('id', $_POST['id'])->delete();
                return $this->getContracts();
            }
            else {
                return array('ret_status' => 'not ok', 'error_text' => 'Remove error');
            }
        }
        else {
            return array('ret_status' => 'error', 'error_text' => 'Empty post');
        }
    }

    public function getContracts()
    {
        $ids = array();
        $tmp = DBContract::select('id')->where('userId', Auth::user()->id)->get();
        foreach ($tmp as $id) {
            $ids[] = $id['id'];
        }
        if (empty($ids)) {
            return array('ret_status' => 'not ok', 'ret_text' => 'У Вас нет контрактов.');
        } else {
            $contract = new Contract();
            $contracts = $contract->getContracts($ids);
            foreach ($contracts as $key => $item){
                if (!in_array($item['id'], $ids)){
                    unset($contracts[$key]);
                    continue;
                }
                $contracts[$key]['template'] = $contract->getTemplate($item['template_id'])['name'];
            }
            file_put_contents('log.out', "<pre>".print_r($contracts, true)."</pre>" , FILE_APPEND);

            $contracts_view = view('includes.source.contract-table', ['contracts' => $contracts])->render();
            return array('ret_status' => 'ok', 'contracts_view' => $contracts_view);
        }
    }
    public function createContract()
    {
        if (!empty($_POST)){
            $params = array('address', 'price');
            $fields = array();
            foreach ($_POST as $key => $value){
                if (in_array($key, $params)){
                    $fields[$key] = $value;
                }
            }
            $contract = new Contract();
            $contract_id = $contract->createContract($_POST['template_id'], $fields);
            DBContract::create(['id'=>$contract_id, 'userId'=>Auth::user()->id]);
            return array('ret_status' => 'ok', 'contract_id' => $contract_id);
        }
        else {
            return array('ret_status' => 'error', 'error_text' => 'Empty post');
        }
    }

    public function checkFields()
    {
        $error = array();
        if (!empty($_POST)){
            foreach ($_POST as $key => $field){
                if ($key === 'address'){
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
                $HTMLattr = 'placeholder="0x493c4afb73b490e988650b9758e7736c72af748f"';
            }
            elseif ($field['type'] === 'price'){
                $HTMLtype = 'number';
                $HTMLattr = 'value="0.000001" min="0.000001" max="1000" step="0.000001"';
            }
            else {
                $HTMLtype = 'text';
                $HTMLattr = '';
            }
            $fields[] = array('id'=> $idx, 'name' => $field['name'], 'type' => $HTMLtype, 'attr' => $HTMLattr, 'purpose' => $field['type']);
            ++$idx;
        }
        return $fields;
    }
}
