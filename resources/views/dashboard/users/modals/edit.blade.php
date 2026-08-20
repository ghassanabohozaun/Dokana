<div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog"
    aria-labelledby="updateUserModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="" method="POST" enctype="multipart/form-data"
            id="update_user_form" data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="updateUserModalLabel">
                            {!! __('users.update_user') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    <input type="hidden" id="id_edit" name="id">
                    <input type="hidden" id="delete_photo_edit" name="delete_photo" value="0">

                    @if(isset($stores))
                    <!-- Store Select (for admin) -->
                    <div>
                        <label class="form-label-modern" for="store_id_edit">
                            {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_id" id="store_id_edit" class="form-input-modern select2">
                            <option value="" disabled>{!! __('general.select_from_list') !!}</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-rose-500 error-text store_id_error block mt-1"></span>
                    </div>
                    @endif

                    <!-- Names Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="name_ar_edit">
                                {!! __('users.name_ar') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_ar_edit" name="name[ar]"
                                class="form-input-modern" placeholder="{!! __('users.enter_name_ar') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_ar_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="name_en_edit">
                                {!! __('users.name_en') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_en_edit" name="name[en]"
                                class="form-input-modern" placeholder="{!! __('users.enter_name_en') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_en_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Mobile & Email Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="mobile_edit">
                                {!! __('users.mobile') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="mobile_edit" name="mobile"
                                class="form-input-modern" placeholder="0599000000" autocomplete="off"
                                maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);" dir="ltr">
                            <span class="text-xs text-rose-500 error-text mobile_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="email_edit">
                                {!! __('users.email') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email_edit" name="email"
                                class="form-input-modern" placeholder="user@example.com" autocomplete="off" dir="ltr">
                            <span class="text-xs text-rose-500 error-text email_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Passwords Grid (Optional for Edit) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="password_edit">
                                {!! __('users.password') !!} <span class="text-slate-400 text-[10px]">({!! __('general.optional') ?? 'اختياري للتغيير' !!})</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password_edit" name="password"
                                    class="form-input-modern pe-10" placeholder="••••••••" autocomplete="new-password">
                                <button type="button" class="absolute inset-y-0 end-0 pe-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                    onclick="togglePassword('password_edit', this);">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                            <span class="text-xs text-rose-500 error-text password_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="password_confirm_edit">
                                {!! __('users.password_confirm') !!}
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirm_edit" name="password_confirm"
                                    class="form-input-modern pe-10" placeholder="••••••••" autocomplete="new-password">
                                <button type="button" class="absolute inset-y-0 end-0 pe-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                    onclick="togglePassword('password_confirm_edit', this);">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                            <span class="text-xs text-rose-500 error-text password_confirm_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Role & Status Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="role_id_edit">
                                {!! __('users.role_id') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="role_id" id="role_id_edit" class="form-input-modern select2">
                                <option value="" disabled>{!! __('general.select_from_list') !!}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-xs text-rose-500 error-text role_id_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="status_edit">
                                {!! __('users.status') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" id="status_edit" class="form-input-modern select2">
                                <option value="" disabled>{!! __('general.select_from_list') !!}</option>
                                <option value="1">{!! __('general.enable') !!}</option>
                                <option value="0">{!! __('general.disabled') !!}</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text status_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Photo Upload (Modern Tailwind Dropzone with Preview) -->
                    <div>
                        <label class="form-label-modern">{!! __('users.photo') !!}</label>
                        <div class="relative">
                            <input type="file" name="photo" id="photo_edit" class="sr-only" accept="image/*,.webp,.png,.jpg,.jpeg">
                            
                            <!-- Empty State Dropzone -->
                            <div id="dropzone_empty_user_edit"
                                onclick="document.getElementById('photo_edit').click()"
                                class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl bg-slate-50/60 dark:bg-slate-800/40 hover:bg-indigo-50/20 dark:hover:bg-indigo-950/20 cursor-pointer transition-all duration-200 text-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 mb-1.5 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-base"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                                    {!! __('general.click_or_drag_to_upload') !!}
                                </p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                    PNG, JPG, WEBP ({!! __('general.max_size') !!}: 5MB)
                                </p>
                            </div>

                            <!-- Preview State -->
                            <div id="dropzone_preview_user_edit" class="hidden items-center justify-between p-3 border border-slate-200 dark:border-slate-700 rounded-2xl bg-slate-50/70 dark:bg-slate-800/60">
                                <div class="flex items-center gap-3">
                                    <img id="preview_img_user_edit" src="" alt="Preview" class="h-12 w-12 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-2xs">
                                    <div>
                                        <span id="preview_name_user_edit" class="text-xs font-bold text-slate-800 dark:text-white block truncate max-w-[200px] sm:max-w-xs"></span>
                                        <span id="preview_size_user_edit" class="text-[10px] text-slate-400 dark:text-slate-500"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" onclick="document.getElementById('photo_edit').click()" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40" title="{!! __('general.change') !!}">
                                        <i class="fas fa-sync text-xs"></i>
                                    </button>
                                    <button type="button" id="remove_photo_edit_btn" class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40" title="{!! __('general.delete') !!}">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-rose-500 error-text photo_error block mt-1"></span>
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
    <script type="text/javascript">
        $(document).ready(function() {
            const $photoInputEdit = $('#photo_edit');
            const $dropzoneEmptyEdit = $('#dropzone_empty_user_edit');
            const $dropzonePreviewEdit = $('#dropzone_preview_user_edit');
            const $previewImgEdit = $('#preview_img_user_edit');
            const $previewNameEdit = $('#preview_name_user_edit');
            const $previewSizeEdit = $('#preview_size_user_edit');
            const $deletePhotoInput = $('#delete_photo_edit');

            // Photo change listener
            $photoInputEdit.on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $previewImgEdit.attr('src', event.target.result);
                        $previewNameEdit.text(file.name);
                        $previewSizeEdit.text((file.size / 1024).toFixed(1) + ' KB');
                        $dropzoneEmptyEdit.addClass('hidden');
                        $dropzonePreviewEdit.removeClass('hidden').addClass('flex');
                        $deletePhotoInput.val('0');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Remove photo button
            $('#remove_photo_edit_btn').on('click', function(e) {
                e.stopPropagation();
                $photoInputEdit.val('');
                $previewImgEdit.attr('src', '');
                $dropzonePreviewEdit.removeClass('flex').addClass('hidden');
                $dropzoneEmptyEdit.removeClass('hidden');
                $deletePhotoInput.val('1');
            });

            // Show edit modal and populate data dynamically via event delegation (0ms lag)
            $(document).on('click', '.editUserBtn', function(e) {
                e.preventDefault();
                
                let $btn = $(this);
                let user_id = $btn.data('id');
                let name_ar = $btn.data('name_ar');
                let name_en = $btn.data('name_en');
                let email = $btn.data('email');
                let mobile = $btn.data('mobile');
                let role_id = $btn.data('role_id');
                let status = $btn.data('status');
                let store_id = $btn.data('store_id');
                let photo_url = $btn.data('photo_url');

                // Populate form fields
                $('#id_edit').val(user_id);
                $('#name_ar_edit').val(name_ar);
                $('#name_en_edit').val(name_en);
                $('#email_edit').val(email);
                $('#mobile_edit').val(mobile);
                $('#password_edit').val('');
                $('#password_confirm_edit').val('');
                $deletePhotoInput.val('0');
                $photoInputEdit.val('');

                // Populate Status Select2
                if ($('#status_edit').length) {
                    $('#status_edit').val(status).trigger('change.select2');
                }

                // Populate Role Select2
                if ($('#role_id_edit').length) {
                    $('#role_id_edit').val(role_id).trigger('change.select2');
                }

                // Populate Store Select2
                if ($('#store_id_edit').length) {
                    if (store_id) {
                        $('#store_id_edit').val(store_id).trigger('change.select2');
                    } else {
                        $('#store_id_edit').val(null).trigger('change.select2');
                    }
                }

                // Handle Photo Preview State
                if (photo_url && photo_url.trim() !== '' && !photo_url.includes('default') && !photo_url.endsWith('/')) {
                    $previewImgEdit.attr('src', photo_url);
                    $previewNameEdit.text("{!! __('users.photo') !!}");
                    $previewSizeEdit.text('');
                    $dropzoneEmptyEdit.addClass('hidden');
                    $dropzonePreviewEdit.removeClass('hidden').addClass('flex');
                } else {
                    $previewImgEdit.attr('src', '');
                    $dropzonePreviewEdit.removeClass('flex').addClass('hidden');
                    $dropzoneEmptyEdit.removeClass('hidden');
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.users.update', ':id') !!}".replace(':id', user_id);
                $('#update_user_form').attr('action', url);
                
                $('#update_user_form').find('.error-text').text('');
                $('#update_user_form').find('.form-input-modern').removeClass('border-rose-500');
                $('#updateUserModal').modal('show');
            });
        });
    </script>
@endpush
