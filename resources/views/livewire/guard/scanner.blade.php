<div class="py-6">
    <div class="max-w-4xl mx-auto">

        <div class="bg-white p-6 rounded shadow">

            <h1 class="text-xl font-bold mb-4 text-center">
                QR Scanner
            </h1>

            <!-- Camera -->
            <div id="reader" class="mb-4"></div>

            <!-- Result -->
            <div id="result" class="mt-4"></div>

            <!-- Scan Again -->
            <button id="scan-again-btn"
                class="hidden mt-4 w-full bg-blue-600 text-white py-2 rounded">
                Scan Again
            </button>

        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
let html5QrCode;

document.addEventListener("livewire:navigated", () => {
    startScanner();
});

document.addEventListener("DOMContentLoaded", () => {
    startScanner();
});

function startScanner() {

    if (html5QrCode) {
        html5QrCode.stop().catch(() => {});
    }

    html5QrCode = new Html5Qrcode("reader");

    document.getElementById('result').innerHTML = '';
    document.getElementById('scan-again-btn').classList.add('hidden');

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 350
        },
        async (decodedText) => {

            await html5QrCode.stop();

            try {
                const response = await fetch(decodedText);
                const data = await response.json();

                document.getElementById('result').innerHTML = renderResult(data);

            } catch (e) {
                document.getElementById('result').innerHTML =
                    '<p class="text-red-600">Invalid or expired QR</p>';
            }

            document.getElementById('scan-again-btn').classList.remove('hidden');
        }
    );
}

function renderResult(data) {

    let deviceRows = '';

    if (data.devices && data.devices.length > 0) {
        data.devices.forEach(device => {
            deviceRows += `
                <tr class="border-t">
                    <td class="px-3 py-2">${device.type}</td>
                    <td class="px-3 py-2">${device.brand}</td>
                    <td class="px-3 py-2">${device.model}</td>
                    <td class="px-3 py-2">${device.serial ?? '-'}</td>
                    <td class="px-3 py-2">${device.imei ?? '-'}</td>
                </tr>
            `;
        });
    } else {
        deviceRows = `
            <tr>
                <td colspan="5" class="text-center py-3 text-gray-500">
                    No devices found
                </td>
            </tr>
        `;
    }

    return `
        <div class="border rounded p-4">

            <h2 class="font-bold text-lg mb-2">${data.name}</h2>

            <div class="mb-3 text-sm">
                <p><strong>Employee ID:</strong> ${data.employee_id ?? '-'}</p>
                <p><strong>Department:</strong> ${data.department ?? '-'}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Type</th>
                            <th class="px-3 py-2 text-left">Brand</th>
                            <th class="px-3 py-2 text-left">Model</th>
                            <th class="px-3 py-2 text-left">Serial</th>
                            <th class="px-3 py-2 text-left">IMEI</th>
                        </tr>
                    </thead>

                    <tbody>
                        ${deviceRows}
                    </tbody>

                </table>
            </div>

        </div>
    `;
}

// Scan again button
document.getElementById('scan-again-btn').addEventListener('click', () => {
    window.location.reload();
});
</script>
