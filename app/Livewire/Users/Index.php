<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $users;
    public $roles;
    
    // Properti untuk Modal Pengguna
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public array $userRoles = [];
    public $isUserModalOpen = false;

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function render()
    {
        $this->users = User::with('roles')->get();
        return view('livewire.users.index');
    }

    private function resetUserForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->userRoles = [];
    }
    
    // --- CRUD Pengguna ---
    public function createUser()
    {
        $this->resetUserForm();
        $this->isUserModalOpen = true;
    }

    public function editUser(User $user)
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; // Kosongkan password saat edit
        $this->password_confirmation = '';
        $this->userRoles = $user->roles->pluck('name')->toArray();
        $this->isUserModalOpen = true;
    }
    
    public function storeUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'userRoles' => 'required|array',
        ];

        // Tambahkan validasi password hanya saat membuat user baru atau saat password diisi
        if (!$this->userId || !empty($this->password)) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $this->validate($rules);
        
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $userData);
        $user->syncRoles($this->userRoles);

        session()->flash('message', $this->userId ? 'Pengguna berhasil diperbarui.' : 'Pengguna baru berhasil dibuat.');

        $this->closeUserModal();
    }

    public function closeUserModal()
    {
        $this->isUserModalOpen = false;
        $this->resetUserForm();
    }

    public function deleteUser(User $user)
    {
        // Jangan hapus user pertama (biasanya admin utama)
        if ($user->id === 1) {
            session()->flash('error', 'Tidak dapat menghapus admin utama.');
            return;
        }
        $user->delete();
        session()->flash('message', 'Pengguna berhasil dihapus.');
    }
}
