$(document).ready(function() {
    $("#btn_submit_faq").on("click", function() {
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
                    $('#form_new_faq').submit();
                }
            },
            onShow: function(e) {
                feather.replace();
            }
        });
    });

    $("#btn_update_faq").on("click", function() {
        bootbox.confirm({
            title: "Update Data ?",
            centerVertical:true,
            message: "Do you want to update the data now? ",
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
                    $('#form_update_faq').submit();
                }
            },
            onShow: function(e) {
                feather.replace();
            }
        });
    });

    $('#form_new_faq').on('submit', function(event){
        event.preventDefault();

        var btn = $("#btn_submit_faq");
        btn.attr("disabled", "disabled");

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:'/admin/part-time/faq/submit',
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
                            window.location = "/admin/part-time/faq"
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

    $('#form_update_faq').on('submit', function(event){
        event.preventDefault();

        var btn = $("#btn_update_faq");
        btn.attr("disabled", "disabled");

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:'/admin/part-time/faq/update',
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
                        title: "Success",
                        message: "<i data-feather='check'></i> Data has been updated.",
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

function init_data_table() {
    let table = $('#dt_faq');
    if (table != null) {
        table.DataTable({
            responsive:true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/part-time/faq/paging',
                type:"POST",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.vacancy_id = $("#vacancy_id").val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'type', name: 'type'},
                { data: 'question', name: 'question'},
                { data: 'answer', name: 'answer'},
                { data: 'id', name: 'id'}
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/part-time/faq/edit/'+data+'" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="edit"></i></a>' +
                            '<a onclick="delete_faq('+data+')" href="javascript:void(0)" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0"><i data-feather="trash"></i></a>';
                    },
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        });
    }
}

function delete_faq(id){
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
                    url:'/admin/part-time/faq/delete',
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