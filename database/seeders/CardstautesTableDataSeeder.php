<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardstautesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cardstautes')->insert([
            // 'id'=>0,
            'name'=>'متبقية',

        ]);
        DB::table('cardstautes')->insert([
            // 'id'=>1,
            'name'=>'مفعله',

        ]);

        DB::table('cardstautes')->insert([
            // 'id'=>2,
            'name'=>'المصدرة',

        ]);

        DB::table('cardstautes')->insert([
            // 'id'=>3,
            'name'=>'ملغية',

        ]);
      
    }
}
