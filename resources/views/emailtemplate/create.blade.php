<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? __('messages.list') }}</h5>
                        </div>
                    </div>
                </div>
            
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($emailtemplatedata,['method' => 'POST','route'=>'emailtemplates.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'emailtemplate'] ) }}
                    <!-- {!! Form::open(['route' => 'emailtemplates.store']) !!} -->
                         {{ Form::hidden('id') }}
                          @php 
                                if($emailtemplatedata && $emailtemplatedata->name){
                                    $old_name = $emailtemplatedata->name;
                                }else{
                                    $old_name ='';
                                }
                            @endphp
                        <div class="card-body">
                            <div class="row">
                                <!-- Name Field -->
                                <div class="form-group col-sm-12">
                                    {!! Form::label('name', 'Name:') !!}
                                    {!! Form::text('name',  null,  ['class' => 'form-control', ($old_name)?'readonly': '', 'required']) !!}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <!-- Email Subject Field -->
                                <div class="form-group col-sm-12">
                                    {!! Form::label('email_subject', 'Email Subject:') !!}
                                    {!! Form::text('email_subject', null, ['class' => 'form-control', 'required']) !!}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <!-- Email Body Field -->
                                <div class="form-group col-sm-12">
                                    {!! Form::label('email_body', 'Email Body:') !!}
                                    {!! Form::text('email_body', null, ['class' => 'form-control, tinymce-email_body', 'required']) !!}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{{ route('emailtemplates.index') }}" class="btn btn-default">Cancel</a>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@section('bottom_script')
    <script>
        (function($) {
            $(document).ready(function(){
                tinymceEditor('.tinymce-email_body',' ',function (ed) {

                }, 450)
            
            });

        })(jQuery);
    </script>
@endsection
</x-master-layout>

