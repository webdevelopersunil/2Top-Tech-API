
<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                            <a href="{{ route('emailtemplates.create') }}" class="float-right mr-1 btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> {{ __('messages.add_form_title',['form' => __('messages.email_templates')  ]) }}</a>
                        </div>
                        {!! $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered']) !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@push('third_party_scripts')
    {!! $dataTable->scripts() !!}
@endpush
</x-master-layout>



