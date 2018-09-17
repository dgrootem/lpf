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
        DB::table('dotws')->insert(['volgorde' => 1 ,'naam' => 'ma']);
        DB::table('dotws')->insert(['volgorde' => 2 ,'naam' => 'di']);
        DB::table('dotws')->insert(['volgorde' => 3 ,'naam' => 'wo']);
        DB::table('dotws')->insert(['volgorde' => 4 ,'naam' => 'do']);
        DB::table('dotws')->insert(['volgorde' => 5 ,'naam' => 'vr']);
    }
}
