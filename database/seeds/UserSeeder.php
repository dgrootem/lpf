<?php

use Illuminate\Database\Seeder;
require_once(__DIR__.'/MyCsvSeeder.php');

class UserSeeder extends MyCsvSeeder
{
    public function __construct()
    {
      $this->table = 'users';
      $this->filename = base_path().'/database/seeds/csvs/users.csv';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
     // public function run()
     // {
     //     //
     //     DB::table('users')->insert(
     //     array (
     //       0 =>
     //       array (
     //         'name' => 'Inge moïs',
     //         'email' => 'inge.mois@skbl.be',
     //       ),
     //       1 =>
     //       array (
     //         'name' => 'Same rely',
     //         'email' => 'sam.rely@skbl.be',
     //       ),
     //       2 =>
     //       array (
     //         'name' => 'Esther Wallace',
     //         'email' => 'esther.wallace@sanctamariabasisschool.be',
     //       ),
     //     )
     //   );
     // }
}
