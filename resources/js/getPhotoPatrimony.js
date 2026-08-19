document.addEventListener("DOMContentLoaded", function () {
    const cameraInput = document.getElementById('cameraInput');
    const fileInput = document.getElementById('fileInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewContainer = document.getElementById('previewContainer');
    const btnRemoveImage = document.getElementById('btnRemoveImage');

    // Função para exibir a prévia e sincronizar o arquivo
    function handleImageSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Se a foto veio da câmera, sincroniza para o input principal enviado ao Laravel
            if (input === cameraInput) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            }

            // Exibe a prévia
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    // Função para limpar a foto selecionada
    function removeImage() {
        cameraInput.value = '';
        fileInput.value = '';
        imagePreview.src = '#';
        previewContainer.classList.add('d-none');
    }

    // Eventos de alteração dos inputs de imagem
    if (cameraInput) cameraInput.addEventListener('change', () => handleImageSelect(cameraInput));
    if (fileInput) fileInput.addEventListener('change', () => handleImageSelect(fileInput));

    // Evento de clique para o botão de remover
    if (btnRemoveImage) btnRemoveImage.addEventListener('click', removeImage);
});