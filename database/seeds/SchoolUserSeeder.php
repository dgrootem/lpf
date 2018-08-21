<?php

use Illuminate\Database\Seeder;
require_once(__DIR__.'/MyCsvSeeder.php');

class SchoolUserSeeder extends MyCsvSeeder
{
    public function __construct()
    {
      $this->table = 'school_user';
      $this->filename = base_path().'/database/seeds/csvs/school_user.csv';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    // public function run()
    // {
    //     //
    // }
}
