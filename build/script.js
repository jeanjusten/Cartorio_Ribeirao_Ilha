//#region Page Loader - Fade out when Fully Loaded
window.addEventListener("load", () => {
    const loader = document.querySelector(".loader")
    loader.classList.add("loader--hidden");

    document.body.removeChild(document.body.firstChild);
})

window.addEventListener("load", () => {
    const loader = document.querySelector(".loader");
    loader.classList.add("loader--hidden");
});
//#endregion

//#region Closes the dropdown menu after clicking.
document.addEventListener("DOMContentLoaded", function() {
    let navLinks = document.querySelectorAll(".nav-link");
    let navbarCollapse = document.querySelector(".navbar-collapse");

    navLinks.forEach(function(link) {
        link.addEventListener("click", function() {
            // Delay for closing navbar dropdown menu
            setTimeout(function() {
                if (navbarCollapse.classList.contains("show")) {
                    new bootstrap.Collapse(navbarCollapse).toggle();
                }
            }, 1000); // 1 second
        });
    });
});
//#endregion

//#region JQuery 
$(document).ready(function() {
    // Phone mask
    $("#tel").mask("(00)00000-0000", {
        placeholder: "(00)00000-0000"
    });

    // Send form with AJAX
    $("#send-form-btn").click(function(event) {
        event.preventDefault(); 

        // Show Spinner loading
        $("#loading").show();

        var alertBox = $("#customAlertBox");
        var alertMessageContainer = $("#alertMessage");
        var closeImg = $(".close");
        var modalImg = $("#brand-logo-popup");
        var form = $("#contact-form");

        $.ajax({
            method: "POST",
            url: "https://formsubmit.co/ajax/cartorioribeirao@cartorioribeirao.com.br",
            dataType: "json",
            accepts: "application/json",
            data: form.serialize(),  
            success: function(response) {
                console.log("Mensagem enviada com sucesso!");
                $("#loading").hide();

                // Show modal
                modalImg.show(); 
                alertMessageContainer.html("Seu formulário foi enviado.<br>Obrigado por contatar o Cartório Ribeirão da Ilha!");
                alertBox.show();  
                // Reset form
                form[0].reset();
            },
            error: function(err) {
                console.log("Falha ao enviar a mensagem. Tente novamente.", err);
                $("#loading").hide();
                alertMessageContainer.html("Erro. Tente novamente");
                alertBox.show();  
            }
        });
        // Close modal
        closeImg.click(function() {
            alertBox.hide(); 
        });
    });
});
//#endregion