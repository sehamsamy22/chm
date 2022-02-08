<?php
namespace App\Modules\Permission\Database\Seeders;

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionDatabaseSeeder extends Seeder
{

    private $permissions;

    public function __construct()
    {
        $this->permissions = Permission::all();
    }

    private function defaultPermissions()
    {
        return [
            '-list' => 'عرض ',
            '-create' => 'إضافة',
            '-edit' => 'التعديل ',
            '-delete' => 'حذف ',
        ];
    }

    private function addAndExceptPermissions($module)
    {
        $defaultPermissions = $this->defaultPermissions();
        if (array_key_exists('addPermissions', $module)) {
            foreach ($module['addPermissions'] as $key => $permission) {
                $defaultPermissions['-' . $key] = $permission;
            }
        }
        if (array_key_exists('exceptPermissions', $module)) {
            foreach ($module['exceptPermissions'] as $permission) {
                unset($defaultPermissions[$permission]);
            }
        }
        return $defaultPermissions;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(FirstRoleTableSeeder::class);
        $role = Role::find(1);
        foreach (config('permission_modules') as $module) {
            $defaultPermissions = $this->addAndExceptPermissions($module);
            foreach ($defaultPermissions as $key => $val) {
                if (!$this->permissions->where('name', $module['name'] . $key)->count()) {
                    Permission::create([
                        'name' => $module['name'] . $key,
                        'guard_name' => 'admin-api',
                        'slug' => $val,
                        'order_by' => $module['name'] . 'Module'
                    ]);
                    $role->givePermissionTo($module['name'] . $key);
                    echo $module['name'] . $key . "\n";
                }
            }
        }
    }

}
