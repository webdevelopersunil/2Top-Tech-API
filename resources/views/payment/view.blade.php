<x-master-layout>
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                            <a href="{{ route('payment.index') }}   " class="float-right btn btn-sm btn-primary"><i
                                    class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12" id="printableArea" >
                <div class="card">
                    <div class="card-body">
                        <div class="row pb-4 mx-0 card-header-border">
                            <div class="col-lg-12 mb-3">
                                <img class="avatar avatar-50 is-squared" src="{{ asset('storage/13/logo.png') }}" >
                            </div>
                            <div class="col-lg-6">
                                <div class="text-left">
                                    <h5 class="font-weight-bold mb-2"> {{__('Invoice Number')}} </h5>
                                    <p class="mb-0" style="font-weight: bold;" > {{ $invoiceDetails->invoice_number }} </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="text-right">
                                    <h5 class="font-weight-bold mb-2">Invoice Date</h5>
                                    <p class="mb-0">{{ $invoiceDetails->created_at }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-4 pb-5 mx-0">
                            <div class="col-lg-6">
                                <div class="text-left">
                                    {{-- <h5 class="font-weight-bold mb-3">{{ __('Invoice From') }}</h5> --}}
                                    {{-- <p class="mb-0 mb-1">{{ ucfirst($invoiceDetails->company->business_name) }}</p>
                                    <p class="mb-0 mb-1">{{ isset($invoiceDetails->company->location->address) ? $invoiceDetails->company->location->address : '-' }}</p>
                                    <p class="mb-0 mb-1"> {{ isset($invoiceDetails->company->location->state->name) ? $invoiceDetails->company->location->state->name : '-' }} </p> --}}
                                    {{-- <p class="mb-0 mb-1">10011</p> --}}
                                    {{-- <p class="mb-0 mb-2">USA</p>
                                    <p class="mb-0 mb-2" style="font-weight:bold;" >{{ ucfirst($invoiceDetails->company->user->email) }}</p> --}}
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="text-right">
                                    <h5 class="font-weight-bold mb-3">{{ ucfirst('Invoice To') }}</h5>
                                    {{-- <p class="mb-0 mb-1"> {{ ucfirst($invoiceDetails->provider->user->first_name) }} {{ ucfirst($invoiceDetails->provider->user->last_name) }} </p>
                                    <p class="mb-0 mb-1">{{ ucfirst($invoiceDetails->provider->address) }} </p>
                                    <p class="mb-0 mb-1">{{ ucfirst($invoiceDetails->provider->states->name) }}</p> --}}
                                    {{-- <p class="mb-0 mb-1">80202</p> --}}
                                    {{-- <p class="mb-0 mb-2">{{ __('USA') }}</p>
                                    <p class="mb-0 mb-2" style="font-weight:bold;" >{{ ucfirst($invoiceDetails->provider->user->email) }}</p> --}}
                                    <p class="mb-0 mb-1">{{ ucfirst($invoiceDetails->company->business_name) }}</p>
                                    <p class="mb-0 mb-1">{{ isset($invoiceDetails->company->location->address) ? $invoiceDetails->company->location->address : '-' }}</p>
                                    <p class="mb-0 mb-1"> {{ isset($invoiceDetails->company->location->state->name) ? $invoiceDetails->company->location->state->name : '-' }} </p>
                                    {{-- <p class="mb-0 mb-1">10011</p> --}}
                                    <p class="mb-0 mb-2">USA</p>
                                    <p class="mb-0 mb-2" style="font-weight:bold;" >{{ ucfirst($invoiceDetails->company->user->email) }}</p>
                                </div>
                            </div>
                        </div>































                        <div class="row">
                            <div class="col-lg-12">
                                <div class="text-left">
                                    <h5 class="font-weight-bold mb-3">{{ __('Work Logs') }}</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item p-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr class="text-muted">
                                                        {{-- <th scope="col" class="text-left">No.</th> --}}
                                                        <th scope="col">{{ __('Comment') }}</th>
                                                        <th scope="col" class="text-center">{{ __('Total Log Time(Hours)') }}</th>
                                                        {{-- <th scope="col" class="text-center">{{ __('Interval Time') }}</th> --}}
                                                        <th scope="col" class="text-center">{{ __('Price') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        {{-- <td class="text-left">
                                                            {{$index}}
                                                        </td> --}}
                                                        <td>
                                                            {{ __('Started work with Owner') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{$data['time_in_hours']}}
                                                        </td>
                                                        {{-- <td class="text-center">
                                                            {{ $worklog->interval_time }}
                                                        </td> --}}
                                                        <td class="text-center" >
                                                            ${{$data['total_amount']}}
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
































                        <div class="row">
                            <div class="col-lg-12">
                                {{-- <div class="text-left">
                                    <h5 class="font-weight-bold mb-3">{{ __('Line Items') }}</h5>
                                </div> --}}
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item p-0">

                                        <br><br>

                                        <div class="text-left">
                                            <h5 class="font-weight-bold mb-3">{{ __('Line Items') }}</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr class="text-muted">
                                                        <th scope="col" class="text-left">No.</th>
                                                        <th scope="col">{{ __('Title') }}</th>
                                                        <th scope="col" class="text-center">{{ __('Quantity') }}</th>
                                                        <th scope="col" class="text-center">{{ __('Sub Total') }}</th>
                                                        <th scope="col" class="text-right">{{ __('Price') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($invoiceDetails->invoiceItems) > 0)
                                                    @php $index = 1; @endphp
                                                        @foreach ($invoiceDetails->invoiceItems as $invoiceItem)
                                                            <tr>
                                                                <td class="text-left">
                                                                    {{$index}}
                                                                </td>
                                                                <td>
                                                                    {{ ucfirst($invoiceItem->title) }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $invoiceItem->quantity }}
                                                                </td>
                                                                <td class="text-center" >
                                                                    ${{ $invoiceItem->sub_total }}
                                                                </td>

                                                                <td class="text-right">
                                                                    ${{ $invoiceItem->price }}
                                                                </td>
                                                            </tr>
                                                            @php $index ++; @endphp
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-end mb-2">
                                            Subtotal: <p class="ml-2 mb-0">${{ ($sum_of_price + $data['total_amount']) }}</p>
                                        </div>
                                        <div class="d-flex justify-content-end mb-2">
                                            Taxes: <p class="ml-2 mb-0">${{$tax}}</p>
                                        </div>
                                        <div class="d-flex justify-content-end mb-2">
                                            Total: <p class="ml-2 mb-0 font-weight-bold">${{ ( $sum_of_price + $data['total_amount'] + $tax ) }}</p>
                                        </div>

                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-12">
                                <div class="d-flex flex-wrap justify-content-between align-items-center p-4">
                                    <div class="flex align-items-start flex-column">
                                        <h6>Notes</h6>
                                        <p class="mb-0 my-2">Please send all items at the same time to the shipping
                                            address. Thanksin advance.</p>
                                    </div>
                                    <div>
                                        <button class="btn btn-secondary px-4" onclick="printableDiv('printableArea')" >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="20"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Print
                                        </button>
                                        {{-- <button class="btn btn-primary px-4">Send</button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





        </div>

    </div>
</x-master-layout>
<script>
    function printableDiv(printableAreaDivId) {
     var printContents = document.getElementById(printableAreaDivId).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
    </script>
