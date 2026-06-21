window.Toast = {
    init: function() {
        if (!document.getElementById('toast-container')) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
        }
    },
    
    show: function(title, message = '', type = 'success', duration = 4000) {
        this.init();
        
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast-item toast-${type}`;
        
        let icon = '';
        if (type === 'success') icon = '<i class="ph-fill ph-check-circle"></i>';
        else if (type === 'error') icon = '<i class="ph-fill ph-x-circle"></i>';
        else icon = '<i class="ph-fill ph-info"></i>';
        
        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                ${message ? `<div class="toast-message">${message}</div>` : ''}
            </div>
            <button class="toast-close"><i class="ph-bold ph-x"></i></button>
        `;
        
        container.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 10);
        
        const closeBtn = toast.querySelector('.toast-close');
        
        const removeToast = () => {
            toast.classList.remove('show');
            toast.classList.add('hide');
            setTimeout(() => {
                if(toast.parentElement) toast.parentElement.removeChild(toast);
            }, 400);
        };
        
        closeBtn.addEventListener('click', removeToast);
        
        if (duration > 0) {
            setTimeout(removeToast, duration);
        }
    }
};

// Listen to Livewire events automatically
document.addEventListener('livewire:init', () => {
    Livewire.on('toast', (event) => {
        const data = Array.isArray(event) ? event[0] : event;
        Toast.show(data.title, data.message || '', data.type || 'success');
    });
});
