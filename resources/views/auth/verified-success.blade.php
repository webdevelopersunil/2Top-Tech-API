@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Thankyou</div>

                <div class="card-body">
                    {{ __('Your account has been verified now.') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
