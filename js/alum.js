$(function(){

    localStorage.setItem('pagina', $("#txtPagina").val());
    
    $('#sltEtapa').on('change', function() {
       
        if(this.value == "Primaria"){
            document.getElementById("gruposPrimaria").hidden = false;
            document.getElementById("gruposSecundaria").hidden = true;
        }else if (this.value == "Secundaria"){
            document.getElementById("gruposPrimaria").hidden = true;
            document.getElementById("gruposSecundaria").hidden = false;
        }
    });

});

