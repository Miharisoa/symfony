$('#table_users1').Tabledit({
    type: 'post',
    url: '/ajax',
    hideIdentifier: true,
    editButton: true,
    deleteButton: false,
    removeButton: false,
    columns: {
        identifier: [0, 'id'],
        editable: [
            [1, 'Username'],
            [2, 'First Name'],
            [3, 'Last Name'],
            [4, 'Address']
        ] //, '{"1": "@mdo", "2": "@fat", "3": "@twitter"}'
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


