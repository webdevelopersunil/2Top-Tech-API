<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StaticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('static_data')->delete();
        \DB::table('static_data')->insert(array (
            array (
                'id' => 1,
                'type' => 'plan_type',
                'label' => 'Unlimited',
                'value' => 'unlimited',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 2,
                'type' => 'plan_type',
                'label' => 'Limited',
                'value' => 'limited',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 3,
                'type' => 'plan_limit_type',
                'label' => 'Service',
                'value' => 'service',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 4,
                'type' => 'plan_limit_type',
                'label' => 'Handyman',
                'value' => 'handyman',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 5,
                'type' => 'plan_limit_type',
                'label' => 'Featured Service',
                'value' => 'featured_service',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 6,
                'type' => 'equipment_categories',
                'label' => 'Equipment Category',
                'value' => 'Air Conditioner',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 7,
                'type' => 'equipment_categories',
                'label' => 'Equipment Category',
                'value' => 'Washing Machine',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 8,
                'type' => 'equipment_categories',
                'label' => 'Equipment Category',
                'value' => 'Elevator',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 9,
                'type' => 'equipment_categories',
                'label' => 'Equipment Category',
                'value' => 'Furniture',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 10,
                'type' => 'equipment_categories',
                'label' => 'Equipment Category',
                'value' => 'Refrigerator',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
            array (
                'id' => 11,
                'type' => 'equipment_categories',
                'label' => 'Equipment Category',
                'value' => 'Microwave Oven',
                'created_at' => '2022-04-21 12:23:03',
                'updated_at' => '2022-04-21 12:23:03',
            ),
        ));
    }
}
