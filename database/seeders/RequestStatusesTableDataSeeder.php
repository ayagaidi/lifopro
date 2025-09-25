<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestStatusesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('request_statuses')->insert([
            'name'=>'قيد الآنتظار',

        ]);
        DB::table('request_statuses')->insert([
            'name'=>'تم القبول',
        ]);
        DB::table('request_statuses')->insert([
            'name'=>'تم الرفض',

        ]);
       
        DB::table('request_statuses')->insert([
            'name'=>'تم الغاؤها',

        ]);
    }
}
