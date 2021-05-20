var del_url = '/admin/flash-event/delete';
$(document).ready(function(){
    $("#btn_add_product").on('click',function (e) {
        let rownumber = parseInt($(this).attr('data-row')) + 1;
        $(this).attr('data-row',rownumber);

        $('#table_flash_product').append('<tr>'+
            '<td>'+
            '<select id="'+rownumber+'" style="width: 100%" class="form-control custom-select select2 '+ rownumber +'" name="detail['+rownumber+'][product_id]">    '+
            $('#selection-field').html()+
            '</select>'+
            '</td>'+
            '<td><input type="number" class="form-control" name="detail['+rownumber+'][point]" placeholder="Enter Point Count"></td>'+
            '<td>'+
            '<input type="number" class="form-control" name="detail['+rownumber+'][cap]" placeholder="Enter Cap Value">'+
            '</td>'+
            '<td align="center"><a class="btn delete_field" href="javascript:void(0)"><i data-feather="trash" aria-hidden="true"></i></a>'+
            '</td>'+
            '</tr>');

        feather.replace();
        $('#'+rownumber).select2();
    });

    $("#period").on('change',function (e) {
        let val = $(this).val();
        if(val === 'daily'){
            $("#daily").removeClass('hide');
            $("#weekly").addClass('hide');
            $("#special_date").addClass('hide');
        }
        else if(val === 'weekly'){
            $("#weekly").removeClass('hide');
            $("#daily").addClass('hide');
            $("#special_date").addClass('hide');
        }
        else if(val === 'special_date'){
            $("#special_date").removeClass('hide');
            $("#weekly").addClass('hide');
            $("#daily").addClass('hide');
        }else{
            console.log('Period Type Not Found')
        }
    })
    
    $("#is_registered_date").on('change', function (e) {
        if($(this).is(':checked')){
            $("input[name=target_registered]").removeAttr('disabled');
            $("#ut_by_register_date").val(true);
            $('input[name="target_registered"]').daterangepicker();
        } else {
            $("input[name=target_registered]").attr('disabled', 'disabled');
            $("#ut_by_register_date").val(false);
            $('input[name="target_registered"]').val('');
        }
    });

    $("#is_point_count").on('change', function (e) {
        if($(this).is(':checked')){
            $("input[name=target_point_from]").removeAttr('disabled');
            $("input[name=target_point_to]").removeAttr('disabled');
            $("#ut_by_point_count").val('true');
        } else {
            $("input[name=target_point_from]").attr('disabled', 'disabled');
            $("input[name=target_point_to]").attr('disabled','disabled');
            $("#ut_by_point_count").val('false');
        }
    });

    init_data_table();
});

$(document).on('click' ,'.delete_field' , function(){
    $(this).closest("tr").remove();
});

$(document).on('click', '.delete_from_db', function () {
    let row = $(this);
    let code = $(this).attr('data-code');
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
                    url:'/admin/flash-event-detail/delete',
                    method:"POST",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data:{code:code},
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
                                    row.closest("tr").remove();
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

function init_data_table(){
    let table = $('#dt_flash_event');
    if (table != null) {
        table.DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/flash-event/paging',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'event_code', name: 'event_code' },
                { data: 'event_name', name: 'event_name' },
                { data: 'detail_count', name: 'detail_count' },
                { data: 'event_period', name: 'event_period' },
                { data: 'created_at', name: 'created_at' },
                { data: 'created_by', name: 'created_by' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'updated_by', name: 'updated_by' },
                { data: 'event_period', name: 'event_period' },
                { data: 'status', name: 'status' },
                { data: 'row_status', name: 'row_status'},
                { data: 'id', name: 'id'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                },
                {
                    targets: 3,
                    className: "text-center"
                },
                {
                    targets: 4,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        let status = {daily : 'badge badge-success', weekly : 'badge badge-warning', special_date : 'badge badge-danger'};
                        let text = {daily : 'Daily', weekly : 'Weekly', special_date : 'Special Date'};
                        return '<span class="text-capitalize p-1 '+status[data]+'">'+text[data]+'</span>'
                    }
                },
                {
                    targets: 5,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 7,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 9,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        if(data === 'special_date'){
                            return 'S : ' + full.event_start.date.replace(':00.000000','') + ' <br/> ' + 'E : ' + full.event_end.date.replace(':00.000000','') ;
                        }else if(data === 'weekly'){
                            return full.day_name;
                        }else{
                            return 'S : ' + full.event_start.date.replace(':00.000000','') + ' <br/> ' + 'E : ' + full.event_end.date.replace(':00.000000','') ;
                        }
                    }
                },
                {
                    targets: 10,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        let status = {running : 'badge badge-success', waiting:'badge badge-danger', expired : 'badge badge-dark'};
                        return '<span class="text-capitalize p-1 '+status[data]+'">'+data+'</span>'
                    }
                },
                {
                    targets: 11,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        let status = {active : 'badge badge-success', inactive : 'badge badge-dark'};
                        return '<span class="text-capitalize p-1 '+status[data]+'">'+data+'</span>'
                    }
                },
                {
                    targets: 12,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/flash-event/'+data+'" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="edit"></i></a>' +
                            '<a onclick="del('+data+',\''+ del_url +'\')" href="javascript:void(0)" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0"><i data-feather="trash"></i></a>';
                    },
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        })
    }
}
