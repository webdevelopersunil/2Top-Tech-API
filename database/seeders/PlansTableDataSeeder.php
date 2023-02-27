<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PlansTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('plans')->delete();
        \DB::table('plans')->insert(array (
            array (
                'id' => 1,
                'title' => 'Basic plan',
                'identifier' => 'basic',
                'type' => 'Monthly',
                'amount' => 99.00,
                'trial_period' => 7,
                'status' => 1,
                'plan_price_id'=>env('MONTHLY_PLAN_ID'),
                'created_at' => '2022-03-10 11:26:15',
                'updated_at' => '2022-03-10 11:26:15',
            ),
            array (
                'id' => 2,
                'title' => 'Basic plan',
                'identifier' => 'basic',
                'type' => 'Yearly',
                'amount' => 999.00,
                'trial_period' => NULL,
                'status' => 1,
                'plan_price_id'=>env('YEARLY_PLAN_ID'),
                'created_at' => '2022-03-10 11:26:15',
                'updated_at' => '2022-03-10 11:26:15',
            )
        ));
    }
}
