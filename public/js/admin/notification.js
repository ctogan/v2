$(document).on('change' ,'#target' , function(){
    let v = $(this).val();
    if(v === 'all'){
        $("#target_uid").hide();
    }else{
        $("#target_uid").show();
    }
});

function init_data_table(){
    let table = $('#dt_notification');
    if (table != null) {
        table.DataTable({
            order:[7,'desc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/notification/paging',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'title', name: 'title' },
                { data: 'body', name: 'body' },
                { data: 'img', name: 'img' },
                { data: 'send_to', name: 'send_to' },
                { data: 'created_at', name: 'created_at' },
                { data: 'created_by', name: 'created_by' },
                { data: 'id', name: 'id'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                },
                {
                    targets: 2,
                    className: "td-wrap",
                    render:function (data, type, full, meta) {
                        return '<div class="ellipsis ellipsis-3" style="width: 200px;white-space: break-spaces;">'+data+'</div>'
                    }
                },
                {
                    targets: 3,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        if(data !== null && data !== ''){
                            return '<img src="'+data+'" width="100px">'
                        }
                        return '';
                    }
                },
                {
                    targets: 4,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        let target = {all : 'badge badge-success', uid : 'badge badge-dark'};
                        return '<span class="text-uppercase p-1 '+target[data]+'">'+data+'</span>'
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
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/notification/'+data+'" data-id="'+data+'" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="eye"></i></a>';
                    },
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        })
    }
}

function init_detail_table(id){
    let table = $('#dt_notification_detail');
    if (table != null) {
        table.DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/notification/paging/detail',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.id = id;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'uid', name: 'title' },
                { data: 'is_read', name: 'is_read' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                },
                {
                    targets: 2,
                    className: "td-wrap",
                    render:function (data, type, full, meta) {
                        if(data){
                            return 'Yes';
                        }

                        return 'No';
                    }
                },
                {
                    targets: 3,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 4,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        if(data !== null){
                            return to_date_time(data);
                        }

                        return '';
                    }
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        })
    }
}