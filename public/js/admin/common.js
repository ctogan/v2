$(document).ready(function() {
    $("form").on("submit", function () {
        event.preventDefault();

        var btn = $(".btn-submit");
        btn.addClass("post");
        btn.attr("disabled", "disabled");

        var token = $('meta[name="csrf-token"]').attr('content');
        var url = $(this).attr('action');
        $.ajax({
            url:url,
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
                $('.modal').modal('hide');
                var text = '';
                var res = JSON.parse(response);
                if(res.status) {
                    bootbox.alert({
                        title: "Success!",
                        message: "<i data-feather='check'></i> Data has been submitted.",
                        centerVertical:true,
                        onShow: function(e) {
                            feather.replace();
                        },
                        callback: function() {
                            btn.removeClass("post");
                            btn.removeAttr("disabled");
                        },
                        onHide: function(e) {
                            location.reload();
                        }
                    });
                }else{
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
                            btn.removeClass("post");
                            btn.removeAttr("disabled");
                            $('.modal').modal('show');
                        }
                    });
                }
            }
        })
    })
});

function submit(data) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var btn = $(".btn-submit");
    btn.addClass("post");
    btn.attr("disabled", "disabled");
    $.ajax({
        url: data[0].action,
        method:"POST",
        headers: {
            'X-CSRF-TOKEN': token
        },
        async:true,
        data:new FormData(data[0]),
        contentType: false,
        cache: false,
        processData: false,
        success:function(response)
        {
            var text = '';
            var res = JSON.parse(response);
            if(res.status) {
                close_loading();
                bootbox.alert({
                    title: "Success!",
                    message: "<i data-feather='check'></i> Data has been submitted.",
                    centerVertical:true,
                    onShow: function(e) {
                        feather.replace();
                    },
                    callback: function() {
                        btn.removeClass("post");
                        btn.removeAttr("disabled");
                    },
                    onHide: function(e) {
                        location.reload();
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
                        btn.removeClass("post");
                        btn.removeAttr("disabled");
                    }
                });
            }
        }
    })
}

function del(id, url) {
    bootbox.confirm({
        title: "Delete Data ?",
        centerVertical:true,
        message: "Do you want to delete the data now? This cannot be undone.",
        buttons: {
            cancel: {
                label: '<i data-feather="x"></i> Cancel'
            },
            confirm: {
                label: '<i data-feather="check"></i> Confirm'
            }
        },
        callback: function (result) {
            if(result){
                loading();
                var token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url:url,
                    method:"POST",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data:{id:id},
                    success:function(response)
                    {
                        var text = '';
                        var res = JSON.parse(response);
                        if(res.status) {
                            close_loading();
                            bootbox.alert({
                                title: "Success",
                                message: "<i data-feather='check'></i> Data has been deleted.",
                                centerVertical:true,
                                onShow: function(e) {
                                    feather.replace();
                                },
                                onHide: function(e) {
                                    location.reload();
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
                                }
                            });
                        }
                    }
                })

            }
        },
        onShow: function(e) {
            feather.replace();
        }
    });
}