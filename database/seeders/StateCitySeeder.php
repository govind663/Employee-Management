<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class StateCitySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        City::truncate();
        State::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $maharashtra = State::create(['name' => 'Maharashtra']);
        $gujarat     = State::create(['name' => 'Gujarat']);
        $rajasthan   = State::create(['name' => 'Rajasthan']);
        $karnataka   = State::create(['name' => 'Karnataka']);

        City::create(['state_id' => $maharashtra->id, 'name' => 'Mumbai']);
        City::create(['state_id' => $maharashtra->id, 'name' => 'Pune']);
        City::create(['state_id' => $maharashtra->id, 'name' => 'Nagpur']);
        City::create(['state_id' => $maharashtra->id, 'name' => 'Nashik']);

        City::create(['state_id' => $gujarat->id, 'name' => 'Ahmedabad']);
        City::create(['state_id' => $gujarat->id, 'name' => 'Surat']);
        City::create(['state_id' => $gujarat->id, 'name' => 'Vadodara']);
        City::create(['state_id' => $gujarat->id, 'name' => 'Rajkot']);

        City::create(['state_id' => $rajasthan->id, 'name' => 'Jaipur']);
        City::create(['state_id' => $rajasthan->id, 'name' => 'Udaipur']);
        City::create(['state_id' => $rajasthan->id, 'name' => 'Jodhpur']);

        City::create(['state_id' => $karnataka->id, 'name' => 'Bangalore']);
        City::create(['state_id' => $karnataka->id, 'name' => 'Mysore']);
        City::create(['state_id' => $karnataka->id, 'name' => 'Mangalore']);
    }
}
