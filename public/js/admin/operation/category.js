var del_url = '/admin/operation/category/delete';
$(document).ready(function() {
    init_data_table();

    $('#edit_category_modal').on('show.bs.modal', function(e) {
        let id = $(e.relatedTarget).data('id');
        let url = '/admin/operation/category/edit/'+ id;
        $.get(url, function (response) {
            let data = JSON.parse(response);
            $("#edit_category_modal #id").val(id);
            $('#edit_category_modal #category_name').val(data.data.category_name);
            $('#edit_category_modal #deeplink').val(data.data.deeplink);

            if(data.data.row_status === 'active'){
                $('#edit_category_modal #active').attr('checked', true);
            }else{
                $('#edit_category_modal #inactive').attr('checked', true);
            }
            $('#edit_category_modal #preview_edit_img_category').attr('src', data.data.img);

            $("#loading-content").hide(function () {
                $(".btn-submit").show();
                $("#form-content").show();
            });
        })
    });

    $('#edit_category_modal').on('hidden.bs.modal', function(e) {
        $("#loading-content").show();
        $("#form-content").hide();
    });
    $("#dt_category_app tbody").sortable({
        update:function (event, ui) {
            $(this).children().each(function (index) {
                if($(this).attr('data-position') !== (index+1)){
                    $(this).attr('data-position', (index+1)).addClass('updated');
                    $("#seq_"+$(this).attr('data-index')).html(index+1);
                    $("#status_update_position").val("true");
                }
            });
            save_new_positions();
            var table = $('#dt_category_app').DataTable();
            table.ajax.reload(
                function () {
                    feather.replace();
                }
            );
        }
    });
});

function init_data_table(){
    let table = $('#dt_category_app');
    if (table != null) {
        table.DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/operation/category/paging',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: 'sequence', name: 'sequence'},
                { data: 'img', name: 'img' },
                { data: 'category_name', name: 'category_name' },
                { data: 'deeplink', name: 'deeplink' },
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
                    render:function (data, type, full, meta) {
                        return '<img src="'+data+'" width="200">';
                    }
                },
                {
                    targets: 4,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        let status = {active : 'badge badge-success', inactive : 'badge badge-dark'};
                        return '<span class="text-capitalize p-1 '+status[data]+'">'+data+'</span>'
                    }
                },
                {
                    targets: 5,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="javascript:void(0)" data-id="'+data+'" data-toggle="modal" data-target="#edit_category_modal" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="edit"></i></a>' +
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

function save_new_positions() {
    var positions =[];
    $('.updated').each(function () {
        positions.push([$(this).attr('data-index'),$(this).attr('data-position')])
        $(this).removeClass('updated');

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:'/admin/operation/category/position/update',
            method:'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            data:{
                positions:positions
            },
            success:function (response) {

            }
        });
    })
}