//----------------------//
// CUSTOM ALERT BOX //
const alertBox = document.getElementById("customAlertBox");
const alertMessageContainer = document.getElementById("alertMessage");
const closeImg = document.querySelector(".close");
const modalImg = document.getElementById("brand-logo-popup");
const form = document.getElementById("contact-form");

//----------------------//
// Send E-mail via EmailJS //
window.onload = function() {
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        emailjs.sendForm("default_service", "contact_form", this).then((response) => {
                console.log("Message was sent successfully!");

                modalImg.style.display = "block";
                alertMessageContainer.innerHTML ="Seu formulário foi enviado.<br>Obrigado por escolher a J2K Imóveis!";
                alertBox.style.display = "block";
                form.reset();

            }, (error) => {
                console.log("Falha ao enviar mensagem. Tente novamente.", error);
                alertMessageContainer.innerHTML ="Erro. Tente novamente";
                alertBox.style.display = "block";
            });
    });

    closeImg.addEventListener('click', function () {
        alertBox.style.display = "none";
    });
}