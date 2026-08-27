document.addEventListener("DOMContentLoaded", function () {
    const showModal = document.getElementById('showPatrimonyModal');
    
    if (showModal) {
        showModal.addEventListener('show.bs.modal', function (event) {
            // Botão que disparou o modal
            const button = event.relatedTarget;

            // Extrai os dados dos atributos data-*
            const code = button.getAttribute('data-code');
            const state = button.getAttribute('data-state');
            const description = button.getAttribute('data-description');
            const image = button.getAttribute('data-image');

            // Preenche os campos de texto no modal
            document.getElementById('modalPatrimonyCode').textContent = code;
            document.getElementById('modalPatrimonyState').textContent = state;
            document.getElementById('modalPatrimonyDescription').textContent = description;

            // Trata a imagem
            const imgElement = document.getElementById('modalPatrimonyImage');
            const noImgElement = document.getElementById('modalPatrimonyNoImage');

            if (image) {
                imgElement.src = image;
                imgElement.classList.remove('d-none');
                noImgElement.classList.add('d-none');
            } else {
                imgElement.classList.add('d-none');
                noImgElement.classList.remove('d-none');
            }
        });
    }
});