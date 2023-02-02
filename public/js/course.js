/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

$(document).ready(function(){

    $("form").submit(function(event){
        event.preventDefault();
        var url = $(this).attr('data-action');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            method: "POST",
            data: new FormData(this),
            dataType: 'HTML',
            contentType: false,
            cache: false,
            processData: false,
            success:function(response)
            {
                location.reload();
            },
            error: function(response) {
                if (response.status !== 422){
                    location.reload();
                }
                $("#flash-container").html(response.responseText);
            }
        });
    });

});

