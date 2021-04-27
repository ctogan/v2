var del_url = '/admin/operation/banner/delete';
$(document).ready(function() {
    init_data_table();

    $('#edit_banner_modal').on('show.bs.modal', function(e) {
        let id = $(e.relatedTarget).data('id');
        let url = '/admin/operation/banner/edit/' + id;
        $.get(url, function (response) {
            let data = JSON.parse(response);
            $("#edit_banner_modal #id").val(id);
            $('#edit_banner_modal #banner_name').val(data.data.banner_name);
            $('#edit_banner_modal #deeplink').val(data.data.deeplink);

            if (data.data.row_status === 'active') {
                $('#edit_banner_modal #active').attr('checked', true);
            } else {
                $('#edit_banner_modal #inactive').attr('checked', true);
            }
            $('#edit_banner_modal #preview_edit_banner').attr('src', data.data.img);

            $("#loading-content").hide(function () {
                $("#edit_banner_modal").addClass('loaded');
                $(".btn-submit").show();
                $("#form-content").show();
            });
        })
    });

    $('#edit_banner_modal').on('hidden.bs.modal', function(e) {
        if(!$('#edit_banner_modal').hasClass('loaded')) {
            $("#loading-content").show();
            $("#form-content").hide();
        }
    });

    $("#dt_banner_app tbody").sortable({
        update:function (event, ui) {
            $(this).children().each(function (index) {
                if($(this).attr('data-position') !== (index+1)){
                    $(this).attr('data-position', (index+1)).addClass('updated');
                    $("#seq_"+$(this).attr('data-index')).html(index+1);
                    $("#status_update_position").val("true");
                }
            });
            save_new_positions();
            var table = $('#dt_banner_app').DataTable();
            table.ajax.reload(
                function () {
                    feather.replace();
                }
            );
        }
    });
});

function init_data_table(){
    let table = $('#dt_banner_app');
    if (table != null) {
        table.DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/operation/banner/paging',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: 'sequence', name: 'sequence'},
                { data: 'img', name: 'img' },
                { data: 'banner_name', name: 'banner_name' },
                { data: 'deeplink', name: 'deeplink' },
                { data: 'row_status', name: 'row_status'},
                { data: 'id', name: 'id'},
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer');
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
                        return '<a href="javascript:void(0)" data-id="'+data+'" data-toggle="modal" data-target="#edit_banner_modal" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="edit"></i></a>' +
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
            url:'/admin/operation/banner/position/update',
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