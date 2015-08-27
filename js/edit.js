function addIng(){
    $("#ing").append("<li style='display: block;'><input type='text' name='amount[]' style='width: 70px;' value='' autocomplete='off' /><input type='text' style='width: 190px;' name='ing[]' class='ing' /><input type='hidden' name='iid[]' /></li>");
    $(".ing").autocomplete(res);

}
var res = [];
$(document).ready(function(){
    $.getJSON('/backend/json/drinks/', function(data) {
        $.each(data, function(key, val) {
            res.push(val);
        });
    $(".ing").autocomplete(res);
    });
});

