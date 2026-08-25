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
            ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold shadow-lg shadow-indigo-600/30 border border-indigo-400/30' 
            : 'text-slate-400 hover:bg-white/[0.07] hover:text-white hover:translate-x-1 transition-all duration-200';
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
