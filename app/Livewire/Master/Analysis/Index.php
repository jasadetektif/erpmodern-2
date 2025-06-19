<?php
namespace App\Livewire\Master\Analysis;
use Livewire\Component;
use App\Models\UnitPriceAnalysis;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public function delete(UnitPriceAnalysis $analysis) {
        $analysis->delete();
        session()->flash('message', 'Analisa berhasil dihapus.');
    }

    public function render() {
        $analyses = UnitPriceAnalysis::withCount('items')->latest()->get();
        return view('livewire.master.analysis.index', ['analyses' => $analyses]);
    }
}
