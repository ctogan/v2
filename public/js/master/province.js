$(document).ready(function() {
    $("#btn_submit_province").on("click", function() {
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
                    $('#form_new_province').submit();
                }
            },
            onShow: function(e) {
                feather.replace();
            }
        });
    });

    $("#btn_update_province").on("click", function() {
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
                    $('#form_update_province').submit();
                }
            },
            onShow: function(e) {
                feather.replace();
            }
        });
    });

    $('#form_new_province').on('submit', function(event){
        event.preventDefault();

        var btn = $("#btn_submit_province");
        btn.attr("disabled", "disabled");

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:'/admin/master/province/submit',
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
                        message: "<i data-feather='check'></i> Data has been inserted.",
                        centerVertical:true,
                        onShow: function(e) {
                            feather.replace();
                        },
                        callback: function() {
                            btn.removeAttr("disabled");
                        },
                        onHide: function(e) {
                            window.location = "/admin/master/province"
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

    $('#form_update_province').on('submit', function(event){
        event.preventDefault();

        var btn = $("#btn_update_province");
        btn.attr("disabled", "disabled");

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:'/admin/master/province/update',
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

function init_data_table(){
    let table = $('#dt_province');

    if (table != null) {
        table.DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/master/province/paging',
                type:"POST",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'province_name', name: 'province_name' },
                { data: 'id', name: 'id' },
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                },
                {
                    targets: 2,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a data-toggle="tooltip" title="View List of City" href="/admin/master/city?province='+data+'" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="list"></i></a>'+
                            '<a href="/admin/master/city/add?province='+data+'" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="plus"></i></a>'+
                            '<a href="/admin/master/province/edit/'+data+'" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="edit"></i></a>' +
                            '<a onclick="delete_city('+data+')" href="javascript:void(0)" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0"><i data-feather="trash"></i></a>';
                    },
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        })
    }
}

function delete_city(id){
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
                    url:'/admin/master/province/delete',
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