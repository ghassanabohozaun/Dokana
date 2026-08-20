<!-- Universal Alpine.js & Tailwind Image Lightbox / Viewer -->
<div x-data="imageLightbox()" 
     x-cloak 
     @preview-image.window="open($event.detail)"
     @keydown.escape.window="close()"
     @keydown.plus.window="zoomIn()"
     @keydown.minus.window="zoomOut()"
     class="relative z-50">

    <!-- Backdrop with Blur Effect -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity">
    </div>

    <!-- Modal Container -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6 md:p-10"
         @click.self="close()">

        <div class="relative max-w-4xl w-full bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden flex flex-col max-h-[90vh]">
            
            <!-- Top Toolbar Header -->
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between bg-slate-50/70 dark:bg-slate-950/50">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                        <i class="fas fa-image"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" x-text="title || '{{ __('general.image_preview') }}'"></h4>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('general.high_res_viewer') }}</p>
                    </div>
                </div>

                <!-- Action Controls Group -->
                <div class="flex items-center gap-1.5">
                    <!-- Zoom Out -->
                    <button type="button" @click="zoomOut()" title="{{ __('general.zoom_out') }}"
                            class="h-8 w-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas fa-search-minus text-xs"></i>
                    </button>
                    <!-- Zoom In -->
                    <button type="button" @click="zoomIn()" title="{{ __('general.zoom_in') }}"
                            class="h-8 w-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas fa-search-plus text-xs"></i>
                    </button>
                    <!-- Rotate -->
                    <button type="button" @click="rotate()" title="{{ __('general.rotate') }}"
                            class="h-8 w-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas fa-redo-alt text-xs"></i>
                    </button>
                    <!-- Reset Zoom -->
                    <button type="button" @click="reset()" title="{{ __('general.reset_zoom') }}"
                            class="h-8 w-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas fa-compress-arrows-alt text-xs"></i>
                    </button>
                    <!-- Open in New Tab -->
                    <a :href="imgUrl" target="_blank" title="{{ __('general.open_in_new_tab') }}"
                       class="h-8 w-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </a>
                    
                    <div class="h-4 w-[1px] bg-slate-200 dark:bg-slate-700 mx-1"></div>

                    <!-- Close Button -->
                    <button type="button" @click="close()" aria-label="Close"
                            class="h-8 w-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Image Viewport Area -->
            <div class="relative flex-1 overflow-hidden p-6 flex items-center justify-center bg-slate-100/70 dark:bg-slate-950/80 min-h-[360px] max-h-[70vh]">
                <!-- Checkerboard Pattern for transparent PNGs -->
                <div class="absolute inset-0 opacity-15 dark:opacity-5 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] [background-size:16px_16px]"></div>

                <!-- The Preview Image -->
                <div class="relative flex items-center justify-center transition-transform duration-200 ease-out max-w-full max-h-full"
                     :style="`transform: scale(${scale}) rotate(${rotation}deg); cursor: ${scale > 1 ? 'grab' : 'default'}`">
                    <img :src="imgUrl" :alt="title" 
                         class="max-h-[60vh] max-w-full rounded-2xl object-contain drop-shadow-xl select-none"
                         draggable="false">
                </div>
            </div>

            <!-- Bottom Footer Bar -->
            <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-950/30">
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-info-circle text-indigo-500"></i>
                    <span>{{ __('general.use_keys_to_zoom') }}</span>
                </span>
                <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400" x-text="`${Math.round(scale * 100)}%`"></span>
            </div>

        </div>
    </div>
</div>

<script>
    function imageLightbox() {
        return {
            isOpen: false,
            imgUrl: '',
            title: '',
            scale: 1,
            rotation: 0,
            open(data) {
                this.imgUrl = typeof data === 'string' ? data : (data.url || '');
                this.title = typeof data === 'object' ? (data.title || '') : '';
                this.scale = 1;
                this.rotation = 0;
                this.isOpen = true;
                document.body.classList.add('overflow-hidden');
            },
            close() {
                this.isOpen = false;
                document.body.classList.remove('overflow-hidden');
            },
            zoomIn() {
                if (this.scale < 3) this.scale = parseFloat((this.scale + 0.25).toFixed(2));
            },
            zoomOut() {
                if (this.scale > 0.5) this.scale = parseFloat((this.scale - 0.25).toFixed(2));
            },
            rotate() {
                this.rotation = (this.rotation + 90) % 360;
            },
            reset() {
                this.scale = 1;
                this.rotation = 0;
            }
        }
    }

    // Global helper for opening preview from anywhere
    window.previewImage = function(url, title) {
        window.dispatchEvent(new CustomEvent('preview-image', {
            detail: { url: url, title: title || '' }
        }));
    };
</script>
