$(document).ready(function () {
    $('#collapse').click(function () {
        $('body').toggleClass('collapse');
    });
    
    $('.modal-trigger').leanModal();
    
    $('select').material_select();

    $(".button-collapse").sideNav();
    
    // Inicialização plugin formulário
    Materialize.updateTextFields();

    
    // Inicialização do modal materialize
    $('.modal-trigger').leanModal({
        overlay: true, close_esc: true
    });
    
     $('.closeModal').click(function(){
        $('#newAnimal').closeModal(); 
     });
});
  