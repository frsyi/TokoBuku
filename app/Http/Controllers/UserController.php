<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\User;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::where('id', '!=', '1')
        //     ->orderBy('name')
        // ->paginate(10);
        // ->simplePaginate(10);

        $search = request('search');
        if ($search) {
            $users = User::with('todos')->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
                ->where('id', '!=', '1')
                ->orderBy('name')
                ->paginate(10)
                // ->simplePaginate(10)
                ->withQueryString();
        } else {
            $users = User::with('todos')->where('id', '!=', '1')
                ->orderBy('name')
                ->paginate(10);
            // ->simplePaginate(10);
            // ->cursorPaginate(10);
        }

        // dd($users->toArray());
        return view('user.index', compact('users'));
    }
}
