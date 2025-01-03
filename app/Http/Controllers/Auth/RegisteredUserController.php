<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Interfaces\Auth\RegisteredUserRepositoryInterface;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    protected $registeredUserRepositoryInterface;

    public function __construct(RegisteredUserRepositoryInterface $registeredUserRepositoryInterface)
    {
        $this->registeredUserRepositoryInterface = $registeredUserRepositoryInterface;
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return  $this->registeredUserRepositoryInterface->create();
        // $roles = Role::all(); // Fetch all roles from the database
        // return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        return  $this->registeredUserRepositoryInterface->store($request);
        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        //     'role_id' => 'required|exists:roles,id',
        // ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'role_id' => $request->role_id,
        //     'password' => Hash::make($request->password),
        // ]);
        
        // $role = Role::find($request->role_id);
        // $user->assignRole($role->name);

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false));
    }
}
