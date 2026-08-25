<div class="min-h-screen p-4" wire:ignore>
    <div class="max-w-xl mx-auto">

        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">

                <h1 class="text-2xl font-bold text-center mb-4">
                    Exit Scanner
                </h1>

                <div id="reader" class="rounded-xl overflow-hidden mb-4"></div>

                <div id="feedback"></div>

            </div>
        </div>

    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
(function () {
    let html5QrCode;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function boot() {
        startScanner();
    }

    document.addEventListener('DOMContentLoaded', boot);
    document.addEventListener('livewire:navigated', boot);

    function startScanner() {
        if (html5QrCode) {
            html5QrCode.stop().catch(() => {});
        }

        html5QrCode = new Html5Qrcode('reader');
        renderIdle();

        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: 300 },
            onScan
        );
    }

    async function onScan(decodedText) {
        try {
            await html5QrCode.pause(true);
        } catch (e) {
            // already paused/stopped, ignore
        }

        try {
            const response = await fetch(decodedText, {
                headers: { 'Accept': 'application/json' },
            });
            const data = await response.json();

            if (!data.ok) {
                renderRejected(data.message || 'QR code not recognized.');
                return;
            }

            renderEmployee(data);
        } catch (e) {
            renderRejected('Could not read this QR code. Try again.');
        }
    }

    function resumeScanning() {
        try {
            html5QrCode.resume();
        } catch (e) {
            startScanner();
        }
        renderIdle();
    }

    function renderIdle() {
        document.getElementById('feedback').innerHTML = `
            <div class="text-center text-sm opacity-60 py-6">
                Point the camera at an employee's QR code
            </div>
        `;
    }

    function renderRejected(message) {
        document.getElementById('feedback').innerHTML = `
            <div class="alert alert-error mb-4">
                <span>${escapeHtml(message)}</span>
            </div>
            <button id="retry-btn" class="btn btn-block btn-lg">
                Scan Again
            </button>
        `;
        document.getElementById('retry-btn').addEventListener('click', resumeScanning);
    }

    function renderEmployee(data) {
        const devices = (data.devices && data.devices.length)
            ? data.devices.map(d => `
                <tr>
                    <td class="p-2">${escapeHtml(d.type)}</td>
                    <td class="p-2">${escapeHtml(d.brand)}</td>
                    <td class="p-2">${escapeHtml(d.model)}</td>
                    <td class="p-2 font-mono text-xs">${escapeHtml(d.serial || '-')}</td>
                </tr>
            `).join('')
            : `<tr><td colspan="4" class="text-center p-4 opacity-60">No devices assigned</td></tr>`;

        const initial = escapeHtml((data.name || 'U').charAt(0).toUpperCase());

        const avatar = data.photo_url
            ? `<img src="${escapeHtml(data.photo_url)}" class="w-16 h-16 rounded-full object-cover" alt="">`
            : `<div class="avatar placeholder">
                   <div class="bg-primary text-primary-content rounded-full w-16">
                       <span class="text-xl">${initial}</span>
                   </div>
               </div>`;

        document.getElementById('feedback').innerHTML = `
            <div class="border border-base-300 rounded-xl p-4 mb-4">
                <div class="flex items-center gap-4 mb-4">
                    ${avatar}
                    <div>
                        <div class="text-lg font-bold">${escapeHtml(data.name)}</div>
                        <div class="text-sm opacity-70">${escapeHtml(data.employee_id || '-')}</div>
                        <div class="text-sm opacity-70">${escapeHtml(data.company || '-')} &middot; ${escapeHtml(data.designation || '-')}</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra table-sm">
                        <thead>
                            <tr><th>Type</th><th>Brand</th><th>Model</th><th>Serial</th></tr>
                        </thead>
                        <tbody>${devices}</tbody>
                    </table>
                </div>
            </div>

            <button id="confirm-btn" class="btn btn-success btn-block btn-lg">
                Confirm Exit
            </button>
        `;

        document.getElementById('confirm-btn').addEventListener('click', () => confirmExit(data.token));
    }

    async function confirmExit(token) {
        const btn = document.getElementById('confirm-btn');
        btn.disabled = true;
        btn.textContent = 'Logging...';

        let ok = false;
        let message = 'Could not log exit.';

        try {
            const response = await fetch(`/qr/${token}/confirm`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });
            const data = await response.json();
            ok = !!data.ok;
            message = data.message || message;
        } catch (e) {
            message = 'Network error. Try again.';
        }

        document.getElementById('feedback').innerHTML = `
            <div class="alert ${ok ? 'alert-success' : 'alert-error'} text-center text-lg font-semibold py-6">
                ${ok ? '&#10003; ' + escapeHtml(message) : escapeHtml(message)}
            </div>
        `;

        setTimeout(resumeScanning, 1200);
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }
})();
</script>
