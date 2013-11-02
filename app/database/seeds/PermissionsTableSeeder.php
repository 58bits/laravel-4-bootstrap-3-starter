<?php

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();

        //Permission 1
        $manageUsers = new Permission;
        $manageUsers->name = 'manage_users';
        $manageUsers->display_name = 'Manage Users';
        $manageUsers->save();
        

        //Permission 2
        $manageWidgets = new Permission;
        $manageWidgets->name = 'manage_widgets';
        $manageWidgets->display_name = 'Manage Widgets';
        $manageWidgets->save();

        DB::table('permission_role')->delete();

        //Role ID 1 and 2 are admin and user respectively.
        $permissions = array(
            array(
                'role_id'      => 1,
                'permission_id' => 1
            ),
            array(
                'role_id'      => 1,
                'permission_id' => 2
            ),            
            array(
                'role_id'      => 2,
                'permission_id' => 2
            ),
        );

        DB::table('permission_role')->insert( $permissions );
    }

}
