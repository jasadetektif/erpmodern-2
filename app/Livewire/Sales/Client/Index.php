<?php

namespace App\Livewire\Sales\Client;

use Livewire\Component;
use App\Models\Client;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $clients;

    // Properti untuk Form
    public $clientId;
    public $client_name, $contact_person, $email, $phone, $address;
    public $isModalOpen = false;

    public function render()
    {
        $this->clients = Client::latest()->get();
        return view('livewire.sales.client.index');
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->clientId = null;
        $this->client_name = '';
        $this->contact_person = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
    }

    public function store()
    {
        $this->validate([
            'client_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email,' . $this->clientId,
            'phone' => 'nullable|string|max:20',
        ]);

        Client::updateOrCreate(['id' => $this->clientId], [
            'client_name' => $this->client_name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        session()->flash('message', $this->clientId ? 'Data Klien berhasil diperbarui.' : 'Klien baru berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit(Client $client)
    {
        $this->clientId = $client->id;
        $this->client_name = $client->client_name;
        $this->contact_person = $client->contact_person;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->address = $client->address;
        $this->openModal();
    }

    public function delete(Client $client)
    {
        $client->delete();
        session()->flash('message', 'Klien berhasil dihapus.');
    }
}
