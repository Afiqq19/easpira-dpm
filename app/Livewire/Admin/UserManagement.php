<?php

namespace App\Livewire\Admin;

use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';

    // Modal state
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    // Form fields
    public $user_id, $nama, $username, $nim, $email, $password, $role, $organisasi_id;
    public $is_active = true;

    // Data dropdown
    public $rolesList = [];
    public $organisasiList = [];
    public $userToDelete = null;

    public function mount()
    {
        $this->rolesList = Role::whereNotIn('name', ['admin', 'direktur', 'wakil_direktur'])->get();
        $this->organisasiList = Organisasi::active()->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query()
            ->with(['roles', 'organisasi'])
            ->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('nim', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        if ($this->roleFilter) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $users = $query->latest()->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $user = User::findOrFail($id);
        
        if ($user->hasRole('admin') || $user->hasRole('direktur') || $user->hasRole('wakil_direktur')) {
            session()->flash('error', 'Tidak dapat mengedit akun Admin atau Eksekutif.');
            return;
        }
        
        $this->user_id = $user->id;
        $this->nama = $user->nama;
        $this->username = $user->username;
        $this->nim = $user->nim;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
        $this->organisasi_id = $user->organisasi_id;
        $this->role = $user->roles->first()?->name;

        $this->isModalOpen = true;
    }

    public function save()
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'role' => 'required|exists:roles,name',
        ];

        if ($this->role === 'mahasiswa') {
            $rules['nim'] = 'required|string|max:20|unique:users,nim,' . $this->user_id;
        } else {
            $rules['username'] = 'required|string|max:50|unique:users,username,' . $this->user_id;
        }

        if (!$this->user_id) {
            $rules['password'] = 'required|min:8';
        }

        if (in_array($this->role, ['hmps', 'ukm'])) {
            $rules['organisasi_id'] = 'required|exists:organisasi,id';
        }

        $this->validate($rules, [
            'organisasi_id.required' => 'Pilih organisasi jika role adalah HMPS atau UKM.',
        ]);

        $data = [
            'nama' => $this->nama,
            'name' => $this->nama,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'nim' => $this->role === 'mahasiswa' ? $this->nim : null,
            'username' => $this->role !== 'mahasiswa' ? $this->username : null,
        ];

        if (in_array($this->role, ['hmps', 'ukm'])) {
            $data['organisasi_id'] = $this->organisasi_id;
        } else {
            $data['organisasi_id'] = null;
        }

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->user_id], $data);
        
        // Sync role
        $user->syncRoles([$this->role]);

        $this->isModalOpen = false;
        session()->flash('message', $this->user_id ? 'User berhasil diperbarui.' : 'User berhasil ditambahkan.');
    }

    public function confirmDelete($id)
    {
        $this->userToDelete = User::findOrFail($id);
        
        if ($this->userToDelete->hasRole('admin') || $this->userToDelete->hasRole('direktur') || $this->userToDelete->hasRole('wakil_direktur')) {
            session()->flash('error', 'Tidak dapat menghapus akun Admin atau Eksekutif.');
            return;
        }
        
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->userToDelete && !$this->userToDelete->hasRole('admin')) {
            $this->userToDelete->delete();
            $this->isDeleteModalOpen = false;
            session()->flash('message', 'User berhasil dihapus.');
        }
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->hasRole('admin') || $user->hasRole('direktur') || $user->hasRole('wakil_direktur')) {
            session()->flash('error', 'Tidak dapat menonaktifkan akun Admin atau Eksekutif.');
            return;
        }
        
        $user->is_active = !$user->is_active;
        $user->save();
        
        session()->flash('message', 'Status user berhasil diubah.');
    }

    private function resetFields()
    {
        $this->user_id = null;
        $this->nama = '';
        $this->username = '';
        $this->nim = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->organisasi_id = null;
        $this->is_active = true;
        $this->userToDelete = null;
    }
}
