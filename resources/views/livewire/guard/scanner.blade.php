<div class="min-h-screen p-4" wire:ignore>
    <div class="max-w-2xl mx-auto">

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
    const reader = document.getElementById('reader');
    const feedback = document.getElementById('feedback');

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
        showReader();
        renderIdle();

        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: 300 },
            onScan
        );
    }

    function showReader() {
        reader.classList.remove('hidden');
    }

    function hideReader() {
        reader.classList.add('hidden');
    }

    // Wraps an action so it only ever fires once per screen — a tap and
    // the auto-advance timeout (where one exists) can both be live at the
    // same time, and only the first should actually do anything.
    function once(fn) {
        let called = false;
        return (...args) => {
            if (called) return;
            called = true;
            fn(...args);
        };
    }

    async function onScan(decodedText) {
        try {
            await html5QrCode.pause(true);
        } catch (e) {
            // already paused/stopped, ignore
        }

        hideReader();

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
        feedback.onclick = null;

        try {
            html5QrCode.resume();
        } catch (e) {
            startScanner();
        }
        showReader();
        renderIdle();
    }

    function renderIdle() {
        feedback.innerHTML = `
            <div class="text-center text-sm opacity-60 py-6">
                Point the camera at an employee's QR code
            </div>
        `;
    }

    function renderRejected(message) {
        const advance = once(resumeScanning);
        feedback.onclick = advance;

        feedback.innerHTML = `
            <div class="alert alert-error mb-4">
                <span class="text-lg">${escapeHtml(message)}</span>
            </div>
            <div class="text-center text-sm opacity-60 py-2">
                Tap anywhere to scan again
            </div>
        `;
    }

    function renderEmployee(data) {
        const devices = (data.devices && data.devices.length)
            ? data.devices.map(d => `
                <div class="rounded-xl border-2 border-primary/30 bg-base-200 p-4">
                    <div class="text-sm opacity-70 mb-2">
                        ${escapeHtml(d.type)} &middot; ${escapeHtml(d.brand)} ${escapeHtml(d.model)}
                    </div>
                    <div class="space-y-2">
                        <div>
                            <div class="text-xs uppercase opacity-60">Serial</div>
                            <div class="font-mono text-2xl font-bold tracking-wide break-all">
                                ${escapeHtml(d.serial || '-')}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs uppercase opacity-60">IMEI</div>
                            <div class="font-mono text-2xl font-bold tracking-wide break-all">
                                ${escapeHtml(d.imei || '-')}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('')
            : `<div class="text-center p-4 opacity-60">No devices assigned</div>`;

        const initial = escapeHtml((data.name || 'U').charAt(0).toUpperCase());

        const avatar = data.photo_url
            ? `<img src="${escapeHtml(data.photo_url)}" class="w-24 h-24 rounded-full object-cover" alt="">`
            : `<div class="avatar placeholder">
                   <div class="bg-primary text-primary-content rounded-full w-24">
                       <span class="text-3xl">${initial}</span>
                   </div>
               </div>`;

        const advance = once(() => confirmExit(data.token));
        feedback.onclick = advance;

        feedback.innerHTML = `
            <div class="border border-base-300 rounded-xl p-5 mb-4">
                <div class="flex items-center gap-4 mb-5">
                    ${avatar}
                    <div>
                        <div class="text-3xl font-bold">${escapeHtml(data.name)}</div>
                        <div class="text-lg opacity-70">${escapeHtml(data.employee_id || '-')}</div>
                        <div class="text-lg opacity-70">${escapeHtml(data.company || '-')} &middot; ${escapeHtml(data.designation || '-')}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    ${devices}
                </div>
            </div>

            <div class="alert alert-success justify-center text-lg font-semibold cursor-pointer">
                Tap anywhere to confirm exit &amp; continue
            </div>
        `;
    }

    async function confirmExit(token) {
        feedback.onclick = null;
        feedback.innerHTML = `
            <div class="text-center text-lg font-semibold py-6 opacity-60">
                Logging...
            </div>
        `;

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

        const advance = once(resumeScanning);
        feedback.onclick = advance;

        feedback.innerHTML = `
            <div class="alert ${ok ? 'alert-success' : 'alert-error'} text-center text-xl font-semibold py-6 cursor-pointer">
                ${ok ? '&#10003; ' + escapeHtml(message) : escapeHtml(message)}
            </div>
            <div class="text-center text-sm opacity-60 py-2">
                Tap anywhere to continue
            </div>
        `;

        // Tapping advances immediately; this is just the fallback so the
        // kiosk never gets stuck if nobody taps.
        setTimeout(advance, 1200);
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }
})();
</script>
