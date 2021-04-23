function to_date_time(date) {
    let tanggal = new Date(date);
    return tanggal.getFullYear()+"-"
        + (tanggal.getMonth()+ 1 > 9 ? (tanggal.getMonth()+ 1).toString() : "0" + (tanggal.getMonth()+ 1).toString())
        +"-"
        +(tanggal.getDate() > 9 ? tanggal.getDate().toString() : "0" + tanggal.getDate().toString())
        + " "
        +(tanggal.getUTCHours().toString() > 9 ? tanggal.getUTCHours().toString() : "0" + tanggal.getUTCHours().toString())
        + ":" + (tanggal.getUTCMinutes().toString() > 9 ? tanggal.getUTCMinutes().toString() : "0" + tanggal.getUTCMinutes().toString())
        + ":" + (tanggal.getUTCSeconds().toString() > 9 ? tanggal.getUTCSeconds().toString() : "0" + tanggal.getUTCSeconds().toString());
}

function refresh_datatable(id) {
    $(id).select2({
        theme: "bootstrap4"
    }).trigger('change');
}

function read_url(){
    document.getElementById(event.target.id).nextElementSibling.innerHTML = event.target.files[0].name;

    var reader = new FileReader(event);
    let preview_id = 'preview_' + event.target.id;
    reader.onload = function() {
        var output = document.getElementById(preview_id);
        output.src = reader.result;
    };

    reader.readAsDataURL(event.target.files[0]);
}

function read_url2(){
    var reader = new FileReader(event);
    let preview_id = 'preview_' + event.target.id;
    reader.onload = function() {
        var output = document.getElementById(preview_id);
        output.src = reader.result;
    };

    reader.readAsDataURL(event.target.files[0]);
}