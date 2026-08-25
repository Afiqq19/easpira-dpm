<?php

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        return view('livewire.layout.sidebar');
    }
    
    /**
     * Check if a given route is active and return luxury active classes
     */
    public function isActive($route)
    {
        return request()->routeIs($route) 
            ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-bold shadow-md shadow-indigo-500/25 border border-indigo-400/20' 
            : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/80 hover:translate-x-1 font-semibold transition-all duration-200';
    }

    public function isActiveClass($route)
    {
        return $this->isActive($route);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }
}
