<x-master-layout>
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                            <a href="{{ route('provider.index') }}   " class="float-right btn btn-sm btn-primary"><i
                                    class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>






            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-sm-3 col-lg-12">
                                <div class="card card-block p-card">
                                    <h5>{{ __('Invoice Detail') }}</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="p-0" style="font-weight: bold;" >
                                                <p class="mb-0 text-muted">{{ __('Invoice Number') }}</p>
                                            </td>
                                            <td style="font-weight: bold;" >
                                                <p class="mb-0 ">{{ $invoiceDetails->invoice_number }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="font-weight: bold;" >
                                                <p class="mb-0 text-muted">{{ __('Restaurant Name') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 " style="font-weight: bold;" >
                                                    {{ isset($invoiceDetails->company->location->restaurant_name)?$invoiceDetails->company->location->restaurant_name:'-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="font-weight: bold;" >
                                                <p class="mb-0 text-muted">{{ __('Technician Name') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 " style="font-weight: bold;" >
                                                    {{ ucfirst($invoiceDetails->provider->user->first_name) . ' ' . ucfirst($invoiceDetails->provider->user->last_name) }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="font-weight: bold;" >
                                                <p class="mb-0 text-muted">{{ __('Service') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 " style="font-weight: bold;" >
                                                    {{ $invoiceDetails->service->name }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="font-weight: bold;" >
                                                <p class="mb-0 text-muted">{{ __('Sub-Total') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 " style="font-weight: bold;" >
                                                    ${{ $invoiceDetails->sub_total }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="font-weight: bold;" >
                                                <p class="mb-0 text-muted">{{ __('Tax') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 " style="font-weight: bold;" >
                                                    ${{ $invoiceDetails->tax }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="font-weight: bold;" >
                                                <p class="mb-0 text-muted">{{ __('Total Amount') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 " style="font-weight: bold;" >
                                                    ${{ $invoiceDetails->total_amount }}
                                                </p>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-sm-3 col-lg-12">
                                <div class="card card-block p-card">
                                    <div class="profile-box">
                                        <div class="profile-card rounded">
                                            @if( isset($invoiceDetails->provider->documents) )
                                                @foreach ( $invoiceDetails->provider->documents as $document )
                                                    @if($document->document_type=='provider_profile_picture')
                                                        @if (isset($document->document->name))
                                                            <img src="{{ asset('storage/' . $document->document->name) }}" alt="profile-bg" class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                                        @else
                                                            <img src="{{ asset('images/user/user.png') }}" alt="profile-bg" class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @else
                                                <img src="{{ asset('images/user/user.png') }}" alt="profile-bg" class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                            @endif

                                            <h3 class="font-600 text-white text-center mb-5">
                                                {{ ucfirst($invoiceDetails->provider->user->first_name).' '.ucfirst($invoiceDetails->provider->user->last_name) }}
                                            </h3>
                                        </div>
                                        <div class="pro-content rounded">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="p-icon mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-primary"
                                                        width="20" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <p style="font-weight: bold;" class="mb-0 eml">{{ isset($invoiceDetails->provider->user->email) ? ucfirst($invoiceDetails->provider->user->email) : '-' }}</p>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="p-icon mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-primary"
                                                        width="20" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <p class="mb-0" style="font-weight:bold;" >
                                                    {{ isset($invoiceDetails->provider->contact_number) ? $invoiceDetails->provider->contact_number : '-' }}
                                                </p>
                                            </div>
                                            {{-- @if (!empty($providerdata ?? ''->address)) --}}
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="p-icon mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-primary"
                                                            width="20" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <p class="mb-0" style="font-weight:bold;" >
                                                        {{$invoiceDetails->provider->address}}, {{$invoiceDetails->provider->city}}
                                                    </p>
                                                </div>
                                            {{-- @endif --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            {{-- <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-3 col-lg-12">
                        <div class="card card-block p-card">
                            <h5></h5>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.invoice_number') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">{{ $invoiceDetails->invoice_number }}</p>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.restaurant_name') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">{{ $invoiceDetails->company->location->restaurant_name }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.technician_name') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">
                                            {{ $invoiceDetails->provider->user->first_name . ' ' . $invoiceDetails->provider->user->last_name }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.service') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">{{ $invoiceDetails->service->name }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.sub_total') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">${{ $invoiceDetails->sub_total }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.tax') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">${{ $invoiceDetails->tax }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.total_amount') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">${{ $invoiceDetails->total_amount }}</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}




            @if (count($invoiceDetails->invoiceItems) > 0)
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-3 col-lg-12">
                            <div class="card card-block p-card">
                                <h5>Invoice items</h5>
                                <table class="table table-borderless mb-0">

                                    @foreach ($invoiceDetails->invoiceItems as $invoiceItem)
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.invoice_item_title') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">{{ $invoiceItem->title }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.quantity') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">{{ $invoiceItem->quantity }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.unit') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">{{ $invoiceItem->unit }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.price') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">{{ $invoiceItem->price }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.sub_total') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">${{ $invoiceItem->sub_total }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif



        </div>

    </div>

    </div>


    <div class="row"></div>
    </div>



</x-master-layout>
