var del_url ='/admin/cerdas-cermat/question/delete';
var count = 20;
$(document).ready(function(){
    $('#edit_question_modal').on('show.bs.modal', function(e) {
        let id = $(e.relatedTarget).data('id');
        let url = '/admin/cerdas-cermat/question/edit/' + id;
        $.get(url, function (response) {
            let data = JSON.parse(response);
            $("#edit_question_modal #id").val(id);
            $('#edit_question_modal #question_text').val(data.data.question);
            $('#edit_question_modal #question_level').val(data.data.question_level);

            if (data.data.row_status === 'active') {
                $('#edit_question_modal #active').attr('checked', true);
            } else {
                $('#edit_question_modal #inactive').attr('checked', true);
            }

            if(data.data.question_image !== null && data.data.question_image !== ''){
                $('#edit_question_modal #preview_edit_question_img').attr('src', data.data.question_image);
            }else{
                $('#edit_question_modal #preview_edit_question_img').attr('src', '/assets/images/default.png');
            }

            $(".add_field").attr('data-row',data.data.answer.length + 1);
            $.each(data.data.answer, function(k, v) {
                let checked = v.is_correct_answer ? 'checked' : '';
                $('#table_edit_answer tbody').append(
                    '<tr id="'+k+'">'+
                    '<td><input type="hidden" value="'+v.id+'" name="answer['+k+'][id]"><input value="'+v.answer+'" type="text" class="form-control form-control-sm" name="answer['+k+'][option]"></td>'+
                    '<td class="vertical-align-middle" align="center">' +
                    '<input class="hdn_correct" type="hidden" value="'+v.is_correct_answer+'" name="answer['+k+'][is_correct]">'+
                    '<input class="chk_correct" name="answer['+k+'][is_correct]" value="true" type="checkbox" '+ checked +'>'+
                    '</td>'+
                    '<td align="center"><a data-answer-id="'+v.id+'" class="delete_field_db" href="javascript:void(0)"><i data-feather="trash"></i></a></td>'+
                    '</tr>'
                );
                feather.replace();
            });

            $("#loading-content").hide(function () {
                $(".btn-submit").show();
                $("#form-content").show();
            });
        })
    });

    $('#edit_question_modal').on('hidden.bs.modal', function(e) {
        $("#loading-content").show();
        $("#form-content").hide();
        $('#table_edit_answer tbody').html('');
    });

    $('#view_question_modal').on('show.bs.modal', function(e) {
        let id = $(e.relatedTarget).data('id');
        let url = '/admin/cerdas-cermat/question/edit/' + id;
        $.get(url, function (response) {
            let data = JSON.parse(response);
            $("#view_question_modal #id").val(id);
            $('#view_question_modal #question_text').val(data.data.question);
            $('#view_question_modal #question_level').val(data.data.question_level);

            if (data.data.row_status === 'active') {
                $('#view_question_modal #active').attr('checked', true);
            } else {
                $('#view_question_modal #inactive').attr('checked', true);
            }

            if(data.data.question_image !== null && data.data.question_image !== ''){
                $('#view_question_modal #preview_edit_question_img').attr('src', data.data.question_image);
            }else{
                $('#view_question_modal #preview_edit_question_img').attr('src', '/assets/images/default.png');
            }

            $(".add_field").attr('data-row',data.data.answer.length + 1);
            $.each(data.data.answer, function(k, v) {
                let checked = v.is_correct_answer ? 'checked' : '';
                $('#table_edit_answer tbody').append(
                    '<tr id="'+k+'">'+
                    '<td><input type="hidden" value="'+v.id+'" name="answer['+k+'][id]"><input value="'+v.answer+'" type="text" class="form-control form-control-sm" name="answer['+k+'][option]"></td>'+
                    '<td class="vertical-align-middle" align="center">' +
                    '<input class="hdn_correct" type="hidden" value="'+v.is_correct_answer+'" name="answer['+k+'][is_correct]">'+
                    '<input class="chk_correct" name="answer['+k+'][is_correct]" value="true" type="checkbox" '+ checked +'>'+
                    '</td>'+
                    '</tr>'
                );
                feather.replace();
            });

            $("#loading-content").hide(function () {
                $(".btn-submit").show();
                $("#form-content").show();
            });
        })
    });

    $('#view_question_modal').on('hidden.bs.modal', function(e) {
        $("#loading-content").show();
        $("#form-content").hide();
        $('#table_edit_answer tbody').html('');
    });
});

$(document).on('change' ,'.chk_correct' , function(){
    var status = $(this).prop('checked');
    var name = $(this).attr("name");
    $('.chk_correct').prop('checked', false);
    $(this).prop('checked',status);
    $('.hdn_correct').val(false);
    $('input[name="'+name+'"]').val(status);
});

$(document).on('click' ,'.delete_field' , function(){
    $(this).closest("tr").remove();
});

$(document).on('click', '.delete_field_db', function () {
    let id = $(this).attr('data-answer-id');
    let row = $(this);
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
                    url:'/admin/cerdas-cermat/answer/delete',
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

$(document).on('click' ,'.add_field' , function(){
    let rownumber = parseInt($(this).attr('data-row')) + 1;
    $(this).attr('data-row',rownumber);

    $('#table_answer tbody').append(
        '<tr id="'+rownumber+'">'+
        '<td>'+
            '<input type="hidden" value="0" name="answer['+rownumber+'][id]">'+
            '<input type="text" class="form-control form-control-sm" name="answer['+rownumber+'][option]">'+
        '</td>'+
        '<td class="vertical-align-middle" align="center">' +
            '<input class="hdn_correct" type="hidden" value="false" name="answer['+rownumber+'][is_correct]">'+
            '<input class="chk_correct" name="answer['+rownumber+'][is_correct]" value="true" type="checkbox">'+
        '</td>'+
        '<td align="center"><a class="delete_field" href="javascript:void(0)"><i data-feather="trash"></i></a></td>'+
        '</tr>'
    );
    feather.replace();
    $('table #'+rownumber).fadeIn('slow');
});

$(document).on('click','#add_prize', function () {
    let rownumber = parseInt($(this).attr('data-row')) + 1;
    $(this).attr('data-row',rownumber);
    $('#table_prize tbody').append(
        '<tr id="'+rownumber+'">'+
        '<td>'+
        '<input type="number" class="form-control" value="'+parseInt(rownumber+1)+'" name="prize['+rownumber+'][rank]">'+
        '</td>'+
        '<td>'+
        '<select id="'+rownumber+'" style="width: 100%" class="form-control custom-select select2'+ rownumber +'" name="prize['+rownumber+'][item]">'+
        $('#selection-field').html()+
        '</select>'+
        '</td>'+
        '<td align="center" class="vertical-align-middle"><a class="delete_field" href="javascript:void(0)"><i data-feather="trash"></i></a></td>'+
        '</tr>'
    );
    feather.replace();
    $('.select2'+rownumber).select2();
});

$(document).on('click','#btn_generate_question', function () {
    let btn = $(this);
    btn.addClass('post').attr('disabled','disabled');
    count = $('input[name="random_question"]').val();
    var table = $('#table_question_generated').DataTable();
    table.ajax.reload(
        function () {
            feather.replace();
            btn.removeClass('post');
            btn.removeAttr('disabled')
        }
    );
});

$(document).on('click','.delete_prize_db', function () {
    let id = $(this).attr('data-id');
    let row = $(this);
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
                    url:'/admin/cerdas-cermat/prize/delete',
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

function question_data_table() {
    let table = $('#dt_question');
    if (table != null) {
        table.DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/cerdas-cermat/question/paging',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'question', name: 'question' },
                { data: 'question_image', name: 'question_image' },
                { data: 'question_level', name: 'question_level' },
                { data: 'answer_count', name: 'answer_count' },
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
                    targets: 2,
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
                    className: "text-center"
                },
                {
                    targets: 3,
                    className: "text-capitalize",
                    render:function (data, type, full, meta) {
                        let level = {easy : 'badge badge-success', medium : 'badge badge-warning', hard : 'badge badge-danger'};
                        return '<span class="text-capitalize p-1 '+level[data]+'">'+data+'</span>'
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
                        let status = {active : 'badge badge-success', inactive : 'badge badge-dark'};
                        return '<span class="text-capitalize p-1 '+status[data]+'">'+data+'</span>'
                    }
                },
                {
                    targets: 10,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="javascript:void(0)" data-id="'+data+'" data-toggle="modal" data-target="#edit_question_modal" class="btn btn-datatable btn-icon btn-transparent-dark btn-sm p-0 mr-2"><i data-feather="edit"></i></a>' +
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

function session_question_data_table() {
    let table = $('#table_question_generated');
    if (table != null) {
        table.DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength:count,
            lengthChange:false,
            searching:false,
            paging:   false,
            ordering: false,
            ajax: {
                url: '/admin/cerdas-cermat/question/random_paging',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.count = count;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'question', name: 'question' },
                { data: 'question_image', name: 'question_image' },
                { data: 'question_level', name: 'question_level' },
                { data: 'answer_count', name: 'answer_count' },
                { data: 'correct_answer.answer', name: 'correct_answer.answer' },
                { data: 'id', name: 'id' },
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-center"
                },
                {
                    targets: 2,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        if(data !== null && data !== ''){
                            return '<img src="'+data+'" width="100px"><input type="hidden" name="cc_question_id['+full.DT_RowIndex+']" value="'+full.id+'">'
                        }
                        return '<input type="hidden" name="cc_question_id['+full.DT_RowIndex+']" value="'+full.id+'">';
                    }
                },
                {
                    targets: 4,
                    className: "text-center"
                },
                {
                    targets: 3,
                    className: "text-capitalize",
                    render:function (data, type, full, meta) {
                        let level = {easy : 'badge badge-success', medium : 'badge badge-warning', hard : 'badge badge-danger'};
                        return '<span class="text-capitalize p-1 '+level[data]+'">'+data+'</span>'
                    }
                },
                {
                    targets: 6,
                    className: "text-center",
                    render:function (data, type, full, meta) {
                        return '<a href="javascript:void(0)" data-id="'+data+'" data-toggle="modal" data-target="#view_question_modal"><i data-feather="eye"></i></a>'
                    }
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        })
    }
}

function session_data_table() {
    let table = $('#dt_session');
    if (table != null) {
        table.DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/cerdas-cermat/session-paging',
                type:"POST",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'title', name: 'title' },
                { data: 'question_count', name: 'question_count' },
                { data: 'open_date', name: 'open_date' },
                { data: 'registration_fee', name: 'registration_fee' },
                { data: 'prize_count', name: 'prize_count' },
                { data: 'participant_count', name: 'participant_count' },
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
                    targets: 2,
                    className: "text-center"
                },
                {
                    targets: 3,
                    className: "text-left",
                    render:function (data, type, full, meta) {
                        return data + ' <br/> ' + full.time_start + ' - ' + full.time_end
                    }
                },
                {
                    targets: 4,
                    className: "text-center"
                },
                {
                    targets: 5,
                    className: "text-center"
                },
                {
                    targets: 6,
                    className: "text-center"
                },
                {
                    targets: 7,
                    className: "text-left",
                    render:function (data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 9,
                    className: "text-left",
                    render:function (data, type, full, meta) {
                        return to_date_time(data);
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
                    render:function (data, type, full, meta) {
                        return '<a class="mr-2" href="/admin/cerdas-cermat/edit/'+data+'"><i data-feather="edit"></i></a>'+
                            '<a href="/admin/cerdas-cermat/report/'+data+'"><i data-feather="bar-chart-2"></i></a>'
                    }
                }
            ],
            drawCallback: function() {
                feather.replace();
            },
        })
    }
}