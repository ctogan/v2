$(document).ready(function() {
    $("#company_id").on("change", function () {
        let val = $(this).val();
        if(val !== ''){
            $.ajax({
                url : "/admin/data/company/"+val,
                type : 'GET',
                dataType: 'json',
                success : function (response) {
                    $('#province').val(response.province_id);
                    $('#province').select2({
                        theme: "bootstrap4"
                    }).trigger('change');
                    $('#hdn_city').val(response.city_id);
                }
            })
        }
    });

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

                    $('#city').val($('#hdn_city').val());
                    refresh_datatable("#city");
                }
            })
        }
    });

    $("#btn_submit_vacancy").on("click", function() {
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
                    $('#form_new_vacancy').submit();
                }
            },
            onShow: function(e) {
                feather.replace();
            }
        });
    });

    $("#btn_update_vacancy").on("click", function(){
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
                    $('#form_update_vacancy').submit();
                }
            },
            onShow: function(e) {
                feather.replace();
            }
        });
    });

    $("#btn_approve_vacancy").on("click", function(){
        bootbox.confirm({
            title: "Approve Data ?",
            centerVertical:true,
            message: "Do you want to approve the data now? ",
            buttons: {
                cancel: {
                    label: '<i data-feather="x"></i> Cancel'
                },
                confirm: {
                    label: '<i data-feather="check"></i> Approve'
                }
            },
            callback: function (result) {
                if(result){
                    loading();
                    var token = $('meta[name="csrf-token"]').attr('content');
                    let id = $("#id").val();

                    $.ajax({
                        url:'/admin/part-time/vacancy/approve',
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
                                    message: "<i data-feather='check'></i> Data has been approved.",
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
                                    onHide:function (e) {
                                        $("#vacancy_rejection_form").modal('show');
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

    $("#btn_open_reject_form").on("click", function(){
        $("#vacancy_rejection_form").modal('show');
        $("#txt_rejection_reason").focus();
    });

    $("#btn_reject_vacancy").on("click", function(){
        loading();
        var token = $('meta[name="csrf-token"]').attr('content');
        let id = $("#id").val();
        let rejection_reason= $("#txt_rejection_reason").val();

        $.ajax({
            url:'/admin/part-time/vacancy/reject',
            method:"POST",
            headers: {
                'X-CSRF-TOKEN': token
            },
            data:{id:id,rejection_reason:rejection_reason},
            success:function(response)
            {
                var text = '';
                var res = JSON.parse(response);
                if(res.status) {
                    $("#vacancy_rejection_form").modal('hide');
                    close_loading();
                    bootbox.alert({
                        title: "Success",
                        message: "<i data-feather='check'></i> Data has been rejected.",
                        centerVertical:true,
                        onShow: function(e) {
                            feather.replace();
                        },
                        onHide: function(e) {
                            location.reload();
                        }
                    });
                }else{
                    $("#vacancy_rejection_form").modal('hide');
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
                        onHide:function (e) {
                            $("#vacancy_rejection_form").modal('show');
                        }
                    });
                }
            }
        })
    });

    $('#form_new_vacancy').on('submit', function(event){
        event.preventDefault();

        var btn = $("#btn_submit_company");
        btn.attr("disabled", "disabled");

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:'/admin/part-time/vacancy/submit',
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
                        message: "<i data-feather='check'></i> Data has been saved.",
                        centerVertical:true,
                        onShow: function(e) {
                            feather.replace();
                        },
                        callback: function() {
                            btn.removeAttr("disabled");
                        },
                        onHide: function(e) {
                            window.location = "/admin/part-time/vacancy"
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

    $('#form_update_vacancy').on('submit', function(event){
        event.preventDefault();

        var btn = $("#btn_update_company");
        btn.attr("disabled", "disabled");

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:'/admin/part-time/vacancy/update',
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
    let table = $('#dt_vacancy');
    if (table != null) {
        table.DataTable({
            order:[18,'desc'],
            responsive: {
                details: {
                    renderer: function ( api, rowIdx, columns ) {
                        var data = $.map( columns, function ( col, i ) {
                            return col.hidden ?
                                '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+''+'</td> '+
                                '<td>'+' : '+col.data+'</td>'+
                                '</tr>' :
                                '';
                        } ).join('');

                        return data ?
                            $('<table/>').append( data ) :
                            false;
                    }
                }
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/part-time/vacancy/paging',
                type:"POST",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.company_filter = $("#company_filter").val();
                    d.category_filter = $("#category_filter").val();
                    d.province_filter = $("#province_filter").val();
                    d.city_filter = $("#city_filter").val();
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'position_name', name: 'position_name' },
                { data: 'company_name', name: 'company_name' },
                { data: 'category_name', name: 'category_name'},
                { data: 'province_name', name: 'province_name'},
                { data: 'city_name', name: 'city_name'},
                { data: 'experienced', name: 'experienced'},
                { data: 'salary', name: 'salary'},
                { data: 'allowance', name: 'allowance'},
                { data: 'send_to_email', name: 'send_to_email'},
                { data: 'send_to_wa', name: 'send_to_wa'},
                { data: 'active_until', name: 'active_until'},
                { data: 'created_at', name: 'created_at'},
                { data: 'created_by', name: 'created_by'},
                { data: 'created_at', name: 'updated_at'},
                { data: 'created_by', name: 'updated_by'},
                { data: 'vacancy_status', name: 'vacancy_status'},
                { data: 'id', name: 'id'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                },
                {
                    targets: 1,
                    className: "text-center"
                },
                {
                    targets: 7,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        if(data === 0){
                            return "Fresh Graduated"
                        }else if(data === 1){
                            return data + " Year";
                        }
                        else{
                            return data + " Years";
                        }
                    }
                },
                {
                    targets: 13,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 15,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 17,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        let status = {"pending":"badge-warning","published":"badge-primary","unpublished":"badge-info text-light","rejected":"badge-secondary"}

                        return '<span class="badge '+status[data]+'">'+data+'</span>'
                    }
                },
                {
                    targets: 18,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/part-time/vacancy/edit/'+data+'" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="edit"></i></a>' +
                            '<a onclick="delete_vacancy('+data+')" href="javascript:void(0)" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="trash"></i></a>' +
                            '<a href="/admin/part-time/applicant?vacancy='+data+'" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0"><i data-feather="search"></i></a>';
                    },
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        });
    }
}

function delete_vacancy(id) {
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
                    url:'/admin/part-time/vacancy/delete',
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