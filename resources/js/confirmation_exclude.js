document.addEventListener('DOMContentLoaded', () => {
    const dialog = document.getElementById('delete-confirmation-dialog');
    const dialogMessage = document.getElementById('dialog-message');
    const dialogPhoto = document.getElementById('dialog-photo');
    const dialogPhotoContainer = document.getElementById('dialog-photo-container');
    const btnConfirm = document.getElementById('btn-dialog-confirm');
    const btnCancel = document.getElementById('btn-dialog-cancel');
    const deleteForms = document.querySelectorAll('.form-delete-patrimony');

    let formToSubmit = null;

    deleteForms.forEach(form => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            formToSubmit = form;

            const label = form.getAttribute('data-label') || 'este patrimônio';
            const photoUrl = form.getAttribute('data-photo');

            dialogMessage.textContent = `Tem certeza que deseja excluir o patrimônio ${label}?`;

            // Trata a exibição da imagem
            if (photoUrl && photoUrl.trim() !== '') {
                dialogPhoto.src = photoUrl;
                dialogPhotoContainer.classList.remove('d-none');
            } else {
                dialogPhoto.src = '';
                dialogPhotoContainer.classList.add('d-none');
            }

            if (dialog) {
                dialog.showModal();
            }
        });
    });

    if (btnConfirm) {
        btnConfirm.addEventListener('click', () => {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });
    }

    if (btnCancel) {
        btnCancel.addEventListener('click', () => {
            if (dialog) {
                dialog.close();
            }
            formToSubmit = null;
        });
    }
});