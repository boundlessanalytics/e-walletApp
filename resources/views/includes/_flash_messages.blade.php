@if ($errors->any())
<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page_title-content">
                    <div class="alert alert-danger alert-block" id="message-alert">
                        <a class="close" data-dismiss="alert">×</a>
                        <strong>Errors:</strong>
                        @foreach($errors->all() as $error)
                            <ul>
                                <li>{{ $error }}</li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(session()->has('message'))
<div class="alert alert-info alert-block" id="message-alert">
    <a class="close" data-dismiss="alert">×</a>
    <strong>{{ session()->has('message') }}</strong>
</div>
{{ session()->forget('message') }}
@endif

@if (session()->has('warning'))
<div class="alert alert-warning alert-block" id="message-alert">
    <a class="close" data-dismiss="alert">×</a>
    <strong>{{ session()->has('warning') }}</strong>
</div>
{{ session()->forget('warning') }}
@endif

{{-- @if ($message = Session::get('success'))
<div class="alert alert-success alert-block" id="message-alert">
    <a class="close" data-dismiss="alert">×</a>
    <strong>{{ $message }}</strong>
</div>
{{ Session::forget('success') }}
@endif --}}


@if ($message = Session::get('success'))
<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page_title-content">
                    <div class="alert alert-success alert-block" id="message-alert">
                        <a class="close" data-dismiss="alert">×</a>
                        <strong>{{ $message }}</strong>
                    </div>
                    {{ Session::forget('success') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif


@if ($message = Session::get('error'))
<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page_title-content">
                    <div class="alert alert-danger alert-block" id="message-alert">
                        <a class="close" data-dismiss="alert">×</a>
                        <strong>{{ $message }}</strong>
                    </div>
                    {{ Session::forget('error') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif
