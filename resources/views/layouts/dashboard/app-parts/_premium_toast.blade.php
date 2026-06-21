<!-- Premium Toast Notification Assets -->
<link rel="stylesheet" href="{{ asset('assets/dashbaord/css/premium-toast.css') }}?v=1.1">
<script src="{{ asset('assets/dashbaord/js/premium-toast.js') }}?v=1.1"></script>

<!-- Notification Bootstrapper -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Check for standard Laravel session flashed messages
        @if(session('success'))
            window.PremiumToast.success(@json(session('success')));
        @endif

        @if(session('error'))
            window.PremiumToast.error(@json(session('error')));
        @endif

        @if(session('warning'))
            window.PremiumToast.warning(@json(session('warning')));
        @endif

        @if(session('info'))
            window.PremiumToast.info(@json(session('info')));
        @endif

        // 2. Check for Validation Errors
        @if($errors->any())
            @if(count($errors->all()) === 1)
                window.PremiumToast.error(@json($errors->first()));
            @else
                window.PremiumToast.error(@json(__('general.validation_error_message')));
            @endif
        @endif


    });

    // 3. Listen to Livewire dispatched events (Supports Livewire V2 and V3)
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (data) => {
            // Livewire 3 returns an array of objects [ { message: '', type: '' } ]
            // Livewire 2 returns the object directly or an array depending on dispatch
            let payload = Array.isArray(data) ? data[0] : data;
            if(payload && payload.message) {
                const type = payload.type || 'info';
                window.PremiumToast.show(payload.message, type);
            }
        });
    });
</script>

