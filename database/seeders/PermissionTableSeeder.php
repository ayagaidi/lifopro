<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

            
            
            'user-list',
            'region-list',
            'cities-list',
            'company-list',
            'offices-list',
            'cardrequests-list',
            'card-list',
            'price-list',
            'api-list',
            'car-list',
            'country-list',
            'countrycon-list',
            'vehiclenationalities-list',
            'insurance_clause-list',
            'purposeofuses-list',
            'report-list',
            'activity-list',
            'role-list',
            'card-field-visibility-list',

'companySummary',
          
          'reortstatic'
            



















        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
