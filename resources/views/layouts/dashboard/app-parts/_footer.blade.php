<footer class="footer footer-static footer-light navbar-border" style=" margin-top: -9px;">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <span class="float-md-left d-block d-md-inline-block">{!! __('dashboard.copyright') !!} &copy; {!! date('Y') !!}
            <a href="javascript:void(0)" class="text-bold-800 grey darken-2">
                {!! auth()->user()->store->name ?? setting()->site_name !!}
            </a>,
            {!! __('dashboard.all_rights_reserved') !!}. </span>
    </p>
</footer>


