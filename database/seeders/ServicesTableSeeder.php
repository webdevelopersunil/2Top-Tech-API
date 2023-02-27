<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('services')->delete();
        \DB::table('services')->insert(array (
            array (
                'id' => 1,
                'name' => 'Plumber',
                'description' => 'plumber related data',
                'order' => '1',
                'status' => '1',
            ),

            array (
                'id' => 2,
                'name' => 'Tabs',
                'description' => 'Tabs related data',
                'order' => '2',
                'status' => '1',
            ),

            array (
                'id' => 3,
                'name' => 'Machanics',
                'description' => 'Machanics related data',
                'order' => '3',
                'status' => '1',
            ),
            array (
                'id' => 4,
                'name' => 'Repair',
                'description' => 'Repair related data',
                'order' => '4',
                'status' => '1',
            ),
            array (
                'id' => 5,
                'name' => 'Temp Jobs',
                'description' => 'Temp Jobs related data',
                'order' => '5',
                'status' => '1',
            ),
            array (
                'id' => 6,
                'name' => 'Maintenance',
                'description' => 'Maintenance related data',
                'order' => '6',
                'status' => '1',
            ),
            array (
                'id' => 7,
                'name' => 'Refrigeration',
                'description' => 'Refrigeration related data',
                'order' => '7',
                'status' => '1',
            ),
            array (
                'id' => 8,
                'name' => 'Heating Ventilation and Air Conditioning',
                'description' => 'Heating Ventilation and Air Conditioning related data',
                'order' => '8',
                'status' => '1',
            ),array (
                'id' => 9,
                'name' => 'Handyman',
                'description' => 'Handyman related data',
                'order' => '9',
                'status' => '1',
            ),array (
                'id' => 10,
                'name' => 'Electrician',
                'description' => 'Electrician related data',
                'order' => '10',
                'status' => '1',
            ),array (
                'id' => 11,
                'name' => 'Restaurant Equipment',
                'description' => 'Restaurant Equipment related data',
                'order' => '11',
                'status' => '1',
            ),array (
                'id' => 12,
                'name' => 'Specialist',
                'description' => 'Specialist related data',
                'order' => '12',
                'status' => '1',
            ),array (
                'id' => 13,
                'name' => 'Goods & Services',
                'description' => 'Goods & Services related data',
                'order' => '13',
                'status' => '1',
            ),
            array (
                'id' => 14,
                'name' => 'Equipment Repair',
                'description' => 'Equipment Repair (non refrigeration)',
                'order' => '14',
                'status' => '1',
            ),
            array (
                'id' => 15,
                'name' => 'Landscaping',
                'description' => 'Landscaping',
                'order' => '15',
                'status' => '1',
            ),
            array (
                'id' => 16,
                'name' => 'Refrigeration repair',
                'description' => 'Refrigeration repair',
                'order' => '16',
                'status' => '1',
            ),
            array (
                'id' => 17,
                'name' => 'Beverage Systems',
                'description' => 'Beverage Systems',
                'order' => '17',
                'status' => '1',
            ),
            array (
                'id' => 18,
                'name' => 'Plumbing',
                'description' => 'Plumbing',
                'order' => '18',
                'status' => '1',
            ),
            array (
                'id' => 19,
                'name' => 'HVAC',
                'description' => 'HVAC',
                'order' => '19',
                'status' => '1',
            ),
            array (
                'id' => 20,
                'name' => 'Electrical',
                'description' => 'Electrical',
                'order' => '20',
                'status' => '1',
            ),
            array (
                'id' => 21,
                'name' => 'Handyman',
                'description' => 'Handyman',
                'order' => '21',
                'status' => '1',
            ),
            array (
                'id' => 22,
                'name' => 'Carpentry',
                'description' => 'Carpentry',
                'order' => '22',
                'status' => '1',
            ),
            array (
                'id' => 23,
                'name' => 'Painting',
                'description' => 'Painting',
                'order' => '23',
                'status' => '1',
            ),
            array (
                'id' => 24,
                'name' => 'Tile/Flooring',
                'description' => 'Tile/Flooring',
                'order' => '24',
                'status' => '1',
            ),
            array (
                'id' => 25,
                'name' => 'Pest Control',
                'description' => 'Pest Control',
                'order' => '25',
                'status' => '1',
            ),
            array (
                'id' => 26,
                'name' => 'Hood Cleaning',
                'description' => 'Hood Cleaning',
                'order' => '26',
                'status' => '1',
            ),
            array (
                'id' => 27,
                'name' => 'Grease Traps',
                'description' => 'Grease Traps',
                'order' => '27',
                'status' => '1',
            ),
            array (
                'id' => 28,
                'name' => 'Deep Clean',
                'description' => 'Deep Clean',
                'order' => '28',
                'status' => '1',
            ),
            array (
                'id' => 29,
                'name' => 'Junk Removal',
                'description' => 'Junk Removal',
                'order' => '29',
                'status' => '1',
            ),
            array (
                'id' => 30,
                'name' => 'Cameras/Security',
                'description' => 'Cameras/Security',
                'order' => '30',
                'status' => '1',
            ),
            array (
                'id' => 31,
                'name' => 'Computer/IT',
                'description' => 'Computer/IT',
                'order' => '31',
                'status' => '1',
            ),
            array (
                'id' => 32,
                'name' => 'Glass',
                'description' => 'Glass',
                'order' => '32',
                'status' => '1',
            ),
            array (
                'id' => 33,
                'name' => 'Roofing',
                'description' => 'Roofing',
                'order' => '33',
                'status' => '1',
            ),
        ));
    }
}
