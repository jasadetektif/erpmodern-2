<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
};
?>

<div class="relative min-h-screen bg-gradient-to-br from-green-100 via-emerald-200 to-green-100 overflow-hidden font-sans">

    <!-- Background Pattern -->
    <div class="absolute inset-0 z-0 opacity-80">
        <img src="https://www.transparenttextures.com/patterns/green-dust-and-scratches.png"
             alt="green pattern" class="w-full h-full object-cover" />
    </div>

    <!-- Animasi Awan -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
    </div>

    <!-- Panel Login -->
    <div class="relative z-10 flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md p-8 rounded-xl backdrop-blur-md bg-white/20 border border-white/30 shadow-2xl">

            <div class="text-center mb-4">
                <h1 class="text-3xl font-bold text-emerald-800">Arkavera ERP</h1>
                <p class="text-sm text-emerald-900">Solusi modern untuk manajemen proyek kontraktor</p>
            </div>

            <!-- Live Clock -->
            <div class="text-center text-emerald-700 font-mono mb-6 text-lg" id="live-clock">00:00:00</div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit.prevent="login" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm text-emerald-900">Email</label>
                    <input wire:model="form.email" id="email" type="email" required
                           class="mt-1 w-full px-4 py-3 border border-emerald-300 rounded-lg bg-white/90 focus:ring-emerald-500 focus:outline-none" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-red-500" />
                </div>

                <div>
                    <label for="password" class="block text-sm text-emerald-900">Password</label>
                    <input wire:model="form.password" id="password" type="password" required
                           class="mt-1 w-full px-4 py-3 border border-emerald-300 rounded-lg bg-white/90 focus:ring-emerald-500 focus:outline-none" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-red-500" />
                </div>

                <div class="flex items-center justify-between text-sm text-emerald-800">
                    <label class="flex items-center gap-2">
                        <input wire:model="form.remember" type="checkbox" class="text-emerald-600 rounded">
                        <span>Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-emerald-600 hover:underline">Lupa?</a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-md transition">
                    MASUK
                </button>
            </form>
        </div>
    </div>

    <!-- CSS Animasi Awan -->
    <style>
        .cloud {
            position: absolute;
            width: 200px;
            height: 100px;
            background-image: url('https://cdn-icons-png.flaticon.com/512/4005/4005801.png');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.2;
            animation: cloud-move 60s linear infinite;
        }

        .cloud-1 { top: 10%; left: -20%; animation-delay: 0s; }
        .cloud-2 { top: 30%; left: -30%; animation-delay: 10s; }
        .cloud-3 { top: 60%; left: -25%; animation-delay: 20s; }

        @keyframes cloud-move {
            0% { transform: translateX(0); }
            100% { transform: translateX(150vw); }
        }
    </style>

    <!-- Live Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const jam = now.getHours().toString().padStart(2, '0');
            const menit = now.getMinutes().toString().padStart(2, '0');
            const detik = now.getSeconds().toString().padStart(2, '0');
            document.getElementById("live-clock").innerText = `${jam}:${menit}:${detik}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</div>
