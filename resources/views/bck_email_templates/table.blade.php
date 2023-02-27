
{!! $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered']) !!}

@push('third_party_scripts')
    
    {!! $dataTable->scripts() !!}
@endpush