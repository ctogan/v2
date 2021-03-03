function init_data_table() {
    let table = $('#dt_applicant');
    if (table != null) {
        table.DataTable({
            order:[8,'desc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/part-time/applicant/paging',
                type:"POST",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'uid', name: 'uid' },
                { data: 'applicant_name', name: 'applicant_name' },
                { data: 'company_name', name: 'company_name' },
                { data: 'position_name', name: 'position_name'},
                { data: 'category_name', name: 'category_name'},
                { data: 'province_name', name: 'province_name'},
                { data: 'city_name', name: 'city_name'},
                { data: 'apply_date', name: 'apply_date'},
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
                    targets: 2,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="http://admin.ctree.id/admin/user/detail?uid='+full.uid+'">'+data+'</a>'
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/part-time/company/edit/'+full.company_id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/part-time/vacancy/edit/'+full.vacancy_id+'">'+data+'</a>'
                    }
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        });
    }
}