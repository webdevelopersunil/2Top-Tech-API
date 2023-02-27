<style>
.mb-0 {
    font-weight: bold;
}
</style>
<x-master-layout>
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="row">


                    <div @if ($providerdata->status !== 'approved') class="col-lg-8" @else class="col-lg-12" @endif >
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                                    <a href="{{ route('provider.index') }}   "
                                        class="float-right btn btn-sm btn-primary"><i
                                            class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($providerdata->status !== 'approved')
                        <div class="col-lg-4">
                            <div class="provider-actions">
                                    <a href="{{ route('changeProviderStatus', ['id' => $providerdata->id, 'status' => 'approve']) }}"
                                        class="badge badge-success">Approve</a>
                                    <a href="{{ route('changeProviderStatus', ['id' => $providerdata->id, 'status' => 'unapprove']) }}"
                                        class="badge badge-danger">Unapprove</a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-sm-3 col-lg-12">
                                <div class="card card-block p-card">
                                    <h5>{{ __('messages.work_experience') }}</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.bio') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">{{ $providerdata->bio ? $providerdata->bio : '-' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.experience_years') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $providerdata->experience_years ? (float) $providerdata->experience_years : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.education') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $providerdata->education ? ucfirst($providerdata->education) : '-' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.previous_employer') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $providerdata->previous_employer ? $providerdata->previous_employer : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.referral') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $providerdata->referral ? $providerdata->referral : '-' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.trade_education') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $providerdata->trade_education ? $providerdata->trade_education : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.preferred_distance') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $providerdata->preferred_distance ? $providerdata->preferred_distance . ' miles' : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.trade_organization') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $providerdata->trade_organization ? $providerdata->trade_organization : '-' }}
                                                </p>
                                            </td>
                                        </tr>

                                    </table>


                                    <p style="width:400px;"></p>

                                    <h5>{{ __('messages.pricing') }}</h5>

                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.hourly_rate') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    $ {{ $providerdata->hourly_rate ? (float) $providerdata->hourly_rate : '-' }}
                                                </p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.weekend_rate') }}sdd</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    $ {{ $providerdata->weekend_rate ? (float) $providerdata->weekend_rate : '-' }}</p>
                                            </td>
                                        </tr>

                                    </table>

                                    <p style="width:400px;"></p>

                                    <h5>{{ __('Services') }}</h5>

                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            @if (isset($providerdata->services))
                                            <div class="buttons" >
                                                @foreach ( $providerdata->services as $service )
                                                    <span class="span-tag-services" style=""  >{{$service->service->name}}</span>
                                                @endforeach
                                            </div>
                                            @endif
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>







                        {{-- <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-block card-stretch">
                                    <div class="card-body">
                                        <h5>{{ __('messages.pricing') }}</h5>
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="p-0">
                                                    <p class="mb-0 text-muted">{{ __('messages.hourly_rate') }}</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 ">
                                                        $ {{ $providerdata->hourly_rate ? (float) $providerdata->hourly_rate : '-' }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">
                                                    <p class="mb-0 text-muted">{{ __('messages.weekend_rate') }}sdd</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 ">
                                                        $ {{ $providerdata->weekend_rate ? (float) $providerdata->weekend_rate : '-' }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-sm-3 col-lg-12">
                                <div class="card card-block p-card">
                                    <div class="profile-box">
                                        <div class="profile-card rounded">
                                            @if ( $profile_picture != '' && isset($profile_picture) )
                                                {{-- asset('storage/'.$profile_picture) --}}
                                                <img src="{{ asset('storage/' . $profile_picture) }}" alt="profile-bg" class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                            @else
                                                <img src="{{ asset('images/user/user.png') }}" class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                            @endif

                                            <h3 class="font-600 text-white text-center mb-5">
                                                {{ substr(ucfirst($providerdata->user->first_name), 0, 10) }}
                                                {{ substr(ucfirst($providerdata->user->last_name), 0, 10) }}
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
                                                <p class="mb-0 eml">{{ ucfirst($providerdata->user->email) }}</p>
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
                                                <p class="mb-0">{{ $providerdata->contact_number }}</p>
                                            </div>
                                            @if (!empty($providerdata->address))
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
                                                    <p class="mb-0">
                                                        {{ $providerdata->address }}
                                                        @if ($providerdata->states->name || $providerdata->city)
                                                            {{-- , {{ $providerdata->city }}, --}}
                                                            {{-- {{ $providerdata->states->name }} --}}
                                                        @endif
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body">
                        <h5>{{ __('messages.provider_documents') }}</h5>
                        <!--  <table class="table table-borderless mb-0 privder-document-list"> -->
                        @php
                            $doc_count = 0;
                            $gallery_count = count($providerdata->documents);
                        @endphp
                        @foreach ($providerdata->documents as $key => $provider_document)
                            @if ($doc_count < 1)
                                <div class="row provider-gallery">
                            @endif

                            @if ($provider_document->document_type === 'driver_license_front')
                                <div class="col-lg-4">
                                    <p class="mb-0 text-muted">{{ __('messages.driver_license_front') }}</p>
                                </div>
                                <div class="col-lg-8">
                                    @if (file_exists(asset('storage/' . $provider_document->document->name)))
                                        <img src="{{ asset('storage/' . $provider_document->document->name) }} alt='File Not Found' " class="gallery-img">
                                    @else
                                        <span class='mb-0' >-</span>
                                    @endif


                                </div>
                            @endif

                            @if ($provider_document->document_type === 'driver_license_back')
                                <div class="col-lg-4">
                                    <p class="mb-0 text-muted">{{ __('messages.driver_license_back') }}</p>
                                </div>
                                <div class="col-lg-8">
                                    @if (file_exists(asset('storage/' . $provider_document->document->name)))
                                        <img src="{{ asset('storage/' . $provider_document->document->name) }}" class="gallery-img">
                                    @else
                                    <span class='mb-0' >-</span>
                                    @endif

                                </div>
                            @endif

                            @if ($provider_document->document_type === 'certification_license')
                                <div class="col-lg-4">
                                    <p class="mb-0 text-muted">{{ __('messages.certification_license') }}</p>
                                </div>
                                <div class="col-lg-8">
                                    @if (file_exists(asset('storage/' . $provider_document->document->name)))
                                        <img src="{{ asset('storage/' . $provider_document->document->name) }}"
                                    class="gallery-img">
                                    @else
                                    <span class='mb-0' >-</span>
                                    @endif

                                </div>
                            @endif
                            @if ($provider_document->document_type === 'vehicle_license_plate' && $provider_document->document)
                                <div class="col-lg-4">
                                    <p class="mb-0 text-muted">{{ __('messages.vehicle_license_plate') }}</p>
                                </div>
                                <div class="col-lg-8">
                                    @if (file_exists(asset('storage/' . $provider_document->document->name)))
                                        <img src="{{ asset('storage/' . $provider_document->document->name) }}" class="gallery-img">
                                    @else
                                    <span class='mb-0' >-</span>
                                    @endif
                                </div>
                            @endif

                            @if ($provider_document->document_type === 'provider_gallery' && $doc_count == 0)
                                @php  $doc_count = $doc_count +1; @endphp

                                <div class="col-lg-4">
                                    <p class="mb-0 text-muted">{{ __('messages.provider_documents') }}</p>
                                </div>
                                <div class="col-lg-8">
                                    <img src="{{ asset('storage/' . $provider_document->document->name) }}"
                                        class="gallery-img">
                                @elseif($provider_document->document_type === 'provider_gallery')
                                    <img src="{{ asset('storage/' . $provider_document->document->name) }}"
                                        class="gallery-img">
                            @endif


                            @if ($provider_document->document_type === 'provider_documents' && $gallery_count - 1 == $key)
                    </div>
                    @endif

                    @if ($doc_count < 1)
                </div>
                @endif
                @endforeach

                <!-- </table> -->
            </div>
        </div>



        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 asd">
                            <h5>{{ __('messages.provider_services') }}</h5>
                        </div>
                        {{ $dataTable->table(['class' => 'table  w-100'], false) }}
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
    </div>


    </div>


    </div>

    @if ($providerdata->providerPatymentMethod)
        <div class="card row">
            <div class="card-body p-0">
                <div class="col-lg-12">
                    <div class="provider-bottom row">
                        <div class="col-lg-12">
                            <h5>{{ __('messages.bank_details') }}</h5>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.account_holder_name') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">
                                            {{ $providerdata->providerPatymentMethod->account_holder_name }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.payment_method') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">{{ $providerdata->providerPatymentMethod->payment_method }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.account_holder_type') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">
                                            {{ $providerdata->providerPatymentMethod->account_holder_type }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.last4') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">**** ***** ****
                                            {{ $providerdata->providerPatymentMethod->last4 }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.country_id') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">{{ $providerdata->providerPatymentMethod->country->name }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.currency') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">{{ $providerdata->providerPatymentMethod->currency }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.bank_name') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 ">{{ $providerdata->providerPatymentMethod->bank_name }}</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



    <div class="row"></div>
    </div>

    <!-- medium modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="mediumBody">
                    <div class="modal-image">
                        <!-- the result to be displayed apply here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('bottom_script')
        {{ $dataTable->scripts() }}
        <script type="text/javascript">
            $(document).on('click', '.gallery-img', function() {
                var img_src = $(this).attr('src');
                $(".modal-image").empty();
                var html = "<img src =" + img_src + " width= '100%;' >";
                $(".modal-image").append(html);
                $('#galleryModal').modal('show');
            });
        </script>
    @endsection

</x-master-layout>
