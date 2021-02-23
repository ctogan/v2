$('#form_import_excel').on('submit', function(event){
    event.preventDefault();

    var btn = $("#btn_upload_file");
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url:'/admin/operational/clbk/event/submit',
        method:"POST",
        headers: {
            'X-CSRF-TOKEN': token
        },
        async:true,
        data:new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success:function(response)
        {
            var res = JSON.parse(response);
            if(res.status) {
                close_loading();
                bootbox.alert({
                    title: "Success",
                    message: "<i data-feather='check'></i> Data has been updated.",
                    centerVertical:true,
                    onShow: function(e) {
                        feather.replace();
                    },
                    onHide: function(e) {
                        window.location.href = '/admin/operational/clbk/event';
                    }
                });
            }else{
                close_loading();
                $.each(res.message, function( index, value ) {
                    text += '<p class="error"><i data-feather="x-square"></i> '+ value[0]+'</p>';
                });
                bootbox.alert({
                    title: "Errors Found",
                    message: text,
                    centerVertical:true,
                    onShow: function(e) {
                        feather.replace();
                    },
                    callback: function() {
                        btn.removeAttr("disabled");
                    }
                });
            }
        }
    })
});
