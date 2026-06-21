document.addEventListener('DOMContentLoaded', function() {
    // Request Desktop Notification Permission
    if ("Notification" in window && Notification.permission !== "denied" && Notification.permission !== "granted") {
        Notification.requestPermission();
    }
});

document.addEventListener('new-notification-received', function(event) {
    // Livewire v3 passes data in event.detail[0]
    let data = event.detail[0] || event.detail;
    
    let title = data.title;
    let message = data.message;
    let level = data.level || 'info';

    // 1. Play Sound (Simple Web Audio API Beep)
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(800, audioCtx.currentTime); // 800Hz
        gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime); // Volume
        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        oscillator.start();
        oscillator.stop(audioCtx.currentTime + 0.15); // Duration
    } catch (e) {}

    // 2. Show Toast (Using Custom Premium Toast)
    let toastType = level === 'danger' ? 'error' : level;
    if (typeof PremiumToast !== 'undefined') {
        PremiumToast.show(`<strong>${title}</strong><br>${message}`, toastType);
    } else {
        alert(title + "\n" + message);
    }

    // 3. HTML5 Desktop Notification
    if ("Notification" in window && Notification.permission === "granted") {
        new Notification(title, {
            body: message,
            icon: "/favicon.ico"
        });
    }

    // 4. Tab Title Flashing
    let originalTitle = document.title;
    let flashInterval = setInterval(() => {
        document.title = document.title === originalTitle ? "🔔 " + title : originalTitle;
    }, 1000);

    // Stop flashing when user focuses the tab
    window.addEventListener('focus', function onFocus() {
        clearInterval(flashInterval);
        document.title = originalTitle;
        window.removeEventListener('focus', onFocus);
    });
});
