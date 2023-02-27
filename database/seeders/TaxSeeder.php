<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('taxes')->delete();
        \DB::table('taxes')->insert(array (

            array (
                'title'          => 'Alabama',
                'type'   => 'percent',
                'value'         => 5,
                'status'        =>1,
            ),array (
                'title'          => 'Alaska',
                'type'   => 'percent',
                'value'         => 5,
                'status'        =>1,
            ),array (
                'title'          => 'California',
                'type'   => 'percent',
                'value'         => 5,
                'status'        =>1,
            ),
            array (
                'title'          => 'Idaho',
                'type'   => 'percent',
                'value'         => 6,
                'status'        =>1,
            ),array (
                'title'          => 'Iowa',
                'type'   => 'percent',
                'value'         => 6,
                'status'        =>1,
            ),array (
                'title'          => 'Indiana',
                'type'   => 'percent',
                'value'         => 7,
                'status'        =>1,
            ),array (
                'title'          => 'Maine',
                'type'   => 'percent',
                'value'         => 5,
                'status'        =>1,
            ),array (
                'title'          => 'Massachusetts',
                'type'   => 'percent',
                'value'         => 6,
                'status'        =>1,
            ),array (
                'title'          => 'Hawaii',
                'type'   => 'percent',
                'value'         => 4,
                'status'        =>1,
            ),array (
                'title'          => 'N.Y.',
                'type'   => 'percent',
                'value'         => 4,
                'status'        =>1,
            ),array (
                'title'          => 'Ohio',
                'type'   => 'percent',
                'value'         => 5,
                'status'        =>1,
            ),array (
                'title'          => 'Okla',
                'type'   => 'percent',
                'value'         => 4,
                'status'        =>1,
            ),array (
                'title'          => 'Tex',
                'type'   => 'percent',
                'value'         => 6,
                'status'        =>1,
            ),array (
                'title'          => 'Wash',
                'type'   => 'percent',
                'value'         => 6,
                'status'        =>1,
            ),

        ));
    }
}
