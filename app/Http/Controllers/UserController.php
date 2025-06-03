<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {

        if (request()->ajax()) {
            $users = User::where('role_id', 2)->get();
            return DataTables::of($users)
                ->addColumn('action', function ($users) {
                    return '<div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$users->id.'" data-original-title="Edit" class="edit btn btn-primary btn-xs">Edit</a>
                                    &nbsp;
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$users->id.'" data-original-title="Delete" class="delete btn btn-danger btn-xs">Delete</a>
                                </div>';
                })
                ->editColumn('created_at', function ($users) {
                    return $users->created_at->diffForHumans();
                })
                ->editColumn('updated_at', function ($users) {
                    return $users->updated_at ? $users->updated_at->diffForHumans() : '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Users.index');
    }

    public function create()
    {
        return view('Users.create'); // tidak perlu kirim data roles
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
        ]);

        $user->assignRole('admin'); // langsung assign role admin

        return redirect()->route('users.index')->with('success', 'Admin created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('Users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Admin updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'Admin deleted successfully.']);
    }

    public function getUser(){

        if(request()->ajax()){
            $users = User::where('role_id', 2)->get();
            return DataTables::of($users)
                ->addColumn('action', function ($users) {
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$users->id.'" data-original-title="Edit" class="edit btn btn-primary btn-xs">Edit</a>';
                })
                ->make(true);
        }

        return view('Users.index');
    }
}
