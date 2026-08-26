import { Html5Qrcode } from "html5-qrcode";

let html5QrCode = null;

document.addEventListener("DOMContentLoaded", function () {
    const qrModal = document.getElementById("qrModal");
    const inputCode = document.getElementById("patrimony_code");

    if (!qrModal) return;

    qrModal.addEventListener("shown.bs.modal", function () {
        setTimeout(async () => {
            const readerDiv = document.getElementById("qr-reader");
            readerDiv.innerHTML = "";

            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("qr-reader");
            }

            const config = { 
                fps: 15, 
                qrbox: { width: 220, height: 220 },
                aspectRatio: 1.0
            };

            try {
                await html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    (decodedText) => {
                        // 1. Preenche o valor no input
                        if (inputCode) {
                            inputCode.value = decodedText;
                            inputCode.dispatchEvent(new Event('input', { bubbles: true }));
                        }

                        // 2. Fecha o modal imediatamente
                        fecharModal(qrModal);
                    },
                    () => {}
                );
            } catch (err) {
                alert("Erro ao ativar câmera: " + err);
            }
        }, 300);
    });

    // O fechamento do modal automaticamente aciona esta parada da câmera
    qrModal.addEventListener("hidden.bs.modal", async function () {
        if (html5QrCode) {
            try {
                if (html5QrCode.isScanning) {
                    await html5QrCode.stop();
                }
                html5QrCode.clear();
            } catch (e) {}
            html5QrCode = null;
        }
    });

    // Função universal para garantir o fechamento do modal
    function fecharModal(modalElement) {
        // Bootstrap 5 (cria a instância se não existir)
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
            modalInstance.hide();
        } 
        // Fallback para jQuery / Bootstrap 4
        else if (typeof $ !== 'undefined') {
            $(modalElement).modal('hide');
        } 
        // Fallback genérico: simula clique no botão "X" de fechar
        else {
            const closeBtn = modalElement.querySelector('[data-bs-dismiss="modal"], [data-dismiss="modal"]');
            if (closeBtn) closeBtn.click();
        }
    }
});