<?php

use Illuminate\Database\Seeder;
require_once(__DIR__.'/MyCsvSeeder.php');

class LeerkrachtSchoolSeeder extends MyCsvSeeder
{

    public function __construct()
    {
      $this->table = 'leerkracht_school';
      $this->filename = base_path().'/database/seeds/csvs/leerkracht_school.csv';
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
