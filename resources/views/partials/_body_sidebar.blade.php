use Lavary\Menu\Menu;
@php
    $url = '';

    $MyNavBar = \Menu::make('MenuList', function ($menu) use($url){

        //Admin Dashboard
        $menu->add('<span>'.__('messages.dashboard').'</span>', ['route' => 'home'])
            ->prepend('<i class="ri-dashboard-line"></i>')
            ->link->attr(['class' => '']);

        $menu->add('<span>'.__('Jobs').'</span>', ['route' => 'jobs.index'])
            ->prepend('<i class="fa fa-users"></i>')
            ->nickname('Jobs')
            ->data('permission', 'part request list');

        $menu->add('<span>'.__('messages.provider').'</span>', ['class' => ''])
                ->prepend('<i class="la la-users"></i>')
                ->nickname('provider')
                ->data('permission', 'provider list')
                ->link->attr(["class" => ""])
                ->href('#providers');

                $menu->provider->add('<span>'.__('messages.list_form_title',['form' => __('messages.provider')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'provider.index'])
                    ->data('permission', 'provider list')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->provider->add('<span>'.__('messages.pending_list_form_title',['form' => __('messages.provider')]).'</span>', ['class' => 'sidebar-layout' ,'route' => ['provider.pending','pending']])
                    ->data('permission', 'pending provider')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);


        $menu->add('<span>'.__('messages.users').'</span>', ['route' => 'user.index'])
                ->prepend('<i class="fa fa-users"></i>')
                ->nickname('user')
                ->data('permission', 'user list');

        $menu->add('<span>'.__('messages.part_request').'</span>', ['route' => 'part-request.index'])
                ->prepend('<i class="fa fa-users"></i>')
                ->nickname('Part Request')
                ->data('permission', 'part request list');

        // inProgressWork
        // $menu->add('<span>'.__('messages.user_management').'</span>', ['route' => 'user_management.index'])
        //         ->prepend('<i class="fa fa-users"></i>')
        //         ->nickname('User Management')
        //         ->data('permission', 'user management list');


        // $menu->add('<span>'.__('messages.tax').'</span>', ['route' => 'tax.index'])
        //         ->prepend('<i class="fas fa-percent"></i>')
        //         ->nickname('tax')
        //         ->data('permission', 'tax list');

        $menu->add('<span>'.__('messages.payment_history').'</span>', ['route' => 'payment.index'])
                ->prepend('<i class="ri-secure-payment-line"></i>')
                ->nickname('tax')
                ->data('permission', 'transaction list');


        $menu->add('<span>'.__('messages.email_templates').'</span>', ['route' => 'emailtemplates.index'])
                ->prepend('<i class="fa fa-envelope"></i>')
                ->nickname('Email Templates')
                ->data('permission', 'emailTemplates list');

        $menu->add('<span>'.__('messages.pages').'</span>', ['class' => ''])
                ->prepend('<i class="ri-pages-line"></i>')
                ->nickname('pages')
                ->data('permission', 'pages')
                ->link->attr(["class" => ""])
                ->href('#pages');
                $menu->pages->add('<span>'.__('messages.terms_condition').'</span>', ['class' => 'sidebar-layout' ,'route' => 'term-condition'])
                    ->data('permission', 'terms condition')
                    ->prepend('<i class="fas fa-file-contract"></i>')
                    ->link->attr(['class' => '']);

                $menu->pages->add('<span>'.__('messages.privacy_policy').'</span>', ['class' => 'sidebar-layout' ,'route' => 'privacy-policy'])
                    ->data('permission', 'privacy policy')
                    ->prepend('<i class="ri-file-shield-2-line"></i>')
                    ->link->attr(['class' => '']);

        $menu->add('<span>'.trans('messages.service').'</span>', ['class' => ''])
                ->prepend('<i class="ri-service-line"></i>')
                ->nickname('service')
                ->data('permission', 'service list')
                ->link->attr(["class" => ""])
                ->href('#services');

                $menu->service->add('<span>'.trans('messages.list_form_title',['form' => trans('messages.service')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'service.index'])
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->data('permission', 'service list')
                    ->link->attr(['class' => '']);

                $menu->service->add('<span>'.trans('messages.add_form_title',['form' => trans('messages.service')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'service.create'])
                    ->data('permission', 'service add')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);


        $menu->add('<span>'.__('messages.setting').'</span>', ['route' => 'setting.index'])
                ->prepend('<i class="ri-settings-2-line"></i>')
                ->nickname('setting')
                ->data('permission', 'system setting');

        })->filter(function ($item) {
            return checkMenuRoleAndPermission($item);
        });

@endphp
<div class="iq-sidebar sidebar-default">
    <div class="iq-sidebar-logo d-flex align-items-end justify-content-between">
        <a href="{{ route('home') }}" class="header-logo">
            <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid rounded-normal light-logo site_logo_preview" alt="logo">
            <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid rounded-normal darkmode-logo site_logo_preview" alt="logo">
            <span class="white-space-no-wrap">{{ ucfirst(str_replace("_"," ",auth()->user()->user_type)) }}</span>
        </a>
        <div class="side-menu-bt-sidebar-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-light wrapper-menu" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>
    <div class="data-scrollbar" data-scroll="1">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="side-menu">
                @include(config('laravel-menu.views.bootstrap-items'), ['items' => $MyNavBar->roots()])
            </ul>
        </nav>
        <div class="pt-5 pb-5"></div>
    </div>
</div>
