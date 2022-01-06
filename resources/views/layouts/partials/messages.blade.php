@if(Session::get('success'))
    <div class="alert alert-success" role="alert">
        <i class="fas fa-check"></i> {{session('success')}}
    </div>
@endif

@if(Session::get('error'))
    <div class="alert alert-danger" role="alert">
        <i class="fas fa-exclamation"></i> {{session('error')}}
    </div>
@endif
