
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['equipment.destroy', $equipment->id], 'method' => 'delete','data--submit'=>'equipment'.$equipment->id]) }}
<div class="d-flex justify-content-end align-items-center">

    @if(!$equipment->trashed())
        <a class="mr-2" href="{{ route('equipment.create',['id' => $equipment->id, 'company_id' => $restaurant_owner_id]) }}" title="{{ __('messages.update_form_title',['form' => __('messages.equipment') ]) }}"><i class="fas fa-pen text-secondary"></i></a>


        <a class="mr-2" href="javascript:void(0)" data--submit="equipment{{$equipment->id}}" 
            data--confirmation='true' 
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.equipment') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.equipment') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
       
    @endif
    @if(auth()->user()->hasAnyRole(['admin']) && $equipment->trashed())
        <a href="{{ route('equipment.action',['id' => $equipment->id, 'type' => 'restore']) }}"
            title="{{ __('messages.restore_form_title',['form' => __('messages.equipment') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.equipment') ]) }}"
            data-message='{{ __("messages.restore_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="fas fa-redo text-secondary"></i>
        </a>
        <a href="{{ route('equipment.action',['id' => $equipment->id, 'type' => 'forcedelete']) }}"
            title="{{ __('messages.forcedelete_form_title',['form' => __('messages.equipment') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.equipment') ]) }}"
            data-message='{{ __("messages.forcedelete_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    @endif
</div>
{{ Form::close() }}