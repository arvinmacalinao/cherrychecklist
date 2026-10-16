<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Exit Scanner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-base-200">
    {{ $slot }}

@livewireScripts

<script>
// Kiosk tablets sit untouched between scans — keep the screen from locking
// or sleeping on inactivity. Wake Lock needs a secure context (HTTPS, or
// the chrome://flags insecure-origin override used for LAN testing), and
// releases itself whenever the page loses visibility, so it's re-acquired
// on every visibilitychange too.
(function () {
    if (!('wakeLock' in navigator)) {
        console.warn('Wake Lock API not supported — screen may sleep on this device/browser.');
        return;
    }

    let wakeLock = null;

    async function requestWakeLock() {
        try {
            wakeLock = await navigator.wakeLock.request('screen');
        } catch (err) {
            console.warn('Wake Lock request failed:', err.message);
        }
    }

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            requestWakeLock();
        }
    });

    requestWakeLock();
})();
</script>
</body>
</html>
