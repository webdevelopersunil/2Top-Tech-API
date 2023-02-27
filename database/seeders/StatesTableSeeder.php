<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('states')->delete();

        \DB::table('states')->insert(array (

            array (
                'id' => 1,
                'name' => 'Alabama',
                'tax' => 10,
                'code_name'=>'AL',
                'country_id' => 231,
            ),

            array (
                'id' => 2,
                'name' => 'Alaska',
                'tax' => 10,
                'code_name'=>'AK',
                'country_id' => 231,
            ),

            array (
                'id' => 3,
                'name' => 'Arizona',
                'tax' => 10,
                'code_name'=>'AZ',
                'country_id' => 231,
            ),

            array (
                'id' => 4,
                'name' => 'Arkansas',
                'tax' => 10,
                'code_name'=>'AR',
                'country_id' => 231,
            ),

            array (
                'id' => 5,
                'name' => 'Byram',
                'tax' => 10,
                'code_name'=>'MS',
                'country_id' => 231,
            ),

            array (
                'id' => 6,
                'name' => 'California',
                'tax' => 10,
                'code_name'=>'CA',
                'country_id' => 231,
            ),

            array (
                'id' => 7,
                'name' => 'Cokato',
                'tax' => 10,
                'code_name'=>'MN',
                'country_id' => 231,
            ),

            array (
                'id' => 8,
                'name' => 'Colorado',
                'tax' => 10,
                'code_name'=>'CO',
                'country_id' => 231,
            ),

            array (
                'id' => 9,
                'name' => 'Connecticut',
                'tax' => 10,
                'code_name'=>'CT',
                'country_id' => 231,
            ),

            array (
                'id' => 10,
                'name' => 'Delaware',
                'tax' => 10,
                'code_name'=>'DE',
                'country_id' => 231,
            ),

            array (
                'id' => 11,
                'name' => 'District of Columbia',
                'tax' => 10,
                'code_name'=>'DC',
                'country_id' => 231,
            ),

            array (
                'id' => 12,
                'name' => 'Florida',
                'tax' => 10,
                'code_name'=>'FL',
                'country_id' => 231,
            ),

            array (
                'id' => 13,
                'name' => 'Georgia',
                'tax' => 10,
                'code_name'=>'GA',
                'country_id' => 231,
            ),

            array (
                'id' => 14,
                'name' => 'Hawaii',
                'tax' => 10,
                'code_name'=>'HI',
                'country_id' => 231,
            ),

            array (
                'id' => 15,
                'name' => 'Idaho',
                'tax' => 10,
                'code_name'=>'ID',
                'country_id' => 231,
            ),

            array (
                'id' => 16,
                'name' => 'Illinois',
                'tax' => 10,
                'code_name'=>'IL',
                'country_id' => 231,
            ),

            array (
                'id' => 17,
                'name' => 'Indiana',
                'tax' => 10,
                'code_name'=>'IN',
                'country_id' => 231,
            ),

            array (
                'id' => 18,
                'name' => 'Iowa',
                'tax' => 10,
                'code_name'=>'IA',
                'country_id' => 231,
            ),

            array (
                'id' => 19,
                'name' => 'Kansas',
                'tax' => 10,
                'code_name'=>'KS',
                'country_id' => 231,
            ),

            array (
                'id' => 20,
                'name' => 'Kentucky',
                'tax' => 10,
                'code_name'=>'KY',
                'country_id' => 231,
            ),

            array (
                'id' => 21,
                'name' => 'Louisiana',
                'tax' => 10,
                'code_name'=>'LA',
                'country_id' => 231,
            ),

            array (
                'id' => 22,
                'name' => 'Lowa',
                'tax' => 10,
                'code_name'=>'UI',
                'country_id' => 231,
            ),

            array (
                'id' => 23,
                'name' => 'Maine',
                'tax' => 10,
                'code_name'=>'ME',
                'country_id' => 231,
            ),

            array (
                'id' => 24,
                'name' => 'Maryland',
                'tax' => 10,
                'code_name'=>'MD',
                'country_id' => 231,
            ),

            array (
                'id' => 25,
                'name' => 'Massachusetts',
                'tax' => 10,
                'code_name'=>'MA',
                'country_id' => 231,
            ),

            array (
                'id' => 26,
                'name' => 'Medfield',
                'tax' => 10,
                'code_name'=>'MA',
                'country_id' => 231,
            ),

            array (
                'id' => 27,
                'name' => 'Michigan',
                'tax' => 10,
                'code_name'=>'MI',
                'country_id' => 231,
            ),

            array (
                'id' => 28,
                'name' => 'Minnesota',
                'tax' => 10,
                'code_name'=>'MN',
                'country_id' => 231,
            ),

            array (
                'id' => 29,
                'name' => 'Mississippi',
                'tax' => 10,
                'code_name'=>'MS',
                'country_id' => 231,
            ),

            array (
                'id' => 30,
                'name' => 'Missouri',
                'tax' => 10,
                'code_name'=>'MO',
                'country_id' => 231,
            ),

            array (
                'id' => 31,
                'name' => 'Montana',
                'tax' => 10,
                'code_name'=>'MT',
                'country_id' => 231,
            ),

            array (
                'id' => 32,
                'name' => 'Nebraska',
                'tax' => 10,
                'code_name'=>'NE',
                'country_id' => 231,
            ),

            array (
                'id' => 33,
                'name' => 'Nevada',
                'tax' => 10,
                'code_name'=>'NV',
                'country_id' => 231,
            ),

            array (
                'id' => 34,
                'name' => 'New Hampshire',
                'tax' => 10,
                'code_name'=>'NH',
                'country_id' => 231,
            ),

            array (
                'id' => 35,
                'name' => 'New Jersey',
                'tax' => 10,
                'code_name'=>'NJ',
                'country_id' => 231,
            ),

            array (
                'id' => 36,
                'name' => 'Wyoming',
                'tax' => 9,
                'code_name'=>'WY',
                'country_id' => 231,
            ),

            array (
                'id' => 37,
                'name' => 'New Mexico',
                'tax' => 10,
                'code_name'=>'NM',
                'country_id' => 231,
            ),

            array (
                'id' => 38,
                'name' => 'New York',
                'tax' => 10,
                'code_name'=>'NY',
                'country_id' => 231,
            ),

            array (
                'id' => 39,
                'name' => 'North Carolina',
                'tax' => 10,
                'code_name'=>'NC',
                'country_id' => 231,
            ),

            array (
                'id' => 40,
                'name' => 'North Dakota',
                'tax' => 10,
                'code_name'=>'ND',
                'country_id' => 231,
            ),

            array (
                'id' => 41,
                'name' => 'Ohio',
                'tax' => 10,
                'code_name'=>'OH',
                'country_id' => 231,
            ),

            array (
                'id' => 42,
                'name' => 'Oklahoma',
                'tax' => 10,
                'code_name'=>'OK',
                'country_id' => 231,
            ),

            array (
                'id' => 43,
                'name' => 'Ontario',
                'tax' => 10,
                'code_name'=>'CA',
                'country_id' => 231,
            ),

            array (
                'id' => 44,
                'name' => 'Oregon',
                'tax' => 10,
                'code_name'=>'OR',
                'country_id' => 231,
            ),

            array (
                'id' => 45,
                'name' => 'Pennsylvania',
                'tax' => 10,
                'code_name'=>'PA',
                'country_id' => 231,
            ),

            array (
                'id' => 46,
                'name' => 'Ramey',
                'tax' => 10,
                'code_name'=>'PA',
                'country_id' => 231,
            ),

            array (
                'id' => 47,
                'name' => 'Rhode Island',
                'tax' => 10,
                'code_name'=>'RI',
                'country_id' => 231,
            ),

            array (
                'id' => 48,
                'name' => 'South Carolina',
                'tax' => 10,
                'code_name'=>'SC',
                'country_id' => 231,
            ),

            array (
                'id' => 49,
                'name' => 'South Dakota',
                'tax' => 10,
                'code_name'=>'SD',
                'country_id' => 231,
            ),

            array (
                'id' => 50,
                'name' => 'Sublimity',
                'tax' => 10,
                'code_name'=>'OR',
                'country_id' => 231,
            ),

            array (
                'id' => 51,
                'name' => 'Tennessee',
                'tax' => 10,
                'code_name'=>'TN',
                'country_id' => 231,
            ),

            array (
                'id' => 52,
                'name' => 'Texas',
                'tax' => 10,
                'code_name'=>'TX',
                'country_id' => 231,
            ),

            array (
                'id' => 53,
                'name' => 'Trimble',
                'tax' => 10,
                'code_name'=>'TN',
                'country_id' => 231,
            ),

            array (
                'id' => 54,
                'name' => 'Utah',
                'tax' => 10,
                'code_name'=>'UH',
                'country_id' => 231,
            ),

            array (
                'id' => 55,
                'name' => 'Vermont',
                'tax' => 10,
                'code_name'=>'VT',
                'country_id' => 231,
            ),

            array (
                'id' => 56,
                'name' => 'Virginia',
                'tax' => 10,
                'code_name'=>'VA',
                'country_id' => 231,
            ),

            array (
                'id' => 57,
                'name' => 'Washington',
                'tax' => 10,
                'code_name'=>'WA',
                'country_id' => 231,
            ),

            array (
                'id' => 58,
                'name' => 'West Virginia',
                'tax' => 17,
                'code_name'=>'WV',
                'country_id' => 231,
            ),

            array (
                'id' => 59,
                'name' => 'Wisconsin',
                'tax' => 15,
                'code_name'=>'WI',
                'country_id' => 231,
            )
        ));


    }
}
