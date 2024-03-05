<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = array(
			array('country_id' => 1,'code' => 'AP', 'name' => 'Andhra Pradesh'),
			array('country_id' => 1,'code' => 'AR', 'name' => 'Arunachal Pradesh'),
			array('country_id' => 1,'code' => 'AS', 'name' => 'Assam'),
			array('country_id' => 1,'code' => 'BR', 'name' => 'Bihar'),
			array('country_id' => 1,'code' => 'CG', 'name' => 'Chhattisgarh'),
			array('country_id' => 1,'code' => 'GA', 'name' => 'Goa'),
			array('country_id' => 1,'code' => 'GJ', 'name' => 'Gujarat'),
			array('country_id' => 1,'code' => 'HR', 'name' => 'Haryana'),
			array('country_id' => 1,'code' => 'HP', 'name' => 'Himachal Pradesh'),
			array('country_id' => 1,'code' => 'JK', 'name' => 'Jammu and Kashmir'),
			array('country_id' => 1,'code' => 'JH', 'name' => 'Jharkhand'),
			array('country_id' => 1,'code' => 'KA', 'name' => 'Karnataka'),
			array('country_id' => 1,'code' => 'KL', 'name' => 'Kerala'),
			array('country_id' => 1,'code' => 'MP', 'name' => 'Madhya Pradesh'),
			array('country_id' => 1,'code' => 'MH', 'name' => 'Maharashtra'),
			array('country_id' => 1,'code' => 'MN', 'name' => 'Manipur'),
			array('country_id' => 1,'code' => 'ML', 'name' => 'Meghalaya'),
			array('country_id' => 1,'code' => 'MZ', 'name' => 'Mizoram'),
			array('country_id' => 1,'code' => 'NL', 'name' => 'Nagaland'),
			array('country_id' => 1,'code' => 'OR', 'name' => 'Orissa'),
			array('country_id' => 1,'code' => 'PB', 'name' => 'Punjab'),
			array('country_id' => 1,'code' => 'RJ', 'name' => 'Rajasthan'),
			array('country_id' => 1,'code' => 'SK', 'name' => 'Sikkim'),
			array('country_id' => 1,'code' => 'UK', 'name' => 'Uttarakhand'),
			array('country_id' => 1,'code' => 'UP', 'name' => 'Uttar Pradesh'),
			array('country_id' => 1,'code' => 'WB', 'name' => 'West Bengal'),
			array('country_id' => 1,'code' => 'TN', 'name' => 'Tamil Nadu'),
			array('country_id' => 1,'code' => 'TR', 'name' => 'Tripura'),
			array('country_id' => 1,'code' => 'AN', 'name' => 'Andaman and Nicobar Islands'),
			array('country_id' => 1,'code' => 'CH', 'name' => 'Chandigarh'),
			array('country_id' => 1,'code' => 'DH', 'name' => 'Dadra and Nagar Haveli'),
			array('country_id' => 1,'code' => 'DD', 'name' => 'Daman and Diu'),
			array('country_id' => 1,'code' => 'DL', 'name' => 'Delhi'),
			array('country_id' => 1,'code' => 'LD', 'name' => 'Lakshadweep'),
			array('country_id' => 1,'code' => 'PY', 'name' => 'Pondicherry')
		);

        DB::table('states')->insert($states);
    }
}
