<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Custom\Traits\ConstructsPages;
use App\Custom\Helpers\Helper;
use App\Custom\Services\ValidationService as VS;

use App\Role;
use App\User;
use App\MenuItem;
use App\Permission;
use App\Link;
use App\File;
use App\Post;
use App\Category;
use Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    use ConstructsPages;

    public function index()
    {
    	echo '<a href="/admin/posts">posts</a>';

    	

    }
}
