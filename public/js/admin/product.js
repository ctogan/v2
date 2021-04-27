var del_url = '/admin/news/delete';
$(document).ready(function() {
    init_data_table();

    $('#edit_product_modal').on('show.bs.modal', function(e) {
        let id = $(e.relatedTarget).data('id');
        let url = '/admin/product/edit/' + id;
        $.get(url, function (response) {
            let data = JSON.parse(response);
            $("#edit_product_modal #id").val(id);
            $('#edit_product_modal #product_code').val(data.data.product_code);
            $('#edit_product_modal #product_name').val(data.data.product_name);

            if (data.data.row_status === 'active') {
                $('#edit_product_modal #active').attr('checked', true);
            } else {
                $('#edit_product_modal #inactive').attr('checked', true);
            }
            $('#edit_product_modal #preview_edit_product').attr('src', data.data.img);

            $("#loading-content").hide(function () {
                $("#edit_product_modal").addClass('loaded');
                $(".btn-submit").show();
                $("#form-content").show();
            });
        })
    });

    $('#edit_product_modal').on('hidden.bs.modal', function(e) {
        $("#loading-content").show();
        $("#form-content").hide();
    });
});

function init_data_table(){
    let table = $('#dt_product_app');
    if (table != null) {
        table.DataTable({
            order:[9,'desc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/product/paging',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'product_code', name: 'product_code' },
                { data: 'product_name', name: 'product_name' },
                { data: 'img', name: 'img' },
                { data: 'created_at', name: 'created_at' },
                { data: 'created_by', name: 'created_by' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'updated_by', name: 'updated_by' },
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
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        return '<img src="'+data+'" width="75px">';
                    }
                },
                {
                    targets: 4,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 6,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 8,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        let status = {active : 'badge badge-success', inactive : 'badge badge-dark'};
                        return '<span class="text-capitalize p-1 '+status[data]+'">'+data+'</span>'
                    }
                },
                {
                    targets: 9,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="javascript:void(0)" data-id="'+data+'" data-toggle="modal" data-target="#edit_product_modal" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="edit"></i></a>';
                    },
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        })
    }
}