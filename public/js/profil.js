$('#table_info_name_user').Tabledit({
    type: 'post',
    url: '/ajax',
    hideIdentifier: true,
    editButton: true,
    deleteButton: false,
    removeButton: false,
    columns: {
        identifier: [0, 'id'],
        editable: [[1, 'Username'], [2, 'First Name'], [3, 'Last Name'],[4, 'Birthday']]
    },
    buttons:{
        edit:{"class":"btn grey btn-edit text-white",html:'<i class="fa fa-edit"></i>',action:"edit"},
        save:{"class":"btn btn-teal btn-save text-white",html:"<i class='fa fa-check'></i>"}
    },
    dangerClass: 'danger',
    onSuccess: function(data, status){
        console.log('success');
        console.log(data);
        return;
    },
    onFail: function(){
        alert('failed');
        return;
    },
});

$('#table_info_address_user').Tabledit({
    type: 'post',
    url: '/ajax',
    hideIdentifier: true,
    editButton: true,
    deleteButton: false,
    removeButton: false,
    columns: {
        identifier: [0, 'id'],
        editable: [[1, 'Address'], [2, 'Postal Code'], [3, 'City'],[4, 'Country']]
    },
    buttons:{
        edit:{"class":"btn grey btn-edit text-white",html:'<i class="fa fa-edit"></i>',action:"edit"},
        save:{"class":"btn btn-teal btn-save text-white",html:"<i class='fa fa-check'></i>"}
    },
    dangerClass: 'danger',
    onSuccess: function(data, status){
        console.log('success');
        console.log(data);
        return;
    },
    onFail: function(){
        alert('failed');
        return;
    },
});

// $('#input_new_picture').change(function () {
//     alert('hehehe');
//     var fd = new FormData();
//     var files = $('#input_new_picture')[0].files[0];
//     fd.append('file',files);
// });

// $('#btn_picture').click(function() {
//
//     // $('#input_new_picture').trigger('click');
//     var fd = new FormData();
//     var files = $('#input_new_picture')[0].files[0];
//     fd.append('file',files);
//
// });

$('#input_new_picture').hide();

$(function () {
    var fileupload = $("#input_new_picture");
    var button = $("#btn_picture");

    button.click(function () {
        fileupload.click();
    });

    fileupload.change(function () {
        var fileName = $(this).val().split('\\')[$(this).val().split('\\').length - 1];
        var form_data = new FormData();
        var files = $("#input_new_picture").prop('files')[0];
        form_data.append('file',files);

        $.ajax({
            url: '/fileupload',
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            // success: function(response){
            //     console.log('mety');
            //     alert(response.filename);
            //     window.location.href = '/profil';
            // }

        }).done(function(data){
            console.log('mety');
            // alert('il y a une erreur');
            alert(data.filename);
            window.location.href = '/profil';
        });

    });
});





