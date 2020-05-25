@php
use App\Template;
@endphp
<div>
    <div class="dropdown">
        <select id="select_template" onchange="getTemplate(this.value)" class="custom-select custom-select-lg mb-3 select-contract">
            <option selected value="0">Выберите шаблон контракта..</option>
            @php
                $templates = \App\Template::select('id', 'name')->where('active', '1')->get();
                foreach ($templates as $template){
                    echo '<option value="'.$template->id.'">'.$template->name.'</a>';
                }
            @endphp
        </select>
        <div id="error_message"><p class="error-text"></p></div>
        <div id="description_template"><p class="img-text"></p></div>
        <div id="fields_contract" class="contract_box display_none">
            <form id="create_contract" class="shadow-sm create_contract_form" action="">
                @csrf
                <div id="place_for_fields"></div>
                <button type="submit" class="btn btn-success fs-20 marg-tb-30">Создать смарт-контракт</button>
            </form>
        </div>
    </div>
</div>
