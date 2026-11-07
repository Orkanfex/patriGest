document.addEventListener('DOMContentLoaded', () => {
    const dialog = document.getElementById('delete-confirmation-dialog');
    const dialogMessage = document.getElementById('dialog-message');
    const dialogPhotoContainer = document.getElementById('dialog-photo-container');
    const dialogPhoto = document.getElementById('dialog-photo');
    const btnCancel = document.getElementById('btn-dialog-cancel');
    const btnConfirm = document.getElementById('btn-dialog-confirm');

    let currentFormToSubmit = null;

    if (!dialog) return;

    // Escuta o submit de qualquer formulário com a classe .form-delete-confirm ou .form-delete-patrimony
    document.addEventListener('submit', (e) => {
        const form = e.target.closest('.form-delete-confirm, .form-delete-patrimony');
        if (form) {
            e.preventDefault();
            currentFormToSubmit = form;

            const label = form.getAttribute('data-label') || 'este item';
            const photo = form.getAttribute('data-photo');

            // Atualiza a mensagem dinamicamente
            dialogMessage.innerHTML = `Tem certeza que deseja excluir <strong>${label}</strong>? Esta ação não poderá ser desfeita.`;

            // Trata a pré-visualização da imagem/avatar
            if (photo && photo.trim() !== '') {
                dialogPhoto.src = photo;
                dialogPhotoContainer.classList.remove('d-none');
            } else {
                dialogPhotoContainer.classList.add('d-none');
                dialogPhoto.src = '';
            }

            // Exibe o dialog nativo
            dialog.showModal();
        }
    });

    // Botão Cancelar
    btnCancel?.addEventListener('click', () => {
        dialog.close();
        currentFormToSubmit = null;
    });

    // Botão Confirmar Exclusão
    btnConfirm?.addEventListener('click', () => {
        if (currentFormToSubmit) {
            currentFormToSubmit.submit();
        }
    });

    // Fecha o dialog ao clicar no backdrop (fora da caixa)
    dialog.addEventListener('click', (e) => {
        const rect = dialog.getBoundingClientRect();
        const isInDialog = (rect.top <= e.clientY && e.clientY <= rect.top + rect.height &&
                            rect.left <= e.clientX && e.clientX <= rect.left + rect.width);
        if (!isInDialog) {
            dialog.close();
        }
    });
});