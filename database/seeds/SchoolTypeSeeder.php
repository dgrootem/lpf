<?php

use Illuminate\Database\Seeder;

class SchoolTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('school_types')->insert([
            'naam' => 'BaO',
            'noemer' => '24'
        ]);
        DB::table('school_types')->insert([
            'naam' => 'BuO',
            'noemer' => '22'
        ]);
        DB::table('school_types')->insert([
            'naam' => 'Special',
            'noemer' => '1'
        ]);

    }
}
