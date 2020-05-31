$(document).ready(function () {
    $('#create_contract').on('submit', function (event) {
        event.preventDefault();
        // $('#create_contract button[type=submit]').attr('disabled', 'disabled');
        // $('#description_template p').html('Контракт создается..');
        // $('#fields_contract').addClass('display_none');
        // $('#place_for_fields').html('');
        // $('#select_template').val(0);
        $.ajax({
            url: "/contract/check_fields/",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $(this).serializeArray(),
            success: function (response) {
                console.log(response);
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



    $(':regex(id, contract_*) button').on('click', function () {
        let copyText = this.parentNode.querySelector('input');
        copyText.select();
        document.execCommand("copy");
        $(this).addClass('isClick');
        let btn = this;
        this.innerText = 'Скопировано';
        setTimeout(() => {$(btn).text('Скопировать');$(btn).removeClass('isClick');}, 5000);
    });
    if (window.location.pathname === '/profile/create-contract'){
        getListTemplates();
    }
});

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


