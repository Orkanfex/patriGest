document.addEventListener('DOMContentLoaded', function () {
    const selectUser = document.querySelector('#environment_users');
    if (selectUser) {
        new TomSelect(selectUser, {
            plugins: ['remove_button'],
            create: false,
            maxOptions: null,
            sortField: { field: "text", direction: "asc" }
        });
    }
});