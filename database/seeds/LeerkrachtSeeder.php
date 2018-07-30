<?php

use Illuminate\Database\Seeder;

require_once(__DIR__.'/MyCsvSeeder.php');

class LeerkrachtSeeder extends MyCsvSeeder
{

    public function __construct()
    {
      $this->table = 'leerkrachts';
      $this->filename = base_path().'/database/seeds/csvs/leerkrachten.csv';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
     /*
    public function run()
    {
        //
        DB::table('leerkrachts')->insert([
            'naam' => 'Alex Terieur',
            'ambt' => 'LS',
            'lestijden_per_week' => 15,
            'vaste_school_id' => 2,
            'actief' => 1
        ]);
    }
    */
}
