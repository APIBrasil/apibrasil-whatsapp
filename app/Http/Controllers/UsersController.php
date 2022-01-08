<?php

namespace App\Http\Controllers;

use App\Models\Sessions;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Yajra\DataTables\DataTables;
use Log;

class UsersController extends Controller
{
    public function index(Request $request)
    {

        try {

            if($request->user()->can('usuarios-todos')) {
                $users = User::orderBy('created_at', 'DESC')
                ->paginate(10);
            } else {
                $users = User::orderBy('created_at', 'DESC')
                ->where('id', $request->user()->id)
                ->paginate(10);

            }

            return view('users.index', compact('users'));

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function datatables(Request $request)
    {

        try {

            if($request->user()->can('usuarios-todos')) {
                $users = User::orderBy('created_at', 'DESC')
                ->with('roles');
            } else {
                $users = User::orderBy('created_at', 'DESC')
                ->with('roles')
                ->where('id', $request->user()->id);

            }

            return DataTables::of($users)->make(true);

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function create(Request $request)
    {

        $request->user()->can('usuarios-create') == false ? abort(403) : '';

        return view('users.create');
    }

    public function store(User $user, StoreUserRequest $request)
    {

        $request->user()->can('usuarios-store') == false ? abort(403) : '';

        $user->create(array_merge($request->validated(), [
            'password' => 'test'
        ]));

        return redirect()->route('users.index')
            ->withSuccess(__('User created successfully.'));
    }

    public function show(User $user, Request $request)
    {

        $request->user()->can('usuarios-show') == false ? abort(403) : '';

        return view('users.show', [
            'user' => $user
        ]);

    }

    public function edit(Request $request, $id)
    {
        try {

            $request->user()->can('usuarios-edit') == false ? abort(403) : '';
            $user = User::whereId($id)->firstOrFail();

            if( $request->user()->can('usuarios-grupos') ) {
                return view('users.edit', [
                    'user' => $user,
                    'userRole' => $user->roles->pluck('name')->toArray(),
                    'roles' => Role::get()
                ]);
            } else {

                if($id != $request->user()->id) {
                    Log::critical('Tentativa de hacking detectado: ' . $request->user()->id . ' tentou editar o usuário ' . $id);
                    return redirect()->route('users.index')->withErrors('Para de show! kkkkk');
                }

                $user = User::whereId($request->user()->id)->firstOrFail();

                return view('users.edit', [
                    'user' => $user,
                    'userRole' => $user->roles->pluck('name')->toArray(),
                    'roles' => $user->roles
                ]);

            }

        } catch (\Throwable $th) {

            Log::critical('Error editar usuario: ' . $th->getMessage());
            return redirect()->route('users.index')
                ->withError(__('User not found.'));
        }

    }

    public function update(User $user, UpdateUserRequest $request)
    {
        try {

            $request->user()->can('usuarios-update') == false ? abort(403) : '';

            $user->update($request->validated());
            $user->syncRoles($request->get('role'));

            return redirect()->route('users.index')
                ->with(['sucess', 'User updated successfully.']);

        } catch (\Throwable $th) {

            Log::critical('Error editar usuario: ' . $th->getMessage());
            return redirect()->route('users.index')
                ->with(['error' => $th->getMessage()]);
        }


    }

    public function destroy(User $user, Request $request)
    {
        try {

            $request->user()->can('usuarios-destroy') == false ? abort(403) : '';
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => __('User deleted successfully.')
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}


/*
git clone https://github.com/codeanddeploy/laravel8-authentication-example.git

if your using my previous tutorial navigate your project folder and run composer update

install packages

composer require spatie/laravel-permission
composer require laravelcollective/html

then run php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

php artisan migrate

php artisan make:migration create_posts_table

php artisan migrate

models
php artisan make:model Post

middleware
- create custom middleware
php artisan make:middleware PermissionMiddleware

register middleware
-

routes

controllers

- php artisan make:controller UsersController
- php artisan make:controller PostsController
- php artisan make:controller RolesController
- php artisan make:controller PermissionsController

requests
- php artisan make:request StoreUserRequest
- php artisan make:request UpdateUserRequest

blade files

create command to lookup all routes
- php artisan make:command CreateRoutePermissionsCommand
- php artisan permission:create-permission-routes

seeder for default roles and create admin user
php artisan make:seeder CreateAdminUserSeeder
php artisan db:seed --class=CreateAdminUserSeeder

*/
