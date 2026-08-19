<div class="modal fade" id="addStoreModal" tabindex="-1" role="dialog" aria-labelledby="addStoreModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.stores.store') !!}" method="POST" enctype="multipart/form-data"
            id="create_store_form" novalidate data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table" data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-store"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="addStoreModalLabel">
                            {!! __('stores.create_new_store') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    
                    <!-- Row 1: Arabic Name, English Name, Plan -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label-modern" for="name_ar_create">
                                {!! __('stores.name_ar') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_ar_create" name="name[ar]" class="form-input-modern"
                                placeholder="{!! __('stores.enter_name_ar') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_ar_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="name_en_create">
                                {!! __('stores.name_en') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_en_create" name="name[en]" class="form-input-modern"
                                placeholder="{!! __('stores.enter_name_en') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_en_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="subscription_plan_create">
                                {!! __('stores.subscription_plan') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="subscription_plan" id="subscription_plan_create" class="form-input-modern select2">
                                <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                                <option value="Basic">{!! __('stores.plan_basic') !!}</option>
                                <option value="Premium">{!! __('stores.plan_premium') !!}</option>
                                <option value="Enterprise">{!! __('stores.plan_enterprise') !!}</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text subscription_plan_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Row 2: Status, Email, Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label-modern" for="status_create">
                                {!! __('stores.status') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" id="status_create" class="form-input-modern select2">
                                <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                                <option value="active">{!! __('general.active') !!}</option>
                                <option value="inactive">{!! __('general.inactive') !!}</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text status_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="email_create">{!! __('stores.email') !!}</label>
                            <input type="email" id="email_create" name="email" class="form-input-modern"
                                placeholder="{!! __('stores.enter_email') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text email_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="phone_create">{!! __('stores.phone') !!}</label>
                            <input type="text" id="phone_create" name="phone" class="form-input-modern"
                                maxlength="10" inputmode="numeric" placeholder="{!! __('stores.enter_phone') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text phone_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Row 3: Address -->
                    <div>
                        <label class="form-label-modern" for="address_create">{!! __('stores.address') !!}</label>
                        <input type="text" id="address_create" name="address" class="form-input-modern"
                            placeholder="{!! __('stores.enter_address') !!}" autocomplete="off">
                        <span class="text-xs text-rose-500 error-text address_error block mt-1"></span>
                    </div>

                    <!-- Row 4: Logo Upload (Modern Tailwind Dropzone) -->
                    <div>
                        <label class="form-label-modern">{!! __('stores.logo') !!}</label>
                        <div class="relative">
                            <input type="file" name="logo" id="logo_create" class="sr-only" accept="image/*,.webp,.png,.jpg,.jpeg,.svg,.ico,.avif">
                            
                            <!-- Empty State Dropzone -->
                            <div id="dropzone_empty_create"
                                onclick="document.getElementById('logo_create').click()"
                                class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl bg-slate-50/60 dark:bg-slate-800/40 hover:bg-indigo-50/20 dark:hover:bg-indigo-950/20 cursor-pointer transition-all duration-200 text-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 mb-1.5 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-base"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                                    {!! __('general.click_or_drag_to_upload') !!}
                                </p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                    WEBP, PNG, JPG, SVG ({!! __('general.max_size') !!}: 5MB)
                                </p>
                            </div>

                            <!-- Preview State -->
                            <div id="dropzone_preview_create" class="hidden items-center justify-between p-3 border border-slate-200 dark:border-slate-700 rounded-2xl bg-slate-50/70 dark:bg-slate-800/60">
                                <div class="flex items-center gap-3">
                                    <img id="preview_img_create" src="" alt="Preview" class="h-12 w-12 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-2xs">
                                    <div>
                                        <span id="preview_name_create" class="text-xs font-bold text-slate-800 dark:text-white block truncate max-w-[200px] sm:max-w-xs"></span>
                                        <span id="preview_size_create" class="text-[10px] text-slate-400 dark:text-slate-500"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" onclick="document.getElementById('logo_create').click()" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40" title="{!! __('general.change') !!}">
                                        <i class="fas fa-sync text-xs"></i>
                                    </button>
                                    <button type="button" id="remove_logo_create_btn" class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40" title="{!! __('general.delete') !!}">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-rose-500 error-text logo_error block mt-1"></span>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-2.5 px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <button type="submit" class="btn-primary-gradient text-xs">
                        <i class="fas fa-save text-xs"></i>
                        <i class="fas fa-spinner fa-spin spinner_loading text-xs hidden d-none"></i>
                        <span>{!! __('general.save') !!}</span>
                    </button>
                    <button type="button" class="btn-secondary-modern text-xs" data-dismiss="modal">
                        {!! __('general.cancel') !!}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Restrict phone field input on create store form
            $('#phone_create').on('keypress', function(e) {
                if (e.which < 48 || e.which > 57) {
                    e.preventDefault();
                }
            }).on('input', function() {
                let val = $(this).val().replace(/\D/g, '');
                if (val.length > 10) val = val.substring(0, 10);
                $(this).val(val);
            });

            // Modern Logo Upload Preview for Create
            const logoInput = document.getElementById('logo_create');
            const dropzoneEmpty = document.getElementById('dropzone_empty_create');
            const dropzonePreview = document.getElementById('dropzone_preview_create');
            const previewImg = document.getElementById('preview_img_create');
            const previewName = document.getElementById('preview_name_create');
            const previewSize = document.getElementById('preview_size_create');
            const removeBtn = document.getElementById('remove_logo_create_btn');

            function handleFile(file) {
                if (!file || !file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewName.textContent = file.name;
                    previewSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
                    dropzoneEmpty.classList.add('hidden');
                    dropzonePreview.classList.remove('hidden');
                    dropzonePreview.classList.add('flex');
                };
                reader.readAsDataURL(file);
            }

            if (logoInput) {
                logoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        handleFile(this.files[0]);
                    }
                });
            }

            if (removeBtn) {
                removeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    logoInput.value = '';
                    previewImg.src = '';
                    dropzonePreview.classList.remove('flex');
                    dropzonePreview.classList.add('hidden');
                    dropzoneEmpty.classList.remove('hidden');
                });
            }

            // Drag and drop events
            if (dropzoneEmpty) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropzoneEmpty.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzoneEmpty.classList.add('border-indigo-500', 'bg-indigo-50/30');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropzoneEmpty.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzoneEmpty.classList.remove('border-indigo-500', 'bg-indigo-50/30');
                    }, false);
                });

                dropzoneEmpty.addEventListener('drop', (e) => {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    if (files && files[0]) {
                        logoInput.files = files;
                        handleFile(files[0]);
                    }
                }, false);
            }

            // Reset upload component on modal hide
            $('#addStoreModal').on('hidden.bs.modal', function () {
                if (logoInput) logoInput.value = '';
                if (previewImg) previewImg.src = '';
                if (dropzonePreview) {
                    dropzonePreview.classList.remove('flex');
                    dropzonePreview.classList.add('hidden');
                }
                if (dropzoneEmpty) dropzoneEmpty.classList.remove('hidden');
            });
        });
    </script>
@endpush
