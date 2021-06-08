<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
        	[
	            'title' => 'Developer',
	            'code' => 'dev',
	            'rank' => '1',
	        ],
        	[
	            'title' => 'Administrator',
	            'code' => 'admin',
	            'rank' => '2',
	        ],
        ]);

        DB::table('users')->insert([
        	[
	            'name' => 'John',
	            'email' => 'a@a.a',
	            'password' => '$2y$10$S65MQ9JcHAxla/KX2MoMwumuBlzDPggSe3Es/ccCsg72GNCCkKv2q',
	            'role_id' => '1',
        	],
        	[
	            'name' => 'John2',
	            'email' => 'a2@a.a',
	            'password' => '$2y$10$S65MQ9JcHAxla/KX2MoMwumuBlzDPggSe3Es/ccCsg72GNCCkKv2q',
	            'role_id' => '2',
        	],
        	[
	            'name' => 'John3',
	            'email' => 'a3@a.a',
	            'password' => '$2y$10$S65MQ9JcHAxla/KX2MoMwumuBlzDPggSe3Es/ccCsg72GNCCkKv2q',
	            'role_id' => null,
        	]
        ]);

        DB::table('permissions')->insert([
        	[
	            'title' => 'Browse',
	            'code' => 'browse',
	        ],
        	[
	            'title' => 'Read',
	            'code' => 'read',
	        ],
	        [
	            'title' => 'Edit',
	            'code' => 'edit',
	        ],
	        [
	            'title' => 'Add',
	            'code' => 'add',
	        ],
	        [
	            'title' => 'Delete',
	            'code' => 'delete',
	        ],
        ]);

        DB::table('menu_items')->insert([
        	[
	            'title' => 'Dashboard',
	            'code' => 'dashboard',
	            'icon' => 'mdi-view-dashboard',
	            'parent_id' => null,
	            'url' => '/admin',
	        ],
        	[
	            'title' => 'Configuration',
	            'code' => 'config',
	            'icon' => 'mdi-settings',
	            'parent_id' => null,
	            'url' => null,
	        ],
	        [
	            'title' => 'Administration',
	            'code' => 'admin',
	            'icon' => 'mdi-account-multiple',
	            'parent_id' => null,
	            'url' => null,
	        ],
        	[
	            'title' => 'Menu Items',
	            'code' => 'menu_items',
	            'icon' => null,
	            'parent_id' => '2',
	            'url' => '/admin/menu_items',
	        ],
	        
        	[
	            'title' => 'Roles',
	            'code' => 'roles',
	            'icon' => null,
	            'parent_id' => '3',
	            'url' => '/admin/roles',
	        ],
	        [
	            'title' => 'Users',
	            'code' => 'users',
	            'icon' => null,
	            'parent_id' => '3',
	            'url' => '/admin/users',
	        ],
	        [
	            'title' => 'Management',
	            'code' => 'management',
	            'icon' => 'mdi-file-document',
	            'parent_id' => null,
	            'url' => null,
	        ],
        ]);

        DB::table('rmps')->insert([
        	[
	            'role_id' => '1',
	            'menu_item_id' => '1',
	            'permission_id' => '1',
	        ],
        	[
	            'role_id' => '1',
	            'menu_item_id' => '4',
	            'permission_id' => '1',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '4',
	            'permission_id' => '2',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '4',
	            'permission_id' => '3',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '4',
	            'permission_id' => '4',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '4',
	            'permission_id' => '5',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '5',
	            'permission_id' => '1',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '5',
	            'permission_id' => '2',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '5',
	            'permission_id' => '3',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '5',
	            'permission_id' => '4',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '5',
	            'permission_id' => '5',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '6',
	            'permission_id' => '1',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '6',
	            'permission_id' => '2',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '6',
	            'permission_id' => '3',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '6',
	            'permission_id' => '4',
	        ],
	        [
	            'role_id' => '1',
	            'menu_item_id' => '6',
	            'permission_id' => '5',
	        ],
        ]);
    }
}
