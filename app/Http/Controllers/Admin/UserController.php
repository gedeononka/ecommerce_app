<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

   public function edit(User $user)
{
    $roles = Role::all(); // récupère tous les rôles Spatie
    return view('admin.users.edit', compact('user', 'roles'));
}

   public function update(Request $request, User $user)
{
    // Validation des champs autorisés à modifier
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    ]);

    // Mise à jour des champs
    $user->name = $validated['name'];
    $user->email = $validated['email'];

    // Si le mot de passe est renseigné, on le met à jour
    if (!empty($validated['password'])) {
        $user->password = $validated['password']; // Le cast 'hashed' dans le modèle va automatiquement hasher
    }

    $user->save();

    return redirect()->route('admin.users.show', $user)
                     ->with('success', 'Informations utilisateur mises à jour avec succès.');
}




}
