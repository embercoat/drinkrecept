function updatePopup(bid){
    $("#updatePopup").css("display", "inline");
    $("#updateform").attr("action", "/backend/updatebar/"+bid);
    document.getElementById("password").focus();
}
function delPopup(bid){
    $("#updatePopup").css("display", "inline");
    $("#updateform").attr("action", "/backend/delbar/"+bid);
    document.getElementById("password").focus();
}