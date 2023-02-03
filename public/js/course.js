
$(document).ready(function(){
    $("form").submit(function(event){
        event.preventDefault();
        submitForm(event, this);
    });
});

function submitForm(event, button) {
    event.preventDefault();
    var form = $(button).closest("form");
    if(!form[0].reportValidity()){
        return;
    }
    var url = form.attr('data-action');
    var method = form.attr("data-method");
    var rowElement = $("#exam-row-" + form.attr("data-examid"));

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: url,
        method: "POST",
        data: new FormData(form[0]),
        dataType: 'HTML',
        contentType: false,
        cache: false,
        processData: false,
        success: function (response)
        {
            switch (method) {
                case "DELETE":
                    rowElement.remove();
                    break;
                case "POST":
                    form.trigger("reset");
                    form.parent().children("meta.insert-point")
                            .first().before(response);
                    break;
                default: //PUT
                    location.reload();
            }
        },
        error: function (response) {
            if (response.status !== 422) {
//                location.reload();
            }
            $("#flash-container").html(response.responseText);
        }
    });
}