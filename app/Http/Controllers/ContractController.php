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

    public function getInstruction(Request $request)
    {
        if (!empty($request->post('instruction'))){
            $instruction_view = view('includes.form.instruction', ['instruction' => $request->post('instruction')])->render();
            return array('ret_status' => 'ok', 'instruction_view' => $instruction_view);
        }
        else {
            return array('ret_status' => 'error', 'error_text' => 'Empty post');
        }
    }

    public function removeContract(Request $request)
    {
        if (!empty($request->post('id'))){
            $contract = new Contract();
            if ($contract->destroyContract($request->post('id'))){
                DBContract::where('id', $request->post('id'))->delete();
                return $this->getContracts($request);
            }
            else {
                return array('ret_status' => 'not ok', 'error_text' => 'Remove error');
            }
        }
        else {
            return array('ret_status' => 'error', 'error_text' => 'Empty post');
        }
    }

    public function getContracts(Request $request)
    {
        if ($request->post('test_user_id')) {
            $user_id = $request->post('test_user_id');
        }
        else {
            $user_id = Auth::user()->id;
        }
        $ids = array();
        $tmp = DBContract::select('id')->where('userId', $user_id)->get();
        foreach ($tmp as $id) {
            $ids[] = $id['id'];
        }
        if (empty($ids)) {
            return array('ret_status' => 'not ok', 'ret_text' => 'У Вас нет контрактов.');
        } else {
            $contract = new Contract();
            $contracts = $contract->getContracts($ids);
            if ($contracts === false){
                return array('ret_status' => 'error', 'error_text' => 'Contract api return false. Retry.');
            }
            foreach ($contracts as $key => $item){
                if (!in_array($item['id'], $ids)){
                    unset($contracts[$key]);
                    continue;
                }
                $contracts[$key]['template'] = $contract->getTemplate($item['template_id'])['name'];
            }
            foreach ($contracts as $key => $item){
                $full_guide = '<p class=\"fs-20 fw-6\"><strong>Инструкция для владельца:</strong></p>';
                foreach ($item['creator_guide'] as $guide){
                    if ($guide['status_id'] < $item['status']['id']){
                        $full_guide .= '<p class=\"fs-18 text_decor-lt\"><strong>'.$guide['guide'].'</strong></p>';
                    }
                    elseif ($guide['status_id'] == $item['status']['id']){
                        $full_guide .= '<p class=\"fs-18\"><strong><u>'.$guide['guide'].'</u></strong></p>';
                    }
                    else {
                        $full_guide .= '<p class=\"fs-18\">'.$guide['guide'].'</p>';
                    }
                }
                $full_guide .= '<hr><p class=\"fs-20 fw-6\"><strong>Инструкция для пользователя:</strong></p>';
                foreach ($item['users_guide'] as $guide){
                    if ($guide['status_id'] < $item['status']['id']){
                        $full_guide .= '<p class=\"fs-18 text_decor-lt\"><strong>'.$guide['guide'].'</strong></p>';
                    }
                    elseif ($guide['status_id'] == $item['status']['id']){
                        $full_guide .= '<p class=\"fs-18\"><strong><u>'.$guide['guide'].'</u></strong></p>';
                    }
                    else {
                        $full_guide .= '<p class=\"fs-18\">'.$guide['guide'].'</p>';
                    }
                }
                $contracts[$key]['guide'] = $full_guide;
            }

            $contracts_view = view('includes.source.contract-table', ['contracts' => $contracts])->render();
            return array('ret_status' => 'ok', 'contracts_view' => $contracts_view);
        }
    }
    public function createContract(Request $request)
    {
        if (!empty($request->post())){

            $params = array('_token', 'template_id', 'test_user_id');
            $fields = array();
            foreach ($request->post() as $key => $value){
                if (!in_array($key, $params)){
                    $fields[$key] = $value;
                }
            }
            $contract = new Contract();
            $contract_id = $contract->createContract($request->post('template_id'), $fields);
            if ($contract_id === false){
                return array('ret_status' => 'error', 'error_text' => 'Contract api return false. Retry.');
            }
            if ($request->post('test_user_id')) {
                $user_id = $request->post('test_user_id');
            }
            else {
                $user_id = Auth::user()->id;
            }
            DBContract::create(['id'=>$contract_id, 'userId'=>$user_id]);
            return array('ret_status' => 'ok', 'contract_id' => $contract_id);
        }
        else {
            return array('ret_status' => 'error', 'error_text' => 'Empty post');
        }
    }

    public function checkFields(Request $request)
    {
        $error = array();
        if (!empty($request->post())){
            foreach ($request->post() as $key => $field){
                if (strpos($key, '|')){
                    $key = substr($key, strpos($key, '|') + 1, strlen($key));
                }
                if ($key === 'address'){
                    $eth_valid = new EthereumValidator();
                    if(!$eth_valid->isAddress($field) && !preg_match('/^(0x)?[0-9a-fA-F]{40}$/', $field)){
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
        if ($templates === false){
            return array('ret_status' => 'error', 'error_text' => 'Contract api return false. Retry.');
        }
        $templates_view = view('includes.source.contract-templates', ['templates' => $templates])->render();
        return array('ret_status' => 'ok', 'templates_view' => $templates_view);
    }

    public function getTemplate(Request $request)
    {
        if (!empty($request->post('id'))) {
            $contract = new Contract();
            $template = $contract->getTemplate($request->post('id'));
            if ($template === false){
                return array('ret_status' => 'error', 'error_text' => 'Contract api return false. Retry.');
            }
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
