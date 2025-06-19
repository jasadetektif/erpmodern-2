<?php
namespace App\Livewire\Settings\Quote;
use Livewire\Component;
use App\Models\Quote;
use Livewire\Attributes\Layout;
#[Layout('components.layouts.app')]
class Index extends Component
{
    public $quotes;
    public $quoteId, $text, $is_active;
    public $isModalOpen = false;

    public function render()
    {
        $this->quotes = Quote::all();
        return view('livewire.settings.quote.index');
    }

    public function create() { $this->resetForm(); $this->openModal(); }
    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }
    private function resetForm() {
        $this->quoteId = null;
        $this->text = '';
        $this->is_active = true;
    }

    public function store()
    {
        $this->validate(['text' => 'required|string|unique:quotes,text,' . $this->quoteId]);
        Quote::updateOrCreate(['id' => $this->quoteId], ['text' => $this->text, 'is_active' => $this->is_active]);
        session()->flash('message', 'Kutipan berhasil disimpan.');
        $this->closeModal();
    }

    public function edit(Quote $quote) {
        $this->quoteId = $quote->id;
        $this->text = $quote->text;
        $this->is_active = $quote->is_active;
        $this->openModal();
    }

    public function delete(Quote $quote) {
        $quote->delete();
        session()->flash('message', 'Kutipan berhasil dihapus.');
    }
}
