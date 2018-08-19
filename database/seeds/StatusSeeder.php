<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('statuses')->insert([
            'omschrijving' => 'ZT',
            'visualisatie' => 'alert-warning',
            'available' => 0,
            'choosable' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('statuses')->insert([
            'omschrijving' => 'RV',
            'visualisatie' => 'alert-success',
            'available' => 0,
            'choosable' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('statuses')->insert([
            'omschrijving' => 'opengesteld',
            'visualisatie' => 'alert-primary',
            'available' => 1,
            'choosable' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('statuses')->insert([
            'omschrijving' => 'geannuleerd',
            'visualisatie' => 'alert-secondary',
            'available' => 1,
            'choosable' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
