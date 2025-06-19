<?php
    namespace App\Livewire\Master\Analysis;
    use Livewire\Component;
    use App\Models\UnitPriceAnalysis;
    use App\Models\Material;
    use App\Models\Labor;
    use Illuminate\Support\Facades\DB;
    use Livewire\Attributes\Layout;

    #[Layout('components.layouts.app')]
    class Form extends Component
    {
        public ?UnitPriceAnalysis $analysis = null;
        public $analysisId;
        public $name, $unit, $total_cost = 0;
        public array $items = [];
        public $allMaterials, $allLabors;

        public function mount($analysis = null)
{
    $this->allMaterials = Material::orderBy('name')->get();
    $this->allLabors = Labor::orderBy('name')->get();

    if ($analysis) {
        $model = UnitPriceAnalysis::with('items.analyzable')->findOrFail($analysis);
        $this->analysis = $model;
        $this->analysisId = $model->id;
        $this->name = $model->name;
        $this->unit = $model->unit;

        foreach ($model->items as $item) {
            $this->items[] = [
                'type' => $item->analyzable_type === Material::class ? 'material' : 'labor',
                'id' => $item->analyzable_id,
                'coefficient' => $item->coefficient,
            ];
        }
    }

    $this->calculateTotal();
}


        public function addMaterial() { $this->items[] = ['type' => 'material', 'id' => '', 'coefficient' => 0]; }
        public function addLabor() { $this->items[] = ['type' => 'labor', 'id' => '', 'coefficient' => 0]; }
        public function removeItem($index) { unset($this->items[$index]); $this->items = array_values($this->items); $this->calculateTotal(); }
        public function updatedItems() { $this->calculateTotal(); }

        public function calculateTotal()
        {
            $total = 0;
            foreach ($this->items as $item) {
                $total += $this->calculateSubtotal($item);
            }
            $this->total_cost = $total;
        }

        public function getUnitPrice($type, $id)
        {
            if (empty($id)) return 'Rp 0';
            $model = $type === 'material' ? $this->allMaterials->find($id) : $this->allLabors->find($id);
            $price = $type === 'material' ? $model?->price : $model?->wage;
            return 'Rp ' . number_format($price ?? 0, 2, ',', '.');
        }

        public function calculateSubtotal($item)
        {
            if (empty($item['id']) || !is_numeric($item['coefficient'])) return 0;
            $model = $item['type'] === 'material' ? $this->allMaterials->find($item['id']) : $this->allLabors->find($item['id']);
            $price = $item['type'] === 'material' ? $model?->price : $model?->wage;
            return ($price ?? 0) * $item['coefficient'];
        }

        public function save()
        {
            $this->validate([
                'name' => 'required|string|max:255|unique:unit_price_analyses,name,' . $this->analysisId,
                'unit' => 'required|string|max:50',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required',
                'items.*.coefficient' => 'required|numeric|min:0.0001',
            ]);

            DB::transaction(function() {
                $analysis = UnitPriceAnalysis::updateOrCreate(['id' => $this->analysisId], [
                    'name' => $this->name, 'unit' => $this->unit, 'total_cost' => $this->total_cost
                ]);
                $analysis->items()->delete();
                foreach($this->items as $item) {
                    $model = $item['type'] === 'material' ? Material::class : Labor::class;
                    $analysis->items()->create([
                        'analyzable_type' => $model, 'analyzable_id' => $item['id'], 'coefficient' => $item['coefficient']
                    ]);
                }
            });
            session()->flash('message', 'Analisa Harga Satuan berhasil disimpan.');
            return $this->redirect(route('master.ahs.index'));
        }

        public function render() { return view('livewire.master.analysis.form'); }
    }
    