function init_data_table() {
    let table = $('#dt_applicant');
    if (table != null) {
        table.DataTable({
            order:[10,'desc'],
            responsive: {
                details: {
                    renderer: function ( api, rowIdx, columns ) {
                        var data = $.map( columns, function ( col, i ) {
                            return col.hidden ?
                                '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+''+'</td> '+
                                '<td>'+col.data+'</td>'+
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
                url: '/admin/part-time/applicant/paging',
                type:"POST",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.vacancy_id = $("#vacancy_id").val();
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'img', name: 'img'},
                { data: 'uid', name: 'uid' },
                { data: 'applicant_name', name: 'applicant_name'},
                { data: 'company_name', name: 'company_name' },
                { data: 'position_name', name: 'position_name'},
                { data: 'category_name', name: 'category_name'},
                { data: 'province_name', name: 'province_name'},
                { data: 'city_name', name: 'city_name'},
                { data: 'apply_date', name: 'apply_date'},
                { data: 'pob', name: 'pob'},
                { data: 'dob', name: 'dob'},
                { data: 'sex', name: 'sex'},
                { data: 'phone', name: 'phone'},
                { data: 'email', name: 'email'},
                { data: 'weight', name: 'weight'},
                { data: 'height', name: 'height'},
                { data: 'religion', name: 'religion'},
                { data: 'last_education', name: 'last_education'},
                { data: 'skills', name: 'skills'},
                { data: 'hobby', name: 'hobby'},
                { data: 'address', name: 'address'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                },
                {
                    targets: 1,
                    className: "text-center",
                },
                {
                    targets: 2,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<img src="'+data+'" class="applicant-profile">'
                    }
                },
                {
                    targets: 3,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="http://admin.ctree.id/admin/user/detail?uid='+full.uid+'">'+data+'</a>'
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/part-time/company/edit/'+full.company_id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 6,
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