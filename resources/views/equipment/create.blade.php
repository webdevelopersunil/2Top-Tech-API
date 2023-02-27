<x-master-layout>
    <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? __('messages.equipment') }}</h5>
                                <a href="{{ route('service.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($equipmentdata,['method' => 'POST','route'=>'equipment.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'equipment'] ) }}
                            {{ Form::hidden('id') }}
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.equipment_id').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('equipment_number',old('equipment_number'),['placeholder' => __('messages.equipment_id'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ Form::label('order',__('messages.make').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    {{ Form::number('make',old('make'), [ 'min' => 1, 'step' => 'any' , 'placeholder' => __('messages.make'),'class' =>'form-control', 'required' ]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('name',old('name'),['placeholder' => __('messages.name'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                                                
                               
                                @php 
                                    $equipment_categories = [];
                                @endphp
                                @foreach($equipmentCategories as $category)
                                    @if($category->type == 'equipment_categories')
                                        @php 
                                            $equipment_categories[$category->id] = $category->value; 
                                        @endphp
                                    @endif
                                @endforeach
                                <div class="form-group col-md-6">
                                    {{ Form::label('status',__('messages.equipment_category').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    {{ Form::select('category_id',
                                    $equipment_categories,old('category_id'),[ 'class' =>'form-control select2js','required']) }}
                                </div>

                                 
                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.model_no'),['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('model_no',old('model_no'),['placeholder' => __('messages.model_no'),'class' =>'form-control']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.equipment_location'),['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('location',old('location'),['placeholder' => __('messages.equipment_location'),'class' =>'form-control']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.sn_number'),['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('sn_no',old('sn_no'),['placeholder' => __('messages.sn_number'),'class' =>'form-control']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.refrigerant_type'),['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('refrigerant_type',old('refrigerant_type'),['placeholder' => __('messages.refrigerant_type'),'class' =>'form-control']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div> 

                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.warranty_info'),['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('warranty_info',old('warranty_info'),['placeholder' => __('messages.warranty_info'),'class' =>'form-control']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.voltage_amps'),['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('voltage_amps',old('voltage_amps'),['placeholder' => __('messages.voltage_amps'),'class' =>'form-control']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.date_of_purchase'),['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('date_of_purchase',old('date_of_purchase'),['placeholder' => __('messages.date_of_purchase'),'class' =>'form-control max-datepicker']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('name',__('messages.filter_no'),['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('filter_no',old('filter_no'),['placeholder' => __('messages.filter_no'),'class' =>'form-control']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label" for="logo">{{ __('messages.equipment_upload_photo') }} </label><span class="text-danger">*</span>
                                    <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input" data-file-error="{{ __('messages.files_not_allowed') }}" @php echo $equipmentdata->file? "" : "required" @endphp  >
                                        <label class="custom-file-label">{{  __('messages.choose_file',['file' =>  __('messages.attachments') ]) }}
                                        </label>
                                    </div>
                                    <span class="selected_file"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        @if($equipmentdata && $equipmentdata->file)
                                           
                                            <div class="border-left-2">
                                                <p class="ml-2"><b>{{ __('messages.previous_photo') }}</b></p>
                                                <div class="ml-2 mt-3">
                                                    <div class="row">
                                                    
                                                        <div class="col-md-2 pr-10 text-center" >

                                                            <img src="{{ asset('storage/'. $equipmentdata->file->name ) }}" alt="profile-bg" class="avatar-100 d-block mx-auto img-fluid mb-3" />
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{ Form::hidden('company_id', app('request')->input('company_id')  ) }}
                                {{ Form::hidden('file_type', 'restaurant_equipments'  ) }}

                            </div>
                                
                            {{ Form::submit( __('messages.save'), ['class'=>'btn btn-md btn-primary float-right']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @section('bottom_script')
        <script type="text/javascript">
            (function($) {
                "use strict";
                $(document).ready(function(){
                  

                    providerAddress(provider_id,provider_address_id)
                    getSubCategory(category_id,subcategory_id)

                    $(document).on('change' , '#provider_id' , function (){
                        var provider_id = $(this).val();
                        $('#provider_address_id').empty();
                        providerAddress(provider_id,provider_address_id);
                    })
                    $(document).on('change' , '#category_id' , function (){
                        var category_id = $(this).val();
                        $('#subcategory_id').empty();
                        getSubCategory(category_id,subcategory_id);
                    })

                    $('.galary').each(function (index,value) {
                        let galleryClass = $(value).attr('data-gallery');
                        $(galleryClass).magnificPopup({
                            delegate: 'a#attachment_files',
                            type: 'image',
                            gallery: {
                                enabled: true,
                                navigateByImgClick: true,
                                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                            },
                            callbacks: {
                                elementParse: function(item) {
                                    if(item.el[0].className.includes('video')) {
                                        item.type = 'iframe',
                                        item.iframe = {
                                            markup: '<div class="mfp-iframe-scaler">'+
                                                    '<div class="mfp-close"></div>'+
                                                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                                                    '<div class="mfp-title">Some caption</div>'+
                                                '</div>'
                                        }
                                    } else {
                                        item.type = 'image',
                                        item.tLoading = 'Loading image #%curr%...',
                                        item.mainClass = 'mfp-img-mobile',
                                        item.image = {
                                            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
                                        }
                                    }
                                }
                            }
                        })
                    })
                }) 
           
                
            })(jQuery);
        </script>
    @endsection
</x-master-layout>