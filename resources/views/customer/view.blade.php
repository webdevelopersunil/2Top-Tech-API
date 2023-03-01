<style>
.mb-0.text-muted {
    font-weight: bold;
}
.mb-0 {
    font-weight: bold;
}
    </style>
<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-block card-stretch">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="font-weight-bold">{{$pageTitle}}</h5>
                        <a href="{{ route('user.index') }}   " class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <!-- start company details section -->
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-sm-3 col-lg-12">
                            <div class="card card-block p-card">
                                <h5>{{  __('messages.company_details') }}</h5>
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        @if($customerdata->company)
                                            @php $company = $customerdata->company @endphp

                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.business_name') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{$company->business_name? $company->business_name: '-'}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.restaurant_name') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{isset($company->location->restaurant_name)? ucfirst($company->location->restaurant_name) : "-"}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.contact_name') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{isset($company->location->contact_name)? ucfirst($company->location->contact_name) : "-"}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.email') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{isset($company->location->email)? ucfirst($company->location->email) :"-"}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.restaurant_address') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{isset($company->location->address)? ucfirst($company->location->address) :"-"}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.city') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{isset($company->location->city)? ucfirst($company->location->city) : "-"}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.cuisine') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">
                                                            @if( $company->cuisines)
                                                                @php $count =count($company->cuisines) @endphp
                                                                @foreach($company->cuisines as $key=>$cuisine)
                                                                    @if($cuisine->cuisine)
                                                                            {{ $cuisine->cuisine->name}}
                                                                            @if($key < $count-1) , @endif
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                __('-')
                                                            @endif
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.phone_number') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{isset($company->location->phone_number)? ucfirst($company->location->phone_number) : "-"}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.restaurant_type') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{isset($company->location->restaurant_type)? ucfirst($company->location->restaurant_type) : "-"}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.seats') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{isset($company->location->seats)? ucfirst($company->location->seats) : "-"}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.bar') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">
                                                            @if (isset($company->location->bar))
                                                                @if ($company->location->bar == 1)
                                                                    {{__('Yes')}}
                                                                @else
                                                                    {{__('No')}}
                                                                @endif
                                                            @else
                                                                {{__('No')}}
                                                            @endif
                                                            {{-- {{isset($company->location->bar) ? ucfirst($company->location->bar) : "-"}} --}}
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.parking') }}</p>
                                                    </td>
                                                    <td>
                                                        @if (isset($company->location->parking))
                                                            @if ($company->location->parking == 1)
                                                                {{__('Yes')}}
                                                            @else
                                                                {{__('No')}}
                                                            @endif
                                                        @else
                                                            {{__('No')}}
                                                        @endif
                                                        {{-- <p class="mb-0 ">{{isset($company->location->parking) ? ucfirst($company->location->parking) : "-"}}</p> --}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-0">
                                                        <p class="mb-0 text-muted">{{ __('messages.referred_by') }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 ">{{$company->referred_by? $company->referred_by : "-"}}</p>
                                                    </td>
                                                </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end company details section -->

                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-sm-3 col-lg-12">
                            <div class="card card-block p-card">
                                <div class="profile-box">
                                    <div class="profile-card rounded">
                                        @if($customerdata->company && $customerdata->company->file && (file_exists(asset('storage/' . $customerdata->company->file->name))) )
                                            <img src="{{ asset('storage/'. $customerdata->company->file->name ) }}" type="{{$customerdata->company->file->type}}" alt="profile-bg" class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded">
                                        @else
                                            <img src="{{ asset('images/user/user.png') }}" class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded" />
                                        @endif

                                        <h3 class="font-600 text-white text-center mb-5">{{$customerdata->display_name}}</h3>
                                    </div>
                                    <div class="pro-content rounded">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="p-icon mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                                                </svg>
                                            </div>
                                            <p class="mb-0 eml" >{{ ucfirst($customerdata->email) }}</p>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="p-icon mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"></path>
                                                </svg>
                                            </div>
                                            <p class="mb-0">{{$customerdata->contact_number}}</p>
                                        </div>
                                        @if(!empty($customerdata->address))
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="p-icon mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="mb-0">{{$customerdata->address}}</p>
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

    <!-- start membership plan section -->
    <div class="row">
        <!--  -->
        <div class="col-lg-12">
            <div class="card card-block card-stretch">
                <div class="card-body">
                    <h5 class="card-title">{{__('messages.membership_plan_details')}}</h5>
                    <div class="table-responsive-sm">
                        <table class="table mb-0">

                            <tbody>
                                 <tr>
                                    <td colspan="4" class="p-0">{{__('messages.subscription_plan')}}</td>
                                    <td colspan="4" style="font-weight: bold;" class="text-center font-weightmb-0 ">
                                        @if( isset($customerdata->company->subsciptionPlan->plan->type) )
                                            {{ ucfirst($customerdata->company->subsciptionPlan->plan->type) }}
                                        @else
                                             -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4"  class="p-0">{{__('messages.expires_at')}}</td>
                                    <td colspan="4" class="text-center font-weightmb-0 " style="font-weight: bold;" >{{$customerdata->company->expires_at? $customerdata->company->expires_at : "-"}}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="p-0">{{__('messages.subscription_status')}}</td>
                                    <td colspan="4" class="text-center font-weightmb-0 " style="font-weight: bold;">{{ $customerdata->company->subscription_status? ucfirst($customerdata->company->subscription_status): "-"}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- end membership plan section -->

    <!-- start pending transactions section -->
    <div class="row">
        <!--  -->
        <div class="col-lg-12">
            <div class="card card-block card-stretch">
                <div class="card-body">
                    <h5 class="card-title">{{__('messages.pending_trans')}}</h5>
                    <div class="table-responsive-sm">
                        <table class="table mb-0">
                            <thead class="table-color-heading">
                                <tr class="text-secondary">
                                    <th scope="col">{{__('messages.service')}}</th>
                                    <th scope="col">{{__('messages.date')}}</th>
                                    <th scope="col">{{__('messages.payment_status')}}</th>
                                    <th scope="col" class="text-right">{{__('messages.total_amount')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($customer_pending_trans) > 0)
                                    @foreach($customer_pending_trans as $pending)
                                        <tr class="white-space-no-wrap">
                                            <td>{{$pending->booking->service->name}}</td>
                                            <td> <div class="d-flex align-items-center">{{date("D, d M Y", strtotime($pending->booking->date))}}</div></td>
                                            <td>{{ $pending->payment_status}}</td>
                                            <td class="text-right">{{ getPriceFormat($pending->total_amount)}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center font-weight-bold">{{__('messages.record_not_found')}}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end pending transactions section -->

    <!-- start team members section -->
         <!-- TODO: display list of team members -->
    <!-- end team members section -->

 <!-- start equipment section -->
    <div class="row">
        <!--  -->
        <div class="col-lg-12">
            <div class="card card-block card-stretch">
                <div class="card-body">
                    <h5 class="card-title">{{__('messages.list_of_equipments')}}</h5>
                    <div class="table-responsive-sm">
                        <table class="table mb-0">
                            <thead class="table-color-heading">
                                <tr class="text-secondary">
                                    <th scope="col">{{__('messages.sr_no')}}</th>
                                    <th scope="col">{{__('messages.name')}}</th>
                                    <th scope="col">{{__('messages.location')}}</th>
                                    <th scope="col">{{__('messages.equipment_category')}}</th>
                                    <th scope="col">{{__('messages.refrigerant_type')}}</th>
                                    <th scope="col">{{__('messages.model_number')}}</th>
                                    <th scope="col">{{__('messages.warranty_info')}}</th>
                                    <th scope="col">{{__('messages.voltage_amps')}}</th>
                                    <th scope="col" class="text-right">{{__('messages.equipment_number')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($customerdata->company->equipments) > 0)
                                    @foreach($customerdata->company->equipments as $key=>$equipment)
                                        <tr class="white-space-no-wrap">
                                            <td>{{ $key+1 }}</td>
                                            <td>{{$equipment->name}}</td>
                                            <td> {{ $equipment->location }}</td>
                                            <td>{{ $equipment->category->value }}</td>
                                            <td>{{ $equipment->refrigerant_type }}</td>
                                            <td>{{ $equipment->model_no }}</td>
                                            <td>{{ $equipment->warranty_info }}</td>
                                            <td>{{ $equipment->voltage_amps }}</td>
                                            <td class="text-right">{{ $equipment->equipment_number }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center font-weight-bold">{{__('messages.record_not_found')}}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end equipment section -->

</div>
</x-master-layout>
