<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Company_user_permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('company_user_permissions')->insert([
        //     'id' =>1,
        //     'name' => 'صلاحية ادارة المكاتب',
        // ]);
        // DB::table('company_user_permissions')->insert([
        //     'name' => 'صلاحية طلب البطاقات',
        // ]);
     
        // DB::table('company_user_permissions')->insert([
        //     'name' => 'صلاحية عرض البطاقات',
        // ]);
     
        // DB::table('company_user_permissions')->insert([
        //     'name' => 'صلاحية اصدار وثيقة',
        // ]);
        // DB::table('company_user_permissions')->insert([
        //     'name' => 'صلاحية ادارة التوزيع ',
        // ]);
        // DB::table('company_user_permissions')->insert([
        //     'name' => 'صلاحية ادارة التقارير ',
        // ]);
        DB::table('company_user_permissions')->insert([
            'name' => 'صلاحية ادارة الراجعات  ',
        ]);
    }
}
