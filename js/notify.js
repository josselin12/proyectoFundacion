$(function () {
    var ruta = "http://localhost:8080/proyectoFundacion/";
    var pagina = localStorage.getItem('pagina');
    var eliminar = localStorage.getItem('eliminar');

    $("#txtEliminar").val(eliminar);


    if ($("#txAccion").val() == "Agregar") {
        Swal.fire({
            icon: 'success',
            title: 'Registro agregado'
        });

    } else if ($("#txAccion").val() == "Modificar") {
        Swal.fire({
            icon: 'success',
            title: 'Registro modificado'
        });
    } else if ($("#txAccion").val() == "Eliminar" && eliminar == null) {
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar el registro?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Sí',
            denyButtonText: `No`,
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.setItem('eliminar', "SI");

                $("#txtEliminar").val("SI");
                document.forms["formUser"].submit();

            } else if (result.isDenied) {
                localStorage.removeItem('eliminar');
                

                let timerInterval;

                Swal.fire({
                    title: 'Cancelación eliminación registro',
                    icon: 'info',
                    html:
                        'La ventana se cerrará en <strong></strong> segundos.<br/><br/>',
                    timer: 3000,
                    didOpen: () => {
                        const content = Swal.getHtmlContainer()
                        const $ = content.querySelector.bind(content)

                        Swal.showLoading()

                        function toggleButtons() {
                            stop.disabled = !Swal.isTimerRunning()
                            resume.disabled = Swal.isTimerRunning()
                        }

                        timerInterval = setInterval(() => {
                            Swal.getHtmlContainer().querySelector('strong')
                                .textContent = (Swal.getTimerLeft() / 1000)
                                    .toFixed(0)
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                        localStorage.removeItem('eliminar');
                        $("#txtEliminar").val("NO");
                        document.forms["formUser"].submit();
                    }
                });
            }
        });
    } else if ($("#txAccion").val() == "Eliminar" && eliminar == "SI") {
        localStorage.removeItem('eliminar');
        Swal.fire({
            icon: 'error',
            title: 'Registro eliminado',
            willClose: () => {
                this.location.href = ruta + pagina;
            }
        });
        
    } else if ($("#txAccion").val() == "Error") {
        Swal.fire({
            icon: 'info',
            title: 'El registro ya se encuentra almacenado, intente con otro.'
        });
    } else if ($("#txAccion").val() == "Vacio") {
        Swal.fire({
            icon: 'info',
            title: 'Existen campos vacíos en el formulario. Rellenar todos por favor'
        });
    }


});