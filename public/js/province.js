$(document).ready(function() {
    $("#province").on("change", function () {
        let val = $(this).val();
        if(val !== ''){
            $.ajax({
                url : "/admin/data/city/"+val,
                type : 'GET',
                dataType: 'json',
                success : function (response) {
                    $('#city').html('');
                    $.each(response, function(key, value) {
                        $('#city')
                            .append($('<option>', { value : value['id'] })
                                .text(value['city_name']));
                    });
                }
            })
        }
    })
});