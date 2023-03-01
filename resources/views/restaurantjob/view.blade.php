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
                    <div class="col-lg-12">
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                                    <a href="{{ route('jobs.index') }}   "
                                        class="float-right btn btn-sm btn-primary">
                                        <i class="fa fa-angle-double-left"></i>
                                        {{ __('messages.back') }}
                                    </a>
                                </div>
                            </div>
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
                                    <h5>{{ __('Job Detail') }}</h5>
                                    <p style="width:400px;"></p>
                                    <table class="table table-borderless mb-0">

                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('Service') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{$jobData->service->name}}
                                                </p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.bio') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ ucfirst($jobData->description) }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('Schedule Type') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ ucfirst($jobData->schedule_type) }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('Status') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ ucfirst($jobData->status) }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('Start At') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $jobData->start_at }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('End At') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $jobData->end_at }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('Restaurant Name') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ ucfirst($jobData->restaurant_name) }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('Restaurant Location') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ ucfirst($jobData->restaurant_location) }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('Active Status') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    @if($jobData->is_active == 1)
                                                        Active
                                                    @else
                                                        Not Active
                                                    @endif
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
                                            @if (isset($jobData->company->file))
                                                <img src="{{ asset('storage/' . $jobData->company->file->name) }}" alt="profile-bg"
                                                    class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                            @else
                                                <img src="{{ asset('images/user/user.png') }}"
                                                    class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                            @endif

                                            <h3 class="font-600 text-white text-center mb-5">
                                                {{ ucfirst($jobData->company->business_name) }}
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
                                                <p class="mb-0 eml">
                                                    {{ ucfirst($jobData->company->user->email) }}
                                                </p>
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
                                                <p class="mb-0">
                                                    @if ( isset($jobData->company->location) )
                                                    -
                                                    @else
                                                        @if( isset($jobData->company->location->phone_number) )
                                                            {{$jobData->company->location->phone_number}}
                                                        @else
                                                            -
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                            {{-- @if (!empty($providerdata->address)) --}}
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
                                                    {{ ucfirst($jobData->restaurant_location) }}
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
        </div>


        <div class="row">


            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-3 col-lg-12">
                        <div class="card card-block p-card">
                            <h5>{{ __('Job Applicants') }}</h5>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted" style="font-weight:bold;" >
                                            {{ __('Profile Pic') }}
                                        </p>
                                    </td>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted" style="font-weight:bold;" >
                                            {{ __('Name') }}
                                        </p>
                                    </td>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted" style="font-weight:bold;" >
                                            {{ __('Email') }}
                                        </p>
                                    </td>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted" style="font-weight:bold;" >
                                            {{ __('Application Status') }}
                                        </p>
                                    </td>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted" style="font-weight:bold;" >
                                            {{ __('Rate Type') }}
                                        </p>
                                    </td>

                                    <td class="p-0">
                                        <p class="mb-0 text-muted" style="font-weight:bold;" >
                                            {{ __('Rate') }}
                                        </p>
                                    </td>

                                    <td class="p-0">
                                        <p class="mb-0 text-muted" style="font-weight:bold;" >
                                            {{ __('Action') }}
                                        </p>
                                    </td>
                                </tr>
                                <p style="width:400px;"></p>
                                @if( count($providers) >= 1 )
                                    @foreach ( $providers as $provider )
                                    <tr>
                                        <td>
                                            <p class="mb-0 ">
                                                @if (isset($provider->providerDetail->documents))
                                                    @foreach ($provider->providerDetail->documents as $document)
                                                        @if ($document->document_type == 'provider_profile_picture')
                                                            <img src="{{ asset('storage/' . $document->document->name) }}" style="height: 50px; width: 50px; min-width: 50px;"
                                                        class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                                        @endif
                                                    @endforeach
                                                @else
                                                <img src="{{ asset('images/user/user.png') }}" style="height: 50px; width: 50px; min-width: 50px;"
                                                class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                                @endif
                                            </p>
                                        </td>
                                        <td class="p-0">
                                            <p class="mb-0 text-muted">
                                                {{ ucfirst($provider->providerDetail->user->first_name) }}
                                                {{ ucfirst($provider->providerDetail->user->last_name) }}
                                            </p>
                                        </td>
                                        <td class="p-0">
                                            <p class="mb-0 text-muted" style="font-weight:bold;" >
                                                {{ ucfirst($provider->providerDetail->user->email) }}
                                            </p>
                                        </td>
                                        <td class="p-0">
                                            <p class="mb-0 text-muted">{{ $provider->application_status }}</p>
                                        </td>
                                        <td class="p-0">
                                            <p class="mb-0 text-muted">{{ ucfirst($provider->rate_type) }}</p>
                                        </td>
                                        <td class="p-0">
                                            <p class="mb-0 text-muted">$ {{ ucfirst($provider->rate) }}</p>
                                        </td>
                                        <td class="p-0">
                                            <div class="d-flex justify-content-end align-items-center">
                                                <a class="mr-2" href="{{ route('provider.show',$provider->id) }} "><i class="far fa-eye text-secondary"></i></a>
                                            </div>
                                        </td>
                                    </tr>

                                    @endforeach
                                @endif
                            </table>

                            <p style="width:400px;"></p>

                            @if( count($providers) == 0 )
                                <div style="text-align: center;" >
                                    <span  style="text-decoration: bold;" >
                                        {{ __('No applicants found.') }}
                                    </span>
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
