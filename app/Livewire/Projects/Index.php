<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $projects;
    public $projectId;
    public $name, $client, $start_date, $end_date, $budget, $status;

    public $isModalOpen = false;

    public function mount()
    {
        $this->projects = Project::all();
    }

    public function render()
    {
        $this->projects = Project::all();
        return view('livewire.projects.index');
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
        $this->projectId = null;
        $this->name = '';
        $this->client = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->budget = '';
        $this->status = 'Baru';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric',
        ]);

        Project::updateOrCreate(['id' => $this->projectId], [
            'name' => $this->name,
            'client' => $this->client,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'budget' => $this->budget,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->projectId ? 'Proyek berhasil diperbarui.' : 'Proyek berhasil ditambahkan.');

        $this->closeModal();
        $this->resetForm();
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $this->projectId = $id;
        $this->name = $project->name;
        $this->client = $project->client;
        $this->start_date = $project->start_date;
        $this->end_date = $project->end_date;
        $this->budget = $project->budget;
        $this->status = $project->status;

        $this->openModal();
    }

    public function delete($id)
    {
        Project::find($id)->delete();
        session()->flash('message', 'Proyek berhasil dihapus.');
    }
}
