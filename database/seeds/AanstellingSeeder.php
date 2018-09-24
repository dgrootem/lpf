<?php

use Illuminate\Database\Seeder;
require_once(__DIR__.'/MyCsvSeeder.php');

class AanstellingSeeder extends MyCsvSeeder
{
    public function __construct()
    {
      $this->table = 'aanstellings';
      $this->filename = base_path().'/database/seeds/csvs/aanstelling.csv';
      DB::listen(function ($query) {
        var_dump($query->sql);
      });
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
