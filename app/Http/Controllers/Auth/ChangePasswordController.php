<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function change()
    {
    	return view('auth.passwords.change');
    }

    public function change_do(Request $request)
    {
    	$validated = $request->validate([
            'old_password' => 'bail|required|password',
            'password' => 'bail|required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $auth_user=auth()->user();
        $auth_user->password = $validated['password'];
        try {
        	$auth_user->save();
        } catch (QueryException $e) {
        	return back()->with('fail', 'Error occured.');
        }

    	return redirect()->route('home')->with('success', 'Password has been changed successfully.');
    }
}
