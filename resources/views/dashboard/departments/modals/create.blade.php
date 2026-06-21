<div class="modal modal-pop" id="createDepartmentModal" tabindex="-1" role="dialog"
    aria-labelledby="createDepartmentModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="{!! route('dashboard.departments.store') !!}" method="POST" enctype="multipart/form-data"
            id='create_department_form' novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="createDepartmentModalLabel">
                        <i class="fas fa-plus-circle text-primary mr-2 icon-size-18"></i> {!! __('departments.create_new_department') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    @if(isset($stores))
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_id_dept_create">{!! __('stores.store') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id='store_id_dept_create' name="store_id">
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

                    <div class="row">
                        <!-- Name Arabic -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_ar_create">{!! __('departments.name_ar') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="name_ar_create" name="name[ar]"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    placeholder="{!! __('departments.enter_name_ar') !!}">
                                <span class="text-danger error-text name_ar_error"></span>
                            </div>
                        </div>

                        <!-- Name English -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_en_create">{!! __('departments.name_en') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="name_en_create" name="name[en]"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    placeholder="{!! __('departments.enter_name_en') !!}">
                                <span class="text-danger error-text name_en_error"></span>
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
    <script>
        $(document).ready(function() {
            if ($('#store_id_dept_create').length) {
                $('#store_id_dept_create').select2({
                    dropdownParent: $('#createDepartmentModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
        });
    </script>
@endpush




