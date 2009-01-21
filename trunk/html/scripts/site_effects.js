/* JS para efectos especiales (deben ser rutinas genéricas) */

function box_error_effects(element_id){
    $(document).ready(
        function(){
            $(element_id).animate({left:"+=10px"}).animate({left:"-5000px"});
        }
    )
    $(element_id).fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
}
function box_info_effects(element_id){
     $(element_id).hide();
     $(element_id).slideDown("slow");
}
