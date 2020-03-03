$('.input-file').on('change',function(){

    var fileName = $(this).val();
    $(this).next('label').html(fileName);
})
$("#group2").hide();
$("#group3").hide();
$("#group4").hide();
function change_visibility(id1,id2) {
    $("#"+id1).hide();
    $("#"+id2).show();
}
