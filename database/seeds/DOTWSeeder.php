<?php

use Illuminate\Database\Seeder;

class DOTWSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('dotws')->insert(['naam' => 'ma']);
        DB::table('dotws')->insert(['naam' => 'di']);
        DB::table('dotws')->insert(['naam' => 'wo']);
        DB::table('dotws')->insert(['naam' => 'do']);
        DB::table('dotws')->insert(['naam' => 'vr']);
    }
}
