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
            'visualisatie' => 'bg-warning',
            'available' => 0,
            'choosable' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('statuses')->insert([
            'omschrijving' => 'RV',
            'visualisatie' => 'bg-success',
            'available' => 0,
            'choosable' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('statuses')->insert([
            'omschrijving' => 'opengesteld',
            'visualisatie' => 'bg-primary',
            'available' => 1,
            'choosable' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('statuses')->insert([
            'omschrijving' => 'geannuleerd',
            'visualisatie' => 'bg-secondary',
            'available' => 1,
            'choosable' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
