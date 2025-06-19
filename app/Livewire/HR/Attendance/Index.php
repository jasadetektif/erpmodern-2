<?php

namespace App\Livewire\HR\Attendance;

use Livewire\Component;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\ProjectTeam;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $projects;
    public $employees = [];

    public $selected_project_id;
    public $selected_date;

    public array $attendances = [];

    public function mount()
    {
        $this->projects = Project::all();
        $this->selected_date = date('Y-m-d');
    }

    // Fungsi ini akan berjalan setiap kali $selected_project_id atau $selected_date berubah
    public function updated($property)
    {
        if ($property === 'selected_project_id' || $property === 'selected_date') {
            $this->loadAttendanceData();
        }
    }

    public function loadAttendanceData()
    {
        if ($this->selected_project_id && $this->selected_date) {
            
            // 1. Ambil ID semua mandor yang ditugaskan ke proyek ini dari tabel project_teams
            $mandorIds = ProjectTeam::where('project_id', $this->selected_project_id)
                                    ->pluck('employee_id');

            // 2. Ambil semua karyawan yang:
            //    a. Ditugaskan langsung ke proyek ini (melalui kolom project_id)
            //    ATAU
            //    b. Merupakan mandor yang ditugaskan ke proyek ini (melalui tabel project_teams)
            $this->employees = Employee::where('status', 'Aktif')
                                        ->where(function ($query) use ($mandorIds) {
                                            $query->where('project_id', $this->selected_project_id)
                                                  ->orWhereIn('id', $mandorIds);
                                        })
                                        ->with('user')
                                        ->get();

            $existingAttendances = Attendance::where('project_id', $this->selected_project_id)
                                             ->where('date', $this->selected_date)
                                             ->get()
                                             ->keyBy('employee_id');

            $this->attendances = [];
            foreach ($this->employees as $employee) {
                $this->attendances[$employee->id] = [
                    'status' => $existingAttendances[$employee->id]->status ?? 'Hadir',
                    'notes' => $existingAttendances[$employee->id]->notes ?? '',
                ];
            }
        } else {
            $this->employees = [];
            $this->attendances = [];
        }
    }

    public function saveAttendances()
    {
        $this->validate([
            'selected_project_id' => 'required',
            'selected_date' => 'required|date',
        ]);

        if (empty($this->employees)) {
            session()->flash('error', 'Tidak ada karyawan untuk dicatat absensinya.');
            return;
        }

        DB::transaction(function () {
            foreach ($this->attendances as $employeeId => $data) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date' => $this->selected_date,
                    ],
                    [
                        'project_id' => $this->selected_project_id,
                        'status' => $data['status'],
                        'notes' => $data['notes'],
                    ]
                );
            }
        });

        session()->flash('message', 'Absensi untuk tanggal ' . $this->selected_date . ' berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.hr.attendance.index');
    }
}