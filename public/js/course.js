
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
    var workingElement = $("#"+form.attr("data-element-id"));

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
                    workingElement.remove();
                    break;
                case "POST":
                    form.trigger("reset");
                    form.parent().children("meta.insert-point")
                            .first().before(response);
                    break;
                case "PUT":
                    workingElement.parent().find("form").trigger("reset");
                    workingElement.replaceWith(response);
                    break;
                default:
                    location.reload();
            }
        },
        error: function (response) {
            switch (response.status){
                case 422:
                    $("#flash-container").html(response.responseText);
                    break;
                default:
                    document.write(response.responseText);
                    console.log(response);
            }
        }
    });
}