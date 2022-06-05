$(function() {
    $("#mostPass").click(function() {
        var x = document.getElementById("txtContrasena");
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
    $("#frmLogin").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            contrasena: {
                required: true,
                minlength: 5
            }
        },
        messages: {
            contrasena: {
                required: "Contraseña es requerida",
            },
            email: "Email es requerido",
        },
        errorPlacement: function(error, element) {
            console.log(element);
            // error.appendTo('#divError');

            if (element.attr("name") == "email")
                error.appendTo('#divErrorE');
            else if (element.attr("name") == "contrasena")
                error.appendTo('#divErrorP');
        },
        submitHandler: function(form) {
            // location.href = "panel.html";
            // console.log(form.email.value);
            // console.log(form.contrasena.value);


            // $.ajax({
            //     url: "https://localhost:44335/api/Usuarios/ValidarUsuarios",
            //     type: "GET",
            //     dataType: "dataType: 'json',",
            //     data: $("#frmLogin").serializeObject(),
            //     success: function(data) {
            //         console.log(data);
            //         swal("error", "No se pudo realizar la operación", "error");
            //     },
            //     error: function(data) {
            //         console.log(data);
            //         console.log("Error");
            //         swal("error", "No se pudo realizar la operación", "error");
            //     },
            // });
        }
    });
    $("#frmRegistro").validate({
        rules: {
            nEmpleado: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 5
            }
        },
        messages: {
            password: "Contraseña es requerida",
            email: "Email es requerido",
            nEmpleado: "Número de empleado es requerido"
        },
        errorPlacement: function(error, element) {
            console.log(element);
            // error.appendTo('#divError');

            if (element.attr("name") == "email")
                error.appendTo('#divErrorE');
            else if (element.attr("name") == "password")
                error.appendTo('#divErrorP');
            else if (element.attr("name") == "nEmpleado")
                error.appendTo("#divErrorN");
        },
        submitHandler: function(form) {
            location.href = "index.html";
        }
    });
});