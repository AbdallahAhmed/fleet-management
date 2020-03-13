@if (session('status'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! session()->get("status") !!}
            </div>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! implode(' <br /> ', $errors->all()) !!}
            </div>
        </div>
    </div>
@endif
