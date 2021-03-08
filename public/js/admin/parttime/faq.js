$(document).ready(function() {

});

function init_data_table() {
    let table = $('#dt_faq');
    if (table != null) {
        table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/part-time/faq/paging',
                type:"POST",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.vacancy_id = $("#vacancy_id").val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'type', name: 'type'},
                { data: 'question', name: 'question'},
                { data: 'answer', name: 'answer'}
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        });
    }
}