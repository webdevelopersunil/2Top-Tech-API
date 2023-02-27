<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();

        DB::table('settings')->insert(array (
            array (
                'id' => 1,
                'key' => 'ADMOB_APP_ID',
                'type' => 'ADMOB',
                'value' => NULL,
            ),
            array (
                'id' => 2,
                'key' => 'ADMOB_BANNER_ID',
                'type' => 'ADMOB',
                'value' => NULL,
            ),
            array (
                'id' => 3,
                'key' => 'ADMOB_INTERSTITIAL_ID',
                'type' => 'ADMOB',
                'value' => NULL,
            ),
            array (
                'id' => 4,
                'key' => 'COLOR_PRIMARY_COLOR',
                'type' => 'COLOR',
                'value' => '#000000',
            ),
            array (
                'id' => 5,
                'key' => 'COLOR_SECONDARY_COLOR',
                'type' => 'COLOR',
                'value' => '#000000',
            ),
            array (
                'id' => 6,
                'key' => 'CURRENCY_COUNTRY_ID',
                'type' => 'CURRENCY',
                'value' => '231',
            ),
            array (
                'id' => 7,
                'key' => 'CURRENCY_POSITION',
                'type' => 'CURRENCY',
                'value' => 'left',
            ),
            array (
                'id' => 8,
                'key' => 'ONESIGNAL_API_KEY',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            array (
                'id' => 9,
                'key' => 'ONESIGNAL_REST_API_KEY',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            array (
                'id' => 10,
                'key' => 'DISTANCE_TYPE',
                'type' => 'DISTANCE',
                'value' => 'km',
            ),
            array (
                'id' => 11,
                'key' => 'DISTANCE_RADIOUS',
                'type' => 'DISTANCE',
                'value' => '50',
            ),
            array (
                'id' => 12,
                'key' => 'dashboard_setting',
                'type' => 'dashboard_setting',
                'value' => '{"Top_Cards":"top_card","Monthly_Revenue_card":"monthly_revenue_card","Top_Services_card":"top_service_card","New_Provider_card":"new_provider_card","Upcoming_Booking_card":"upcoming_booking_card","New_Customer_card":"new_customer_card"}',
            ),
            array (
                'id' => 13,
                'key' => 'provider_dashboard_setting',
                'type' => 'provider_dashboard_setting',
                'value' => '{"Top_Cards":"top_card","Monthly_Revenue_card":"monthly_revenue_card","Top_Services_card":"top_service_card","New_Provider_card":"new_provider_card","Upcoming_Booking_card":"upcoming_booking_card","New_Customer_card":"new_customer_card"}',
            ),
            array (
                'id' => 14,
                'key' => 'handyman_dashboard_setting',
                'type' => 'handyman_dashboard_setting',
                'value' => '{"Top_Cards":"top_card","Schedule_Card":"schedule_card"}',
            ),
            array (
                'id' => 15,
                'key' => 'ONESIGNAL_APP_ID_PROVIDER',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            array (
                'id' => 16,
                'key' => 'ONESIGNAL_REST_API_KEY_PROVIDER',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            array (
                'id' => 17,
                'key' => 'GOOGLE_MAP_KEY',
                'type' => 'GOOGLE_MAP_KEY',
                'value' => NULL,
            ),
            array (
                'id' => 18,
                'key' => 'JOB_START_DISTANCE',
                'type' => 'JOB_START_DISTANCE',
                'value' => 100,
            ),
        ));


    }
}
