@php
use App\Template;
@endphp
<div>
    <div class="dropdown">
        <div id="contract-templates"></div>
        <div id="error_message"><p class="error-text"></p></div>
        <div id="description_template"><p class="img-text"></p></div>
        <div id="fields_contract" class="contract_box display_none">
            <form id="create_contract" class="shadow-sm create_contract_form" action="">
                @csrf
                <input id="template_id" type="hidden" value="" name="template_id">
                <div id="place_for_fields"></div>
                <div id="error_contract_message"><p class="error-text"></p></div>
                <button type="submit" class="btn btn-success fs-20 marg-tb-30">Создать смарт-контракт</button>
            </form>
        </div>
    </div>
</div>
