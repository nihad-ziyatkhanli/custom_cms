<?php

use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run()
    {
        DB::table('menu_items')->insert([
            [
                'title' => 'Files',
                'code' => 'files',
                'icon' => null,
                'parent_id' => '7',
                'url' => '/admin/files',
            ],
            [
                'title' => 'Categories',
                'code' => 'categories',
                'icon' => null,
                'parent_id' => '7',
                'url' => '/admin/categories',
            ],
            [
                'title' => 'Posts',
                'code' => 'posts',
                'icon' => null,
                'parent_id' => '7',
                'url' => '/admin/posts',
            ],
            [
                'title' => 'Links',
                'code' => 'links',
                'icon' => null,
                'parent_id' => '7',
                'url' => '/admin/links',
            ],
        ]);

        DB::table('rmps')->insert([
            [
                'role_id' => '1',
                'menu_item_id' => '8',
                'permission_id' => '1',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '8',
                'permission_id' => '2',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '8',
                'permission_id' => '3',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '8',
                'permission_id' => '4',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '8',
                'permission_id' => '5',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '9',
                'permission_id' => '1',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '9',
                'permission_id' => '2',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '9',
                'permission_id' => '3',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '9',
                'permission_id' => '4',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '9',
                'permission_id' => '5',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '10',
                'permission_id' => '1',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '10',
                'permission_id' => '2',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '10',
                'permission_id' => '3',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '10',
                'permission_id' => '4',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '10',
                'permission_id' => '5',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '11',
                'permission_id' => '1',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '11',
                'permission_id' => '2',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '11',
                'permission_id' => '3',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '11',
                'permission_id' => '4',
            ],
            [
                'role_id' => '1',
                'menu_item_id' => '11',
                'permission_id' => '5',
            ],
        ]);

        DB::table('categories')->insert([
        	[
	            'title' => 'General',
	            'code' => 'general',
	        ],
        	[
	            'title' => 'News',
	            'code' => 'news',
	        ],
        ]);
    }
}
