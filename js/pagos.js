$(function(){

    $(function () {

        localStorage.setItem('pagina', $("#txtPagina").val());
        
    });

    $("input[name='opMonto']").click(function() {
        //alert(this.value);
        if(this.value == "Primaria"){
            $("#txtMonto").val(1935);
        }else if(this.value == "Secundaria"){
            $("#txtMonto").val(2100);
        }

    });
    
    var listaBecados = JSON.parse($("#txtBecado").text());
    var listaNoBecados = JSON.parse($("#txtNoBecado").text());

    validarMonto($('#sltAlumno').val())

    $('#sltAlumno').on('change', function() {
        //alert( this.value );
        idAlumnoT = this.value;
        validarMonto(idAlumnoT)
            
    });

    function validarMonto(idAlumnoT){
        for (var becado of listaBecados){
            if (idAlumnoT == becado.id_Alumno){
                //console.log(becado);
                document.getElementById("txtMonto").hidden = false;

                document.getElementById("radio1").hidden = true;
                document.getElementById("radio2").hidden = true;
            }
                
        }
        
        for (var nbecado of listaNoBecados){
            if (idAlumnoT == nbecado.id_Alumno){
                //console.log(nbecado);
                document.getElementById("txtMonto").hidden = true;

                document.getElementById("radio1").hidden = false;
                document.getElementById("radio2").hidden = false;
            }
                
        }
    }

});

