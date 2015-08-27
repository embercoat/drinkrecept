$(function() {
    $(".rmIng").click(function(x) {
        //~ $($(x.target).parent()).fadeOut("slow");
        id = $($(x.target).parent()).attr('id');
        return false;
    });
});
