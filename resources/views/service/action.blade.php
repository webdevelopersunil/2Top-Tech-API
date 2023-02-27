
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['service.destroy', $service->id], 'method' => 'delete','data--submit'=>'service'.$service->id]) }}
<div class="d-flex justify-content-end align-items-center">
    @if(!$service->trashed())
       
        <a class="mr-2" href="{{ route('service.create',['id' => $service->id]) }}" title="{{ __('messages.update_form_title',['form' => __('messages.service') ]) }}"><i class="fas fa-pen text-secondary"></i></a>
        
        <a class="mr-2" href="javascript:void(0)" data--submit="service{{$service->id}}" 
            data--confirmation='true' 
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.service') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.service') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
        
    @endif
    @if(auth()->user()->hasAnyRole(['admin']) && $service->trashed())
        <a href="{{ route('service.action',['id' => $service->id, 'type' => 'restore']) }}"
            title="{{ __('messages.restore_form_title',['form' => __('messages.service') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.service') ]) }}"
            data-message='{{ __("messages.restore_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="fas fa-redo text-secondary"></i>
        </a>
        <a href="{{ route('service.action',['id' => $service->id, 'type' => 'forcedelete']) }}"
            title="{{ __('messages.forcedelete_form_title',['form' => __('messages.service') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.service') ]) }}"
            data-message='{{ __("messages.forcedelete_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    @endif
</div>
{{ Form::close() }}