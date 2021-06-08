<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Traits\ConstructsPages;
use App\Custom\Services\DataTableService as DataTable;
use App\Custom\Helpers\Helper;
use App\User;

class UserController extends Controller
{
    use ConstructsPages;

    public function browse(Request $request)
    {
        $rearranged = DataTable::rearrange($request->all());

        $result = User::browseAndCount($rearranged);
        $users = $result['records'];
        $auth_user = auth()->user();

        $data=[];

        foreach ($users as $user) {
            $data[] = [
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => isset($user->email_verified_at) ? $user->email_verified_at->format('Y-m-d') : 'Not verified',
                'role_title' => $user->role->title,
                'created_at' => isset($user->created_at) ? $user->created_at->format('Y-m-d') : 'n/a',
                'operations' => [
                    'edit' => ($auth_user->can('edit', $user)) ? route($this->route_name.'.edit', ['id' => $user->id]) : false,
                    'delete'=> ($auth_user->can('delete', $user)) ? route($this->route_name.'.delete', ['id' => $user->id]) : false,
                ],
            ];
        }

        return [
            'recordsTotal' => $result['records_total'],
            'recordsFiltered' => $result['records_filtered'],
            'data' => Helper::safe($data),
        ];
    }
}
