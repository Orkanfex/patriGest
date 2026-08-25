let html5QrCode = null;

document.addEventListener("DOMContentLoaded", function () {
    const qrModal = document.getElementById("qrModal");
    const inputCode = document.getElementById("patrimony_code");

    if (!qrModal) return;

    qrModal.addEventListener("shown.bs.modal", async function () {
        try {
            // 1. Força o Safari a pedir permissão listando as câmeras
            const devices = await Html5Qrcode.getCameras();

            if (devices && devices.length > 0) {
                // Tenta pegar a câmera traseira, se não achar pega a primeira (frontal)
                const backCamera = devices.find(d => 
                    d.label.toLowerCase().includes('back') || 
                    d.label.toLowerCase().includes('traseira') ||
                    d.label.toLowerCase().includes('environment')
                );
                const cameraId = backCamera ? backCamera.id : devices[0].id;

                html5QrCode = new Html5Qrcode("qr-reader");

                await html5QrCode.start(
                    cameraId,
                    { fps: 10, qrbox: { width: 200, height: 200 } },
                    (decodedText) => {
                        inputCode.value = decodedText;
                        html5QrCode.stop().then(() => {
                            html5QrCode = null;
                            const modalInstance = bootstrap.Modal.getInstance(qrModal);
                            modalInstance.hide();
                        });
                    },
                    () => {} // Ignora erros de busca de frame
                );
            } else {
                alert("Nenhuma câmera encontrada no iPad.");
            }
        } catch (err) {
            // Captura recusa de permissão do usuário ou bloqueios do Safari
            alert("Acesso negado ou erro na câmera: " + err);
        }
    });

    qrModal.addEventListener("hidden.bs.modal", async function () {
        if (html5QrCode && html5QrCode.isScanning) {
            await html5QrCode.stop();
            html5QrCode = null;
        }
    });
});