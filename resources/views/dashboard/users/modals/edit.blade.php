<div class="modal modal-pop" id="updateUserModal" role="dialog" aria-labelledby="updateUserModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="" method="POST" enctype="multipart/form-data" id='update_user_form'
            novalidate data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            @method('PUT')
            <div class="modal-content border-0">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center"
                        id="updateUserModalLabel">
                        <i class="fas fa-edit text-primary mr-2 icon-size-18"></i> {!! __('users.update_user') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    <input type="hidden" id="id_edit" name="id">

                    <!-- First Row: Store (Full Width if Admin) -->
                    @if ($stores)
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <div class="premium-form-group">
                                    <label class="premium-label" for="store_id_edit">{!! __('stores.store') !!} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control premium-input shadow-none select2"
                                        id='store_id_edit' name="store_id">
                                        <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text store_id_error"></span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Second Row: Names and Mobile (3 Columns) -->
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_ar_edit">{!! __('users.name_ar') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control premium-input shadow-none"
                                    id="name_ar_edit" name="name[ar]" placeholder="{!! __('users.enter_name_ar') !!}"
                                    autocomplete="off">
                                <span class="text-danger error-text name_ar_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_en_edit">{!! __('users.name_en') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control premium-input shadow-none"
                                    id="name_en_edit" name="name[en]" placeholder="{!! __('users.enter_name_en') !!}"
                                    autocomplete="off">
                                <span class="text-danger error-text name_en_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="mobile_edit">{!! __('users.mobile') !!} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control premium-input shadow-none text-left"
                                    id="mobile_edit" name="mobile" placeholder="{!! __('users.enter_mobile') !!}"
                                    dir="ltr" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                                <span class="text-danger error-text mobile_error"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Third Row: Email, Password, Password Confirm (3 Columns) -->
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="email_edit">{!! __('users.email') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control premium-input shadow-none text-left"
                                    id="email_edit" name="email" placeholder="{!! __('users.enter_email') !!}"
                                    dir="ltr" autocomplete="off">
                                <span class="text-danger error-text email_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="password_edit">{!! __('users.password') !!}</label>
                                <div class="position-relative">
                                    <i class="fas fa-eye pointer text-primary premium-icon-opposite"
                                        style="{{ Lang() == 'ar' ? 'left: 1.15rem !important; right: auto !important;' : 'right: 1.15rem !important; left: auto !important;' }} position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; font-size: 1.35rem; cursor: pointer;"
                                        onclick="togglePassword('password_edit', this);"></i>
                                    <input type="password" class="form-control premium-input shadow-none"
                                        id="password_edit" name="password" placeholder="{!! __('users.enter_password') !!}"
                                        autocomplete="new-password">
                                </div>
                                <span class="text-danger error-text password_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="password_confirm_edit">{!! __('users.password_confirm') !!}</label>
                                <div class="position-relative">
                                    <i class="fas fa-eye pointer text-primary premium-icon-opposite"
                                        style="{{ Lang() == 'ar' ? 'left: 1.15rem !important; right: auto !important;' : 'right: 1.15rem !important; left: auto !important;' }} position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; font-size: 1.35rem; cursor: pointer;"
                                        onclick="togglePassword('password_confirm_edit', this);"></i>
                                    <input type="password" class="form-control premium-input shadow-none"
                                        id="password_confirm_edit" name="password_confirm"
                                        placeholder="{!! __('users.enter_password_confirm') !!}" autocomplete="new-password">
                                </div>
                                <span class="text-danger error-text password_confirm_error"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Fourth Row: Role (Full Width) -->
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="role_id_edit">{!! __('users.role_id') !!} <span
                                        class="text-danger">*</span></label>
                                <select class="form-control premium-input shadow-none select2" id='role_id_edit'
                                    name="role_id">
                                    <option value="" selected="">{!! __('general.select_from_list') !!}</option>
                                    @foreach ($roles as $role)
                                        <option value="{!! $role->id !!}">{!! $role->name !!}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text role_id_error"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Fourth Row: Photo (Full Width) -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="premium-form-group mb-0">
                                <label class="font-weight-bold text-dark">{!! __('users.photo') !!}</label>
                                <div class="premium-photo-container">
                                    <input type="hidden" name="delete_photo" id="delete_photo_edit" value="0">
                                    <input type="file" name="photo" id="photo_edit" class="form-control"
                                        accept="image/*">
                                </div>
                                <span class="text-danger error-text photo_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer">
                    <button type="submit" id="saveBtnEdit" class="btn btn-premium-save font-weight-bold">
                        <i class="fas fa-save mr-2"></i>
                        <i class="fas fa-spinner fa-spin d-none spinner_loading mr-2"></i>
                        {{ __('general.save') }}
                    </button>

                    <button type="button" class="btn btn-premium-secondary font-weight-bold"
                        data-dismiss="modal">
                        <i class="fas fa-times-circle mr-2"></i> {{ __('general.cancel') }}
                    </button>
                </div>
                <!--end::modal footer-->

            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">


        $(document).ready(function() {
            let lang = "{!! Lang() !!}";

            // Show edit modal and populate data dynamically
            $('body').on('click', '.edit_user_button', function() {
                let user_id = $(this).attr('user-id');
                let user_name_ar = $(this).attr('user-name-ar');
                let user_name_en = $(this).attr('user-name-en');
                let user_email = $(this).attr('user-email');
                let user_mobile = $(this).attr('user-mobile');
                let user_role_id = $(this).attr('user-role-id');
                let user_photo_url = $(this).attr('user-photo-url');
                let user_photo = $(this).attr('user-photo');

                let user_store_id = $(this).attr('user-store-id');
                let user_store_name = $(this).attr('user-store-name');

                // Populate fields
                $('#id_edit').val(user_id);
                $('#delete_photo_edit').val(0);
                $('#name_ar_edit').val(user_name_ar);
                $('#name_en_edit').val(user_name_en);
                $('#email_edit').val(user_email);
                $('#mobile_edit').val(user_mobile);
                $('#role_id_edit').val(user_role_id).trigger('change');

                // Pre-populate Select2 for Store without Extra AJAX Call
                if ($('#store_id_edit').length) {
                    if (user_store_id) {
                        $('#store_id_edit').val(user_store_id).trigger('change');
                    } else {
                        $('#store_id_edit').val(null).trigger('change');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.users.update', 'id') !!}".replace('id', user_id);
                $('#update_user_form').attr('action', url);

                // Re-initialize FileInput using Global Generic Pattern
                let photoOptions = {};
                if (user_photo && user_photo_url && !user_photo_url.includes('default')) {
                    photoOptions = {
                        initialPreview: [user_photo_url],
                        initialPreviewAsData: true
                    };
                }
                window.PremiumFileInput.init("#photo_edit", photoOptions);
            });

            // Initialize Generic Select2 for Store & Role in Edit Modal
            if ($('#store_id_edit').length) {
                $('#store_id_edit').select2({
                    dropdownParent: $('#updateUserModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
            if ($('#role_id_edit').length) {
                $('#role_id_edit').select2({
                    dropdownParent: $('#updateUserModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            // Handle Header Update after successful profile edit
            $('#update_user_form').on('ajax-form-success', function(e, response) {
                let user_id = $('#id_edit').val();
                let auth_id = "{{ user()->id }}";

                if (user_id == auth_id && response.status) {
                    let userName = response.data.name[lang] || response.data.name['en'] || response.data
                        .name['ar'];
                    $('.user-name-text').text(userName);
                    $('.dropdown-header-premium .user-name').text(userName);
                    if (response.photo_url) {
                        let imgHtml = '<img src="' + response.photo_url + '?t=' + new Date().getTime() +
                            '" alt="avatar" class="avatar-img-premium shadow-sm">';
                        $('.avatar-wrapper-premium').html(imgHtml);
                    }
                }
            });
        });
    </script>
@endpush
