<div class="modal modal-pop" id="createUserModal" role="dialog" aria-labelledby="createUserModalLabel" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="{!! route('dashboard.users.store') !!}" method="POST" enctype="multipart/form-data"
            id='create_user_form' novalidate data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table" data-table-id="#table_data">
            @csrf
            <div class="modal-content border-0">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="createUserModalLabel">
                        <i class="fas fa-user-plus text-primary mr-2 icon-size-18"></i>
                        {!! __('users.create_new_user') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    <!-- First Row: Store (Full Width if Admin) -->
                    @if ($stores)
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_id_create">{!! __('stores.store') !!} <span
                                        class="text-danger">*</span></label>
                                <select class="form-control premium-input shadow-none select2"
                                    id='store_id_create' name="store_id">
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
                                <label class="premium-label" for="name_ar_create">{!! __('users.name_ar') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control premium-input shadow-none"
                                    id="name_ar_create" name="name[ar]" placeholder="{!! __('users.enter_name_ar') !!}"
                                    autocomplete="off">
                                <span class="text-danger error-text name_ar_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_en_create">{!! __('users.name_en') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control premium-input shadow-none"
                                    id="name_en_create" name="name[en]" placeholder="{!! __('users.enter_name_en') !!}"
                                    autocomplete="off">
                                <span class="text-danger error-text name_en_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="mobile_create">{!! __('users.mobile') !!} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control premium-input shadow-none text-left"
                                    id="mobile_create" name="mobile" placeholder="{!! __('users.enter_mobile') !!}"
                                    dir="ltr" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                                <span class="text-danger error-text mobile_error"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Third Row: Email, Password, Password Confirm (3 Columns) -->
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="email_create">{!! __('users.email') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control premium-input shadow-none text-left"
                                    id="email_create" name="email" placeholder="{!! __('users.enter_email') !!}"
                                    dir="ltr" autocomplete="off">
                                <span class="text-danger error-text email_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="password_create">{!! __('users.password') !!} <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fas fa-eye pointer text-primary premium-icon-opposite"
                                        style="{{ Lang() == 'ar' ? 'left: 1.15rem !important; right: auto !important;' : 'right: 1.15rem !important; left: auto !important;' }} position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; font-size: 1.35rem; cursor: pointer;"
                                        onclick="togglePassword('password_create', this);"></i>
                                    <input type="password" class="form-control premium-input shadow-none"
                                        id="password_create" name="password" placeholder="{!! __('users.enter_password') !!}"
                                        autocomplete="new-password">
                                </div>
                                <span class="text-danger error-text password_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="password_confirm_create">{!! __('users.password_confirm') !!} <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fas fa-eye pointer text-primary premium-icon-opposite"
                                        style="{{ Lang() == 'ar' ? 'left: 1.15rem !important; right: auto !important;' : 'right: 1.15rem !important; left: auto !important;' }} position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; font-size: 1.35rem; cursor: pointer;"
                                        onclick="togglePassword('password_confirm_create', this);"></i>
                                    <input type="password" class="form-control premium-input shadow-none"
                                        id="password_confirm_create" name="password_confirm"
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
                                <label class="premium-label" for="role_id_create">{!! __('users.role_id') !!} <span
                                        class="text-danger">*</span></label>
                                <select class="form-control premium-input shadow-none select2" id='role_id_create'
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
                                    <input type="file" name="photo" id="photo_create" class="form-control"
                                        accept="image/*">
                                </div>
                                <span class="text-danger error-text photo_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer">
                    <button type="submit" id="saveBtn" class="btn btn-premium-save font-weight-bold">
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
            // Initialize Generic Select2 for Store & Role
            if ($('#store_id_create').length) {
                $('#store_id_create').select2({
                    dropdownParent: $('#createUserModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
            if ($('#role_id_create').length) {
                $('#role_id_create').select2({
                    dropdownParent: $('#createUserModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            // Initialize FileInput using Global Generic Pattern
            window.PremiumFileInput.init("#photo_create");
        });
    </script>
@endpush


