document.addEventListener("DOMContentLoaded", function () {
    const startButtons = document.querySelectorAll("[data-start-scanner]");
    const stopButtons = document.querySelectorAll("[data-stop-scanner]");

    let currentTarget = null;
    let isRunning = false;
    let lastDetectedCode = null;
    let sameCodeCount = 0;

    function stopScanner(resultBox = null) {
        if (isRunning && window.Quagga) {
        Quagga.stop();
        isRunning = false;
        }

        if (currentTarget) {
        currentTarget.innerHTML = "";
        }

        lastDetectedCode = null;
        sameCodeCount = 0;

        if (resultBox) {
            resultBox.textContent = "Scanner arrêté.";
        }
    }

    startButtons.forEach(button => {
        button.addEventListener("click", function () {
            const inputId = this.dataset.targetInput;
            const videoId = this.dataset.videoId;
            const resultId = this.dataset.resultId;

            const input = document.getElementById(inputId);
            const target = document.getElementById(videoId);
            const resultBox = document.getElementById(resultId);

            if (!input || !target || !resultBox) {
                console.error("Éléments scanner manquants.");
                return;
            }

            stopScanner(resultBox);

            currentTarget = target;
            resultBox.textContent = "Activation de la caméra...";

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: target,
                    constraints: {
                        facingMode: "environment",
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                },
                locator: {
                    patchSize: "medium",
                    halfSample: true
                },
                numOfWorkers: 2,
                frequency: 5,
                decoder: {
                    readers: [
                        "ean_reader",
                        "ean_8_reader",
                        "upc_reader",
                        "upc_e_reader",
                        "code_128_reader"
                    ]
                },
                locate: true
            }, function (err) {
                if (err) {
                    console.error("Erreur Quagga.init :", err);
                    resultBox.textContent = "Impossible d’accéder à la caméra.";
                    return;
                }

                Quagga.start();
                isRunning = true;
                resultBox.textContent = "Caméra activée. Présentez un code-barres...";
            });

            Quagga.offDetected();
            Quagga.onDetected(function (data) {
                if (!data || !data.codeResult || !data.codeResult.code) {
                    return;
                }

                const code = String(data.codeResult.code).trim();

    // accepter seulement des formats plausibles
                const isValidFormat =
                    /^\d{8}$/.test(code) ||
                    /^\d{12,13}$/.test(code) ||
                    /^[A-Za-z0-9\-]{6,20}$/.test(code);

                if (!isValidFormat) {
                    return;
                }

    // pour EAN-13 / UPC / EAN-8 on exige uniquement des chiffres
    // et on demande 2 lectures identiques avant validation
                if (code === lastDetectedCode) {
                    sameCodeCount++;
                } else {
                    lastDetectedCode = code;
                    sameCodeCount = 1;
                }

                resultBox.textContent = "Lecture en cours : " + code + " (" + sameCodeCount + "/2)";

                if (sameCodeCount < 2) {
                    return;
                }

                input.value = code;
                resultBox.textContent = "Code détecté : " + code;

                stopScanner(resultBox);

                const form = input.closest("form");
                if (form) {
                    form.submit();
                }
            });
        });
    });

    stopButtons.forEach(button => {
        button.addEventListener("click", function () {
            const resultId = this.dataset.resultId;
            const resultBox = document.getElementById(resultId);
            stopScanner(resultBox);
        });
    });

    window.addEventListener("beforeunload", function () {
        stopScanner();
    });
});
