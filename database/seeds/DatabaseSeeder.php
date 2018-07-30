<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SchoolTypeSeeder::class);
        $this->call(SchoolSeeder::class);
        $this->call(LeerkrachtSeeder::class);
        $this->call(StatusSeeder::class);
    }
}
