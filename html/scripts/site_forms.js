function enviar_submit_generic(contenedorInput , formName){
    var elementoSeleccionado = false;

    if (!$('#'+contenedorInput+' input').is(':checked')){
        alert("Debes seleccionar un elemento");
        return false;
    }else{
        elementoSeleccionado = true;
    }
    if(elementoSeleccionado){
        $('#'+formName+'').submit();
    }
}

/* TODO: recorrer los elementos actuales y verificar que no existan ya */
function existen_duplicados(elemento, valor){
    var flag = false;

    $(elemento.toString()).each(function(){
        
        if($(this).val() == valor){
            flag = true;
        }

    })
    return flag;
}
function existen_duplicados_text(elemento, cadena){
    var flag = false;

    $(elemento.toString()).each(function(){
        
        if($(this).text() == cadena){
            flag = true;
        }

    })
    return flag;
}
function caracteres_minimos_input(input_id , cant){
    return $("#"+input_id+"").val().length >= cant;
}
