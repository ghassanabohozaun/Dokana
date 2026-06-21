<form action="{!! url()->current() !!}" method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <input type="text" name="keyword" class="form-control" autocomplete="off"
                placeholder="{!! __('general.search') !!}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary" id='search'>{!! __('general.search') !!}</button>
        </div>
    </div>
</form>


