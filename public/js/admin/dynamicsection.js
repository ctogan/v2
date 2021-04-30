var del_url = '/admin/dynamic-section/delete';

$(document).ready(function(){
    init_data_table();

    $(document).on('change' ,'#target' , function(){
        let val = $(this).val();
        check_target(val, '#add_new_dynamic_section');
    });

    $(document).on('change' ,'#target_edit' , function(){
        let val = $(this).val();
        check_target(val, '#edit_dynamic_section');
    });

    $('#edit_dynamic_section').on('show.bs.modal', function(e) {
        let id = $(e.relatedTarget).data('id');
        let url = '/admin/dynamic-section/edit/'+ id;
        $.get(url, function (response) {
            let data = JSON.parse(response);
            $("#edit_dynamic_section #id").val(id);
            $('#edit_dynamic_section #title').val(data.data.title);
            $('#edit_dynamic_section #sub_title').val(data.data.sub_title);
            $('#edit_dynamic_section #deeplink').val(data.data.deeplink);
            $('#edit_dynamic_section #target_edit').val(data.data.target);
            $('#edit_dynamic_section #url').val(data.data.url);
            $('#edit_dynamic_section #deeplink').val(data.data.deeplink);
            $('#edit_dynamic_section #snapcash_id').val(data.data.snapcash_id);
            $('#edit_dynamic_section #adid').val(data.data.adid);

            if(data.data.row_status === 'active'){
                $('#edit_dynamic_section #active').attr('checked', true);
            }else{
                $('#edit_dynamic_section #inactive').attr('checked', true);
            }
            $('#edit_dynamic_section #preview_edit_dynamic_section_img').attr('src', data.data.dynamic_section_img);

            check_target(data.data.target, '#edit_dynamic_section');

            $("#loading-content").hide(function () {
                $(".btn-submit").show();
                $("#form-content").show();
            });
        })
    });
});

function check_target(val, modal) {
    if(val === 'inapp'){
        $(modal + ' #url').removeClass('hide');
        $(modal + ' #deeplink').addClass('hide');
        $(modal + ' #snapcash').addClass('hide');
        $(modal + ' #campaign').addClass('hide');
    }else if(val === 'default_browser'){
        $(modal + ' #url').removeClass('hide');
        $(modal + ' #deeplink').addClass('hide');
        $(modal + ' #snapcash').addClass('hide');
        $(modal + ' #campaign').addClass('hide');
    }else if(val === 'deeplink'){
        $(modal + ' #deeplink').removeClass('hide');
        $(modal + ' #url').addClass('hide');
        $(modal + ' #snapcash').addClass('hide');
        $(modal + ' #campaign').addClass('hide');
    }else if(val === 'snapcash'){
        $(modal + ' #snapcash').removeClass('hide');
        $(modal + ' #url').addClass('hide');
        $(modal + ' #deeplink').addClass('hide');
        $(modal + ' #campaign').addClass('hide');
    }else if(val === 'campaign'){
        $(modal + ' #campaign').removeClass('hide');
        $(modal + ' #url').addClass('hide');
        $(modal + ' #deeplink').addClass('hide');
        $(modal + ' #snapcash').addClass('hide');
    }else{
        $(modal + ' #campaign').addClass('hide');
        $(modal + ' #url').addClass('hide');
        $(modal + ' #deeplink').addClass('hide');
        $(modal + ' #snapcash').addClass('hide');
    }
}

function init_data_table(){
    let table = $('#dt_dynamic_section');
    if (table != null) {
        table.DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/dynamic-section/paging',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'dynamic_section_img', name: 'dynamic_section_img'},
                { data: 'title', name: 'title' },
                { data: 'sub_title', name: 'sub_title' },
                { data: 'target', name: 'target' },
                { data: 'created_at', name: 'created_at' },
                { data: 'created_by', name: 'created_by' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'updated_by', name: 'updated_by' },
                { data: 'row_status', name: 'row_status'},
                { data: 'id', name: 'id'},
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-index', data.id);
                $(row).attr('data-position', data.sequence);
            },
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                },
                {
                    targets: 1,
                    className: "text-center",
                    render:function (data) {
                        return '<img src="'+data+'" width="75" height="75" style="object-fit: cover"/>'
                    }
                },
                {
                    targets: 4,
                    render:function (data, type, full, meta) {
                        let target = {inapp : 'In App Browser',
                            default_browser : 'Default Browser',
                            deeplink : 'Deeplink',
                            snapcash : 'Snapcash',
                            campaign : 'Campaign'
                        };
                        return target[data];
                    }
                },
                {
                    targets: 5,
                    render:function (data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 7,
                    render:function (data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 9,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        let status = {active : 'badge badge-success', inactive : 'badge badge-dark'};
                        return '<span class="text-capitalize p-1 '+status[data]+'">'+data+'</span>'
                    }
                },
                {
                    targets: 10,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="javascript:void(0)" data-id="'+data+'" data-toggle="modal" data-target="#edit_dynamic_section" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="edit"></i></a>' +
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