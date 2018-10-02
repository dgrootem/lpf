<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AmbtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('ambts')->insert([
            'naam' => 'LS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('ambts')->insert([
            'naam' => 'KS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('ambts')->insert([
            'naam' => 'BuBao',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
