(function () {
    // 1. Idle Timer Logic (Global & Multi-tab Sync)
    const STORAGE_KEY = "lock_screen_last_activity";
    const config = window.LockScreenConfig || {
        idle_limit: 300,
        lock_route: "#",
    };
    const idleLimit = config.idle_limit;

    function resetTimer() {
        localStorage.setItem(STORAGE_KEY, Date.now().toString());
    }

    // Initialize last activity if not set
    if (!localStorage.getItem(STORAGE_KEY)) {
        resetTimer();
    }

    // Only start monitoring if we are NOT already on the lock screen
    if (!window.location.href.includes("lock-screen")) {
        setInterval(() => {
            const lastActivity = parseInt(localStorage.getItem(STORAGE_KEY) || Date.now());
            const secondsSinceLastActivity = Math.floor((Date.now() - lastActivity) / 1000);

            if (secondsSinceLastActivity >= idleLimit) {
                window.location.href = config.lock_route;
            }
        }, 1000);

        // Listen for activity on current window
        window.addEventListener('load', resetTimer);
        window.addEventListener('mousemove', resetTimer);
        window.addEventListener('mousedown', resetTimer);
        window.addEventListener('touchstart', resetTimer);
        window.addEventListener('click', resetTimer);
        window.addEventListener('keypress', resetTimer);
        window.addEventListener('scroll', resetTimer);
    }

    // 2. Unlock Handler (Lock Screen Only - Pure Vanilla JS)
    document.addEventListener("DOMContentLoaded", function () {
        const lockForm = document.getElementById("lock-form");
        if (lockForm) {
            const submitBtn = document.getElementById("unlock-btn");
            const data = window.LockScreenData || {};

            // Keep-Alive Ping every 15 minutes (900000 ms) to prevent session expiry
            if (data.routes && data.routes.keep_alive) {
                setInterval(function() {
                    fetch(data.routes.keep_alive, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json"
                        }
                    }).catch(err => console.log('Keep-alive ping failed', err));
                }, 900000);
            }

            lockForm.addEventListener("submit", function () {
                // Show loading state on standard submit
                if (submitBtn) {
                    submitBtn.disabled = true;
                    const unlockingText = (data.labels && data.labels.unlocking) ? data.labels.unlocking : "جاري الفتح...";
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1.5"></i> ' + unlockingText;
                }
            });
        }
    });
})();
