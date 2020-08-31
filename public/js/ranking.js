$(document).on("mouseover", ".salsp", function () {
    $(this).addClass('spbd');
    $(this).parent().children('.collection').removeClass('spbd');
    $(this).parent().parent().children('.sal').removeClass('hidden');
    $(this).parent().parent().children('.colle').addClass('hidden');
});
$(document).on("mouseover", ".collection", function () {
    $(this).addClass('spbd');
    $(this).parent().children('.salsp').removeClass('spbd');
    $(this).parent().parent().children('.colle').removeClass('hidden');
    $(this).parent().parent().children('.sal').addClass('hidden');
});