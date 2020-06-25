$(document).ready(function () {
    $('#create_contract').on('submit', function (event) {
        event.preventDefault();
        $('#create_contract button[type=submit]').attr('disabled', 'disabled');
        let formData = $(this).serializeArray();
        let formDataCheck = $(this).serializeArray();
        for (let field in formData){
            if ($('input[name='+formData[field].name+']').data('purpose') !== undefined) {
                formDataCheck[field].name += '|' + $('input[name=' + formData[field].name + ']').data('purpose');
            }
        }
        $.ajax({
            url: "/contract/check_fields/",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formDataCheck,
            success: function (response) {
                if (response.ret_status === 'ok'){
                    $('#description_template p').html('Контракт создается..');
                    $('#fields_contract').addClass('display_none');
                    $('#place_for_fields').html('');
                    $('#select_template').val(0);
                    $.ajax({
                        url: "/contract/create/",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        success: function (response) {
                            if (response.ret_status === 'ok'){
                                $('#description_template p').html('Контракт создан.<br>Идентификатор: '+response.contract_id+'<br>Для отслеживания статуса пройдите в "Мои контракты".');
                            }
                            else if (response.ret_status === 'error'){
                                $('#error_message p').html('Error: ' + response.error_text);
                            }
                            else {
                                $('#error_message p').html('Error: unexpected error.');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log('jqXHR:');
                            console.log(jqXHR);
                            console.log('textStatus:');
                            console.log(textStatus);
                            console.log('errorThrown:');
                            console.log(errorThrown);
                        }
                    });
                }
                else if (response.ret_status === 'not ok'){
                    let error_text = '';
                    for(let key in response.error_array){
                        error_text += key + ': ' + response.error_array[key] + '<br>';
                    }
                    $('#error_contract_message p').html(error_text);
                    $('#create_contract button[type=submit]').removeAttr('disabled');
                }
                else if (response.ret_status === 'error'){
                    $('#error_contract_message p').html('Error: ' + response.error_text);
                    $('#create_contract button[type=submit]').removeAttr('disabled');
                }
                else {
                    $('#error_contract_message p').html('Error: unexpected error.');
                    $('#create_contract button[type=submit]').removeAttr('disabled');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('jqXHR:');
                console.log(jqXHR);
                console.log('textStatus:');
                console.log(textStatus);
                console.log('errorThrown:');
                console.log(errorThrown);
            }
        });
    });


    if (window.location.pathname === '/profile/create-contract'){
        getListTemplates();
    }
    else if (window.location.pathname === '/profile/my-contract'){
        getContracts();
    }
    else if (window.location.pathname === '/contracts') {
        getExchange();
    }
});

function getExchange() {
    $.ajax({
        url: "/contract/get_exchange/",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {},
        success: function (response) {
            if (response.ret_status === 'ok'){
                $('#contracts_table tbody').html(response.contracts_view);
                activateCopyBtn();
            }
            else if (response.ret_status === 'not ok'){
                $('#error_message p').html(response.ret_text);
                $('#contracts_table').addClass('display_none');
            }
            else {
                $('#error_message p').html('Error: unexpected error.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('jqXHR:');
            console.log(jqXHR);
            console.log('textStatus:');
            console.log(textStatus);
            console.log('errorThrown:');
            console.log(errorThrown);
        }
    });
}

function getContracts() {
    $.ajax({
        url: "/contract/get_contracts/",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {},
        success: function (response) {
            if (response.ret_status === 'ok'){
                $('#contracts_table tbody').html(response.contracts_view);
                activateCopyBtn();
            }
            else if (response.ret_status === 'not ok'){
                $('#error_message p').html(response.ret_text);
                $('#contracts_table').addClass('display_none');
            }
            else {
                $('#error_message p').html('Error: unexpected error.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('jqXHR:');
            console.log(jqXHR);
            console.log('textStatus:');
            console.log(textStatus);
            console.log('errorThrown:');
            console.log(errorThrown);
        }
    });
}

function activateCopyBtn() {
    $('.copy_btn').on('click', function () {
        let copyText = this.parentNode.querySelector('input');
        copyText.select();
        document.execCommand("copy");
        $(this).addClass('isClick');
        let btn = this;
        this.innerText = 'Скопировано';
        setTimeout(() => {$(btn).text('Скопировать');$(btn).removeClass('isClick');}, 5000);
    });
}

function getListTemplates() {
    $.ajax({
        url: "/contract/get_list_templates/",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {},
        success: function (response) {
            if (response.ret_status === 'ok'){
                $('#contract-templates').html(response.templates_view);
            }
            else if (response.ret_status === 'error'){
                $('#error_message p').html('Error: ' + response.error_text);
            }
            else {
                $('#error_message p').html('Error: unexpected error.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('jqXHR:');
            console.log(jqXHR);
            console.log('textStatus:');
            console.log(textStatus);
            console.log('errorThrown:');
            console.log(errorThrown);
        }
    });
}

function showInstruction(text){
    $.ajax({
        url: "/contract/get_instruction/",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'instruction': text
        },
        success: function (response) {
            if (response.ret_status === 'ok'){
                $('#place_instruction_form').html(response.instruction_view);
                $("#instruction_form").modal('show');
            }
            else if (response.ret_status === 'error'){
                alert('Error: ' + response.error_text);
            }
            else {
                alert('Error: unexpected error.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('jqXHR:');
            console.log(jqXHR);
            console.log('textStatus:');
            console.log(textStatus);
            console.log('errorThrown:');
            console.log(errorThrown);
        }
    });
}

function removeContract(id) {
    $.ajax({
        url: "/contract/remove/",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'id': id
        },
        success: function (response) {
            if (response.ret_status === 'ok'){
                $('#contracts_table tbody').html(response.contracts_view);
                activateCopyBtn();
            }
            else if (response.ret_status === 'not ok'){
                $('#error_message p').html(response.ret_text);
                $('#contracts_table').addClass('display_none');
            }
            else {
                $('#error_message p').html('Error: unexpected error.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('jqXHR:');
            console.log(jqXHR);
            console.log('textStatus:');
            console.log(textStatus);
            console.log('errorThrown:');
            console.log(errorThrown);
        }
    });
}

function getTemplate(id) {
    if (id === '0'){
        $('#description_template p').html('');
        $('#fields_contract').addClass('display_none');
        $('#place_for_fields').html('');
        return
    }
    $.ajax({
        url: "/contract/get_template/",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            "id": id
        },
        success: function (response) {
            if (response.ret_status === 'ok'){
                $('#description_template p').html(response.description_template);
                $('#fields_contract').removeClass('display_none');
                $('#place_for_fields').html(response.fields_view);
                $('#template_id').val(id);
                $('#create_contract button[type=submit]').removeAttr('disabled');
            }
            else if (response.ret_status === 'error'){
                $('#error_message p').html('Error: ' + response.error_text);
            }
            else {
                $('#error_message p').html('Error: unexpected error.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('jqXHR:');
            console.log(jqXHR);
            console.log('textStatus:');
            console.log(textStatus);
            console.log('errorThrown:');
            console.log(errorThrown);
        }
    });
}

jQuery.expr[':'].regex = function(elem, index, match) {
    var matchParams = match[3].split(','),
        validLabels = /^(data|css):/,
        attr = {
            method: matchParams[0].match(validLabels) ?
                matchParams[0].split(':')[0] : 'attr',
            property: matchParams.shift().replace(validLabels,'')
        },
        regexFlags = 'ig',
        regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
    return regex.test(jQuery(elem)[attr.method](attr.property));
}


