<?php

namespace App\Livewire\Settings\Announcement;

use Livewire\Component;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $announcements;

    // Properti untuk Form
    public $announcementId;
    public $title, $content;
    public $is_active = true;
    public $isModalOpen = false;

    public function render()
    {
        $this->announcements = Announcement::with('user')->latest()->get();
        return view('livewire.settings.announcement.index');
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
        $this->announcementId = null;
        $this->title = '';
        $this->content = '';
        $this->is_active = true;
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Announcement::updateOrCreate(['id' => $this->announcementId], [
            'title' => $this->title,
            'content' => $this->content,
            'is_active' => $this->is_active,
            'user_id' => Auth::id(),
        ]);

        session()->flash('message', $this->announcementId ? 'Pengumuman berhasil diperbarui.' : 'Pengumuman baru berhasil dibuat.');
        $this->closeModal();
    }

    public function edit(Announcement $announcement)
    {
        $this->announcementId = $announcement->id;
        $this->title = $announcement->title;
        $this->content = $announcement->content;
        $this->is_active = $announcement->is_active;
        $this->openModal();
    }

    public function delete(Announcement $announcement)
    {
        $announcement->delete();
        session()->flash('message', 'Pengumuman berhasil dihapus.');
    }
}