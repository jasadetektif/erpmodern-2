<?php

namespace App\Livewire\Procurement\Supplier;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $suppliers;
    public $supplierId;
    public $name, $contact_person, $phone, $email, $address;
    public $isModalOpen = false;

    public function render()
    {
        $this->suppliers = Supplier::all();
        return view('livewire.procurement.supplier.index');
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetForm()
    {
        $this->supplierId = null;
        $this->name = '';
        $this->contact_person = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Supplier::updateOrCreate(['id' => $this->supplierId], [
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ]);

        session()->flash('message', $this->supplierId ? 'Data pemasok berhasil diperbarui.' : 'Pemasok baru berhasil ditambahkan.');

        $this->closeModal();
        $this->resetForm();
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $id;
        $this->name = $supplier->name;
        $this->contact_person = $supplier->contact_person;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->address = $supplier->address;
        $this->openModal();
    }

    public function delete($id)
    {
        Supplier::find($id)->delete();
        session()->flash('message', 'Pemasok berhasil dihapus.');
    }
}
