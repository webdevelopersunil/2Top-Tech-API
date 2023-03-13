<x-master-layout>
    <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? __('messages.list') }}</h5>
                                <a href="{{ route('provider.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @if($auth_user->can('provider list'))
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        {{ Form::model($user_management,['method' => 'POST','route'=>'user_management.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'provider'] ) }}

                            {{ Form::hidden('id') }}
                            {{ Form::hidden('user_type','provider') }}

                            <div class="row">

                                <div class="form-group col-md-4">
                                    {{ Form::label('display_name',__('Full Name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('display_name',old('display_name'),['placeholder' => __('Full Name'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('email',__('messages.email').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('email',old('email'),['placeholder' => __('messages.email'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('password',__('Password').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('password',old('password'),['placeholder' => __('Password'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>


                                <div class="form-group col-md-4">
                                    {{ Form::label('role',__('Role').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}

                                    <select class = 'form-control' name="role" >
                                        <option value="7" class = 'form-control' >Staff</option>
                                        <option value="1" class = 'form-control' >Admin</option>
                                    </select>
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                            </div>

                            {{ Form::submit( __('messages.save'), ['class'=>'btn btn-md btn-primary float-right']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('bottom_script')

    @endsection
</x-master-layout>
