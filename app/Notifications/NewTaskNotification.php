<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task; // <-- Import model Task

class NewTaskNotification extends Notification
{
    use Queueable;

    public $task; // Properti untuk menyimpan data tugas

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        // Kita hanya akan menyimpan notifikasi ke database untuk saat ini
        return ['database'];
    }

    // Format data yang akan disimpan di database
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'project_id' => $this->task->project_id,
            'message' => 'Tugas baru "' . $this->task->name . '" telah ditambahkan ke proyek Anda.'
        ];
    }
}