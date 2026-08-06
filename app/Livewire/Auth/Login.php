<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts::guest')]
class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:3')]
    public string $password = '';

    public string $error = '';

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password, 'status' => 'Activo'])) {
            session()->regenerate();
            $user = Auth::user();
            $route = match ($user->role) {
                'Chofer', 'Instalador' => 'driver.dashboard',
                'vigilancia' => 'gate.dashboard',
                default => 'dashboard',
            };
            return $this->redirect(route($route), navigate: true);
        }

        $this->error = 'Credenciales inválidas. Verifica tu correo y contraseña.';
    }

    public function clearError()
    {
        $this->error = '';
    }

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $route = match ($user->role) {
                'Chofer', 'Instalador' => 'driver.dashboard',
                'vigilancia' => 'gate.dashboard',
                default => 'dashboard',
            };
            $this->redirect(route($route), navigate: true);
        }
    }

    public function render()
    {
        $knownPasswords = [
            'admin@aguainmaculada.com' => 'admin123',
            'direccion@aguainmaculada.com' => 'dir123',
            'calidad@aguainmaculada.com' => 'calidad123',
            'logistica@aguainmaculada.com' => 'log123',
            'jefedistribucion@aguainmaculada.com' => 'dist123',
            'juan.perez@aguainmaculada.com' => 'chofer123',
            'luis.martinez@aguainmaculada.com' => 'instalador123',
        ];

        return view('livewire.login', [
            'testUsers' => \App\Domains\User\Models\User::where('status', 'Activo')->get()->map(fn($u) => [
                'email' => $u->email,
                'password' => $knownPasswords[$u->email] ?? '••••••',
                'role' => $u->role,
            ]),
        ]);
    }
}
