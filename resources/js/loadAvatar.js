import Croppie from 'croppie';

document.addEventListener('DOMContentLoaded', () => {
    let croppieInstance = null;
    const input = document.getElementById('avatar-input');
    const container = document.getElementById('croppie-container');
    const preview = document.getElementById('avatar-preview');
    const form = document.getElementById('avatar-form');
    const croppedInput = document.getElementById('cropped_image');

    // 1. Ao selecionar a imagem no input
    input.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                // Esconde a foto estática e mostra o container interativo do Croppie
                preview.classList.add('d-none');
                container.classList.remove('d-none');

                // Limpa instância anterior se o usuário escolher outra foto em seguida
                if (croppieInstance) {
                    croppieInstance.destroy();
                }

                // Cria o visualizador interativo do Croppie
                croppieInstance = new Croppie(container, {
                    viewport: { width: 200, height: 200, type: 'square' },
                    boundary: { width: 280, height: 280 },
                    showZoomer: true,          // Mostra a barra de zoom
                    enableOrientation: true    // Permite ajustar orientação
                });

                // Carrega a imagem dentro da área do Croppie
                croppieInstance.bind({
                    url: e.target.result
                });
            };

            reader.readAsDataURL(this.files[0]);
        }
    });

    // 2. Ao submeter o formulário
    form.addEventListener('submit', function (e) {
        if (croppieInstance) {
            e.preventDefault(); // Pausa o envio padrão

            // Extrai o recorte final em Base64 na dimensão de 200x200
            croppieInstance.result({
                type: 'blob',
                size: { width: 200, height: 200 },
                format: 'jpeg',
                quality: 0.9
            }).then(function (blob) {
                // Insere a imagem cortada no campo invisível e envia
                const formData = new FormData();
                formData.append('_method', 'PUT'); // Simula método PUT no Laravel
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('avatar', blob, 'avatar.jpg'); // Envia como arquivo nativo

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload(); // Recarrega para mostrar a nova foto
                    } else {
                        alert('Ocorreu um erro ao salvar a imagem.');
                    }
                });
            });
        }
    });
});