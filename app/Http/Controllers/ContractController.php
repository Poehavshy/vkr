<?php

namespace App\Http\Controllers;

use App\Field;
use App\Template;
use Illuminate\Http\Request;
use function Sodium\add;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            $fields[] = array('id'=> $field->id, 'name' => $field->name, 'type' => $field->type);
        }
        return $fields;
    }
}
