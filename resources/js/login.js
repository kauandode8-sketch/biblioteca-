$(document).ready(function() {
    $('#entrar').click(function() {
        alert("Funfei!");
    });

    if ($("#email").val() == "" || $("#senha").val() == "") {
        swal.fire({
            title: "Erro!",
            text: "Preencha todos os campos!",
            icon: "error"
        });
    }
    var letras = $(senha).val().length;
    console.log(letras);
    if (letras < 8) {
        swal.fire({
            title: "Erro!",
            text: "A senha deve ter no mínimo 6 caracteres!",
            icon: "error"
        });
        return;
    
    }
    var email = $("#email").val().length;
    console.log(email);
    if (email < 6) {
        swal.fire({
            title: "Erro!",
            text: "O email deve ter no mínimo 6 caracteres!",
            icon: "error"
        });
    }
});
