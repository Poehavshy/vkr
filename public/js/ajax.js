$(document).ready(function () {

});

function getTemplate(id) {
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
