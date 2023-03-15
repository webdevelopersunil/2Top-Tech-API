<x-master-layout>
    <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? __('messages.list') }}</h5>
                                <a href="{{ route('user_management.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @if($auth_user->can('provider list'))
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        {{ Form::model($user_management,['method' => 'PATCH','route'=>['user_management.update', $user_management->id], 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'provider'] ) }}

                            {{ Form::hidden('id') }}

                            <div class="row">

                                <div class="form-group col-md-6">
                                    {{ Form::label('display_name',__('Full Name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('display_name',old('display_name'),['placeholder' => __('Full Name'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('email',__('messages.email').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('email',old('email'),['placeholder' => __('messages.email'),'class' =>'form-control','required','readonly']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('role',__('Role').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}

                                    <select class = 'form-control' disabled="true"  name="role" >
                                        @if ($user_management->UserRole->roleDetail->id == 7)
                                            <option class = 'form-control' >Staff</option>
                                        @elseif ($user_management->UserRole->roleDetail->id == 1)
                                            <option class = 'form-control' >Admin</option>
                                        @endif
                                    </select>
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('Phone',__('Contact Number').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::number('contact_number',old('contact_number'),['placeholder' => __('Contact Number'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('status',__('Status').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    <select class = 'form-control' name="status" >
                                        <option value="0" @if ($user_management->status == 0) selected @endif  class = 'form-control' >Un-Active</option>
                                        <option value="1" @if ($user_management->status == 1) selected @endif class = 'form-control' >Active</option>
                                    </select>
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('address',__('messages.address'), ['class' => 'form-control-label']) }}
                                    {{ Form::textarea('address', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.address') ]) }}
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
