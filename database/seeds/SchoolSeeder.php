<?php

use Illuminate\Database\Seeder;
use Flynsarmy\CsvSeeder\CsvSeeder;
require_once(__DIR__.'/MyCsvSeeder.php');
class SchoolSeeder extends MyCsvSeeder
{
    public function __construct()
    {
      $this->table = 'schools';
      $this->filename = base_path().'/database/seeds/csvs/schools.csv';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    /*public function run()
    {
        //
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($this->table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        parent::run();
    }*/
}
