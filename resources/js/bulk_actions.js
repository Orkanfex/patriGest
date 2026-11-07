document.addEventListener('DOMContentLoaded', () => {
    const btnToggle = document.getElementById('btn-toggle-move-mode');
    const btnConfirm = document.getElementById('btn-open-move-modal');
    const selectAll = document.getElementById('select-all-patrimonies');
    const checkboxes = document.querySelectorAll('.patrimony-checkbox');
    const selectedCountSpan = document.getElementById('selected-count');

    // Alterna a exibição das checkboxes
    btnToggle?.addEventListener('click', () => {
        const isHidden = document.querySelector('.th-move-checkbox').classList.contains('d-none');

        document.querySelectorAll('.th-move-checkbox, .td-move-checkbox').forEach(el => {
            el.classList.toggle('d-none');
        });

        if (isHidden) {
            btnToggle.textContent = 'Cancelar Seleção';
            btnToggle.classList.replace('btn-outline-primary', 'btn-secondary');
            btnConfirm.classList.remove('d-none');
        } else {
            btnToggle.innerHTML = '<i class="bi bi-arrow-left-right me-1"></i> Mover Patrimônios';
            btnToggle.classList.replace('btn-secondary', 'btn-outline-primary');
            btnConfirm.classList.add('d-none');
            // Desmarca tudo ao cancelar
            if (selectAll) selectAll.checked = false;
            checkboxes.forEach(cb => cb.checked = false);
            updateCount();
        }
    });

    // Atualiza contador e estado do botão de confirmação
    function updateCount() {
        const checkedCount = document.querySelectorAll('.patrimony-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        btnConfirm.disabled = checkedCount === 0;
    }

    selectAll?.addEventListener('change', (e) => {
        checkboxes.forEach(cb => cb.checked = e.target.checked);
        updateCount();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateCount));
});