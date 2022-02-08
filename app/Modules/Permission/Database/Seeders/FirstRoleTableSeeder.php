<?php
namespace App\Modules\Permission\Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class FirstRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $gMRole = Role::where('name', 'generalManager')->first();
        if(!$gMRole) {
            Role::create([
                'name' => 'GeneralManager',
                'guard_name' => 'admin-api',
                'slug' => 'المدير العام'
            ]);

            DB::table('model_has_roles')->insert([
                'role_id' => 1,
                'model_type' => Admin::class,
                'model_id' => 1
            ]);
        }
    }
}
