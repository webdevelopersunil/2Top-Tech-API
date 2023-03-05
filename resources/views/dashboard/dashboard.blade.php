<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="adminModalLabel">{{ __('messages.dashboard_customizer') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
            @if (($data['dashboard_setting'] != [] && !empty($data['dashboard_setting']->Top_Cards)) || $show == 'true')
                <div class="col-md-12">
                    <div class="dashboard-container">
                        <div class="row">
                            <div class="col-md-3 card-green">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="d-flex flex-wrap justify-content-start align-items-center">
                                                    <h5 class="mb-2 font-weight-bold">
                                                        {{ !empty($data['dashboard']['count_total_service']) ? $data['dashboard']['count_total_service'] : 0 }}
                                                    </h5>
                                                </div>
                                                <p class="mb-0">
                                                    {{ __('messages.total_name', ['name' => __('messages.service')]) }}
                                                </p>
                                                <p class="mb-0 "> &nbsp&nbsp&nbsp </p>
                                            </div>
                                            <div class="col-auto d-flex flex-column">
                                                <div class="iq-card-icon icon-shape text-white rounded-circle">
                                                    <i class="ri-service-line"></i>
                                                </div>
                                                <a class="pt-2"
                                                    href="{{ route('service.index') }}">{{ __('messages.view_all') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 card-orange">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="d-flex flex-wrap justify-content-start align-items-center">
                                                    <h5 class="mb-2  font-weight-bold">
                                                        {{ !empty($data['dashboard']['count_total_provider']) ? $data['dashboard']['count_total_provider'] : 0 }}
                                                    </h5>
                                                    <p class="mb-0 ml-3  font-weight-bold"></p>
                                                </div>
                                                <p class="mb-0 ">
                                                    {{ __('messages.total_name', ['name' => __('messages.provider')]) }}
                                                </p>
                                                <p class="mb-0 ">
                                                    {{ __('(Last 30 days)') }}
                                                </p>
                                            </div>
                                            <div class="col-auto d-flex flex-column">
                                                <div class="iq-card-icon icon-shape text-white rounded-circle ">
                                                    <i class="la la-users"></i>
                                                </div>
                                                <a class="pt-2"
                                                    href="{{ route('provider.index') }}">{{ __('messages.view_all') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 card-blue">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="d-flex flex-wrap justify-content-start align-items-center">
                                                    <h5 class="mb-2  font-weight-bold">
                                                        {{ !empty($data['dashboard']['bookings_count']) ? $data['dashboard']['bookings_count'] : 0 }}
                                                    </h5>
                                                    <p class="mb-0 ml-3 text-danger font-weight-bold"></p>
                                                </div>
                                                <p class="mb-0 ">
                                                    {{ __('messages.total_name', ['name' => __('Bookings')]) }}
                                                </p>
                                                <p class="mb-0 "> &nbsp&nbsp&nbsp </p>
                                            </div>
                                            <div class="col-auto d-flex flex-column">
                                                <div class="iq-card-icon icon-shape text-white rounded-circle ">
                                                    <i class="la la-users"></i>
                                                </div>
                                                <a class="pt-2"
                                                    href="#">{{ __('messages.view_all') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 card-gray">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="d-flex flex-wrap justify-content-start align-items-center">
                                                    <h5 class="mb-2 font-weight-bold">
                                                        {{ !empty($data['dashboard']['job_count']) ? $data['dashboard']['job_count'] : 0 }}
                                                    </h5>
                                                    <p class="mb-0 ml-3 text-danger font-weight-bold"></p>
                                                </div>
                                                <p class="mb-0 ">
                                                    {{ __('messages.total_name', ['name' => __('Job')]) }}
                                                </p>
                                                <p class="mb-0 ">
                                                    {{ __('(Last 30 days)') }}
                                                </p>
                                            </div>
                                            <div class="col-auto d-flex flex-column">
                                                <div class="iq-card-icon icon-shape  text-white rounded-circle">
                                                    <i class="la la-users"></i>
                                                </div>
                                                <a class="pt-2"
                                                    href="{{ route('jobs.index') }}">{{ __('messages.view_all') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            @if (($data['dashboard_setting'] != [] && !empty($data['dashboard_setting']->New_Provider_card)) ||
                $show == 'true')
                <div class="col-md-6">
                    <div class="card">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ __('messages.new_provider') }}</h4>
                                <a href="{{ route('provider.index') }}"
                                    class="float-right mr-1 btn btn-sm btn-primary">{{ __('messages.see_all') }}</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive max-height-400">
                                <table class="table mb-0">
                                    <thead class="table-color-heading">
                                        <tr class="text-secondary">
                                            <th scope="col">{{ __('messages.user') }}</th>
                                            {{-- <th scope="col" class="white-space-no-wrap">{{ __('messages.name') }}</th> --}}
                                            <th scope="col">{{ __('messages.date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($data['dashboard']['new_provider'] as $provider)
                                            <tr class="white-space-no-wrap">

                                                <td>
                                                    <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                        <div class="h-avatar is-medium h-5">
                                                            @foreach ( $provider->documents as $documents )
                                                                @if ($documents['document_type']=='provider_profile_picture')

                                                                    @if (!empty($documents->document->name))
                                                                        <img src="{{asset("storage/". $documents->document->name )}}" alt="profile-bg" class="listing-img" />
                                                                    @else
                                                                        <img class="avatar rounded-circle"  src="{{asset('images/user/user.png')}}" >
                                                                    @endif

                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <div class="data-content">
                                                            <div>
                                                                <span class="font-weight-bold">
                                                                    <a href="{{route('provider.show',$provider->id)}}" style="text-decoration: none; color:#324253;"  >
                                                                        {{ substr(ucfirst($provider->user->first_name), 0, 12) }} {{ substr(ucfirst($provider->user->last_name), 0, 12) }}
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="font-weight: bold;" > {{ date('d M Y', strtotime($provider->created_at)) }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (($data['dashboard_setting'] != [] && !empty($data['dashboard_setting']->New_Customer_card)) ||
                $show == 'true')
                <div class="col-md-6">
                    <div class="card">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ __('messages.new_customer') }}</h4>
                                <a href="{{ route('user.index') }}"
                                    class="float-right mr-1 btn btn-sm btn-primary">{{ __('messages.see_all') }}</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive max-height-400">
                                <table class="table mb-0">
                                    <thead class="table-color-heading">
                                        <tr class="text-secondary">
                                            <th scope="col">{{ __('messages.user') }}</th>
                                            <th scope="col">{{ __('messages.date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($data['dashboard']['new_customer'] as $customer)
                                            <tr class="white-space-no-wrap">

                                                <td>
                                                    <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                        <div class="h-avatar is-medium h-5">

                                                            @if (!empty($customer->file->name))
                                                                <img class="avatar rounded-circle" alt="profile-bg" src="{{asset("storage/". $customer->file->name )}}" >
                                                            @else
                                                                <img class="avatar rounded-circle"  src="{{asset('images/user/user.png')}}" >
                                                            @endif
                                                        </div>
                                                        <div class="data-content">
                                                            <div>
                                                                <a href="{{route('user.show',$customer->user->id)}}" style="text-decoration: none; color:#324253;"  >
                                                                    <span class="font-weight-bold">{{ !empty($customer->user->display_name) ? substr( ucfirst($customer->user->display_name), 0, 20 ) : '-' }}</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="font-weight: bold;" > {{ date('d M Y', strtotime($customer->created_at)) }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-master-layout>
