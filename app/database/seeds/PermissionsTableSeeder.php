<?php

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();

        $manageUsers = new Permission;
        $manageUsers->name = 'manage_users';
        $manageUsers->display_name = 'Manage Users';
        $manageUsers->save();
        

        $managePosts = new Permission;
        $managePosts->name = 'manage_media';
        $managePosts->display_name = 'Manage Media';
        $managePosts->save();

        DB::table('permission_role')->delete();

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
