<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(PermissionTableSeeder::class);

        // $this->call(Office_user_permissions::class);
        $this->call(Company_user_permissions::class);
        //  $this->call(CreateAdminUserSeeder::class);
        //  $this->call(User_Type_TableDataSeeder::class);
        //  $this->call(RequestStatusesTableDataSeeder::class);
        //  $this->call(CardstautesTableDataSeeder::class);


    }
}
