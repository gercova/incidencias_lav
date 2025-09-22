<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserValidate;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $users = User::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%");
            })
            ->latest()
            ->paginate(setting('pagination_limit'));

        return $users;
    }

    public function store(UserValidate $request){
        $validated = $request->validated();

        $item = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => bcrypt($validated['password']),
        ]);

        return response()->json(['item' => $item, 'success' => true, 'message' => 'Guardado exitosamente'], 200);
    }

    public function update(UserValidate $request, User $user){
        $validated = $request->validated();
        $item = $user->update([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => $validated['password'] ? bcrypt($validated['password']) : $user->password,
        ]);

        return response()->json(['item' => $item, 'success' => true, 'message' => 'Actualizado exitosamente'], 200);
    }

    public function destroy(User $user){
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Eliminado exitosamente'], 200);
    }

    public function changeRole(User $user){
        $user->update([
            'role' => request('role'),
        ]);

        return response()->json(['success' => true, 'message' => 'Rol actualizado'], 200);
    }

    public function bulkDelete(){
        User::whereIn('id', request('ids'))->delete();
        return response()->json(['message' => '¡Usuarios eliminados exitosamente!']);
    }
}
