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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('statuses')->insert([
            'omschrijving' => 'RV',
            'visualisatie' => 'alert-success',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('statuses')->insert([
            'omschrijving' => 'opengesteld',
            'visualisatie' => 'alert-primary',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
