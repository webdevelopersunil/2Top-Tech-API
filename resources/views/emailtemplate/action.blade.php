
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['emailtemplates.destroy', $emailtemplates->id], 'method' => 'delete','data--submit'=>'emailtemplates'.$emailtemplates->id]) }}
<div class="d-flex justify-content-end align-items-center">
 	@if(!$emailtemplates->trashed())
       
        <a class="mr-2" href="{{ route('emailtemplates.create',['id' => $emailtemplates->id]) }}" title="{{ __('messages.update_form_title',['form' => __('messages.email_templates') ]) }}"><i class="fas fa-pen text-secondary"></i></a>
        
        <a class="mr-2" href="javascript:void(0)" data--submit="emailtemplates{{$emailtemplates->id}}" 
            data--confirmation='true' 
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.email_templates') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.email_templates') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
        
    @endif
       
</div>
{{ Form::close() }}