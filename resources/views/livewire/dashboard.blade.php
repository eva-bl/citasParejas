<?php

use App\Actions\Plan\GetPlanOfTheYearAction;
use Livewire\Volt\Component;

new class extends Component
{
    public $planOfTheYear = null;

    public function mount(GetPlanOfTheYearAction $getPlanOfTheYear)
    {
        if (!auth()->user()->hasCouple()) {
            return $this->redirect(route('couple.setup'), navigate: true);
        }
        
        $this->planOfTheYear = $getPlanOfTheYear->execute(auth()->user()->couple);
    }
}; ?>

<div>
    <x-layouts.app :title="__('Dashboard')">
        <div>
            <h1 class="text-3xl font-bold text-white mb-4">
                {{ __('¡Hola') }}, {{ auth()->user()->name }}!
            </h1>
            <p class="text-white/90 text-lg mb-6">
                {{ __('Bienvenido a tu espacio de planes en pareja') }}
            </p>
        </div>
    </x-layouts.app>
</div>
