$(document).ready(function() {
    $("#btn_change_password").on("click", function() {
        bootbox.confirm({
            title: "Change Password ?",
            centerVertical:true,
            message: "Are you sure change your password?",
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
                        url:'/admin/settings/change-password/submit',
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
    });

    $("#btn_submit_user").on("click", function() {
        bootbox.confirm({
            title: "Save Data ?",
            centerVertical:true,
            message: "Do you want to save the data now? This cannot be undone.",
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
                    $('#form_new_user').submit();
                }
            },
            onShow: function(e) {
                feather.replace();
            }
        });
    });

    $("#btn_update_user").on("click", function() {
        bootbox.confirm({
            title: "Update Data ?",
            centerVertical:true,
            message: "Do you want to update the data now? This cannot be undone.",
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
                    $('#form_update_user').submit();
                }
            },
            onShow: function(e) {
                feather.replace();
            }
        });
    });

    $('#form_new_user').on('submit', function(event){
        event.preventDefault();

        var btn = $("#btn_submit_company");
        btn.attr("disabled", "disabled");

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:'/admin/settings/user-admin/submit',
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
                            btn.removeAttr("disabled");
                        },
                        onHide: function(e) {
                            window.location = "/admin/settings/user-admin"
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

    $('#form_update_user').on('submit', function(event){
        event.preventDefault();

        var btn = $("#btn_update_user");
        btn.attr("disabled", "disabled");

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:'/admin/settings/user-admin/update',
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
                var text = '';
                var res = JSON.parse(response);
                if(res.status) {
                    close_loading();
                    bootbox.alert({
                        title: "Success!",
                        message: "<i data-feather='check'></i> Data has been updated.",
                        centerVertical:true,
                        onShow: function(e) {
                            feather.replace();
                        },
                        callback: function() {
                            btn.removeAttr("disabled");
                        },
                        onHide: function(e) {
                            window.location = "/admin/settings/user-admin"
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
});

function delete_user(id) {
    bootbox.confirm({
        title: "Delete User ?",
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
                    url:'/admin/settings/user-admin/delete',
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