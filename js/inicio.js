$(function() {
    $("#mostPass").click(function() {
        var x = document.getElementById("txtPassword");


        if (x.type == "text") {
            $("#iconPass").removeClass();
            $("#iconPass").addClass('pass fas fa-eye');
            x.type = "password";
        } else if (x.type == "password") {
            $("#iconPass").removeClass();
            $("#iconPass").addClass('pass fas fa-eye-slash');
            x.type = "text";
        }
    });
});