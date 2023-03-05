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

                    <div @if ($partrequest->status == 'Pending') class="col-lg-8" @else class="col-lg-12" @endif>
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                                    <a href="{{ route('part-request.index') }}   "
                                        class="float-right btn btn-sm btn-primary"><i
                                            class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($partrequest->status == 'Pending')
                        <div class="col-lg-4">
                            <div class="provider-actions">
                                <a href="{{ route('partRequestStatusUpdate', ['id' => $partrequest->id, 'status' => 'Fullfiled']) }}"
                                    class="badge badge-success">Fullfiled</a>
                                <a href="{{ route('partRequestStatusUpdate', ['id' => $partrequest->id, 'status' => 'NotAvailable']) }}"
                                    class="badge badge-danger">NotAvailable</a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            {{-- <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-sm-3 col-lg-12">
                                <div class="card card-block p-card">
                                    <h5>{{ __('messages.work_experience') }}</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('Part Request Status') }}</p>
                                            </td>
                                            <td>
                                                @if ($partrequest->status == 'Fullfiled')
                                                    <a class="badge badge-success">Fullfiled</a>
                                                @elseif($partrequest->status == 'Pending')
                                                    <a class="badge badge-danger">Pending</a>
                                                @elseif($partrequest->status == 'NotAvailable')
                                                    <a class="badge badge-danger">NotAvailable</a>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    <h5>{{ __('messages.work_experience') }}</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.bio') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">{{ $partrequest->provider->bio ? $partrequest->provider->bio : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.experience_years') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $partrequest->provider->experience_years ? (float) $partrequest->provider->experience_years : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.education') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $partrequest->provider->education ? ucfirst($partrequest->provider->education) : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.previous_employer') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $partrequest->provider->previous_employer ? $partrequest->provider->previous_employer : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.referral') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $partrequest->provider->referral ? $partrequest->provider->referral : '-' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.trade_education') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $partrequest->provider->trade_education ? $partrequest->provider->trade_education : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.preferred_distance') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $partrequest->provider->preferred_distance ? $partrequest->provider->preferred_distance . ' miles' : '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.trade_organization') }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    {{ $partrequest->provider->trade_organization ? $partrequest->provider->trade_organization : '-' }}
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
                                                    $
                                                    {{ $partrequest->provider->hourly_rate ? (float) $partrequest->provider->hourly_rate : '-' }}
                                                </p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="p-0">
                                                <p class="mb-0 text-muted">{{ __('messages.weekend_rate') }}sdd</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 ">
                                                    $ {{ $partrequest->provider->weekend_rate ? (float) $partrequest->provider->weekend_rate : '-' }}
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
                                            @if( isset($profile_picture))

                                                    <img src="{{ asset('storage/' . $profile_picture) }}" alt="profile-bg" class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                                @else
                                            <img src="{{ asset('images/user/user.png') }}"
                                                class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />

                                            @endif

                                            <h3 class="font-600 text-white text-center mb-5">
                                                {{ substr(ucfirst($partrequest->provider->user->first_name), 0, 10) }}
                                                {{ substr(ucfirst($partrequest->provider->user->last_name), 0, 10) }}
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
                                                <p class="mb-0 eml">{{ ucfirst($partrequest->provider->user->email) }}
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
                                                <p class="mb-0">{{ $partrequest->provider->contact_number }}</p>
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
                                                        {{ $partrequest->provider->address }}
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
            </div> --}}
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body">
                        <h5>{{ __('Part Detail Details') }}</h5>

                            {{-- <div class="col-lg-8">
                                @if (file_exists(asset('storage/' . $partrequest->file->name)))
                                    <img src="{{ asset('storage/' . $partrequest->file->name) }} alt='File Not Found' " class="gallery-img">
                                @else
                                    <span class='mb-0' >-</span>
                                @endif
                            </div> --}}

                            <table class="table table-borderless mb-0">
                                <td class="p-0">
                                    <p class="mb-0 text-muted">{{ __('Part Request Status') }}</p>
                                </td>
                                <td>
                                    @if ($partrequest->status == 'Fullfiled')
                                        <a class="badge badge-success">Fullfiled</a>
                                    @elseif($partrequest->status == 'Pending')
                                        <a class="badge badge-danger">Pending</a>
                                    @elseif($partrequest->status == 'NotAvailable')
                                        <a class="badge badge-danger">NotAvailable</a>
                                    @endif
                                </td>
                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('messages.account_holder_name') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 "> {{ $partrequest->name }} </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('Model Number') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 "> {{ !empty($partrequest->model_no) ? $partrequest->model_no : '-' }} </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="p-0">
                                        <p class="mb-0 text-muted">{{ __('description') }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 "> {{ ($partrequest->description != null) ? $partrequest->description : '-' }} </p>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</x-master-layout>
