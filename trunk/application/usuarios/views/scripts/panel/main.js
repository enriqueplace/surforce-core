function cambiar_clave_validar(){
    
    if( $('#pass1').val() != $('#pass2').val()){
        alert('¡Las claves no pueden ser distintas!');

        $('#pass1').val('');
        $('#pass2').val('');

        $('#pass1').focus();

        return false;
    }
    $('#cambiar-clave-form').submit();

}