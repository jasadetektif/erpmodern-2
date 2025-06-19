<?php

namespace App\Livewire\Projects;

use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\ProjectTeam;
use App\Models\Rab;
use App\Models\Task;
use App\Models\UnitPriceAnalysis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class Show extends Component
{
    use WithFileUploads;

    public Project $project;
    public $tasks;
    public $activeTab = 'overview';

    // Properti Keuangan
    public $totalExpenses = 0;
    public $profitOrLoss = 0;
    public $budgetUsagePercentage = 0;

    // Properti Manajemen Tim
    public $mandorList;
    public $team_employee_id, $team_number_of_workers, $team_worker_daily_wage;
    public $team_payment_type = 'harian', $team_lump_sum_value, $team_work_progress;
    public $isTeamModalOpen = false;

    // Modal Edit Tim
    public ?ProjectTeam $editingTeam = null;
    public $isTeamEditModalOpen = false;

    // Properti form tugas
    public $taskId;
    public $task_name, $task_description, $task_status, $task_start_date, $task_end_date, $task_progress;
    public $isTaskModalOpen = false;

    // Properti Gantt Chart
    public array $ganttTasks = [];

    // Properti unggah dokumen
    public $document;

    // Properti Pengeluaran Lain-lain
    public $expense_description, $expense_amount;
    public $isExpenseModalOpen = false;

    // Properti untuk RAB
    public $rab;
    public array $rabItems = [];
    public $totalRabAmount = 0;
    public $analysisList;


    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function mount(Project $project)
    {
        $this->project = $project->load('teams.employee.user', 'documents.uploadedBy', 'otherExpenses.user', 'rab.items');
        $this->analysisList = UnitPriceAnalysis::orderBy('name')->get();
        $this->calculateFinancials();

        $assignedMandorIds = $this->project->teams->pluck('employee_id');
        $this->mandorList = Employee::where('position', 'Mandor')
            ->whereNotIn('id', $assignedMandorIds)
            ->get();
        
        $this->loadRabData();
        $this->resetTeamForm(); // Panggil saat mount untuk memastikan nilai default
    }

    public function calculateFinancials()
    {
        $this->project->refresh();
        $materialExpenses = $this->project->purchaseOrders()->sum('total_amount');
        $laborExpenses = $this->project->total_labor_cost;
        $otherExpenses = $this->project->otherExpenses()->sum('amount');
        
        $this->totalExpenses = $materialExpenses + $laborExpenses + $otherExpenses;
        $this->profitOrLoss = $this->project->budget - $this->totalExpenses;

        $this->budgetUsagePercentage = $this->project->budget > 0
            ? ($this->totalExpenses / $this->project->budget) * 100
            : 0;
    }

    public function render()
    {
        $currentTasks = $this->project->tasks()->get();
        $this->tasks = $currentTasks;

        if ($currentTasks) {
            $this->ganttTasks = $currentTasks->whereNotNull('start_date')->whereNotNull('end_date')->map(function ($task) {
                return [
                    'id' => (string) $task->id,
                    'name' => $task->name,
                    'start' => $task->start_date,
                    'end' => $task->end_date,
                    'progress' => $task->progress,
                ];
            })->toArray();
        } else {
            $this->ganttTasks = [];
        }

        return view('livewire.projects.show');
    }

    // --- Manajemen Tim ---
    public function openTeamModal() { $this->resetTeamForm(); $this->isTeamModalOpen = true; }
    public function closeTeamModal() { $this->isTeamModalOpen = false; }

    private function resetTeamForm()
    {
        $this->team_employee_id = '';
        $this->team_number_of_workers = 0; // Set default to 0
        $this->team_worker_daily_wage = 0; // Set default to 0
        $this->team_payment_type = 'harian';
        $this->team_lump_sum_value = 0; // Set default to 0
        $this->team_work_progress = 0; // Set default to 0
    }

    public function assignMandor()
    {
        $validationRules = [
            'team_employee_id' => 'required|exists:employees,id',
            'team_payment_type' => 'required|in:harian,borongan',
        ];

        // Reset values of the unused payment type to 0 before validation and saving
        if ($this->team_payment_type == 'harian') {
            $validationRules['team_number_of_workers'] = 'required|integer|min:1';
            $validationRules['team_worker_daily_wage'] = 'required|numeric|min:0';
            $this->team_lump_sum_value = 0; // Set to 0 if not used
            $this->team_work_progress = 0; // Set to 0 if not used
        } else { // 'borongan'
            $validationRules['team_lump_sum_value'] = 'required|numeric|min:1';
            $validationRules['team_work_progress'] = 'required|integer|min:0|max:100';
            $this->team_number_of_workers = 0; // Set to 0 if not used
            $this->team_worker_daily_wage = 0; // Set to 0 if not used
        }

        $this->validate($validationRules);

        ProjectTeam::create([
            'project_id' => $this->project->id,
            'employee_id' => $this->team_employee_id,
            'number_of_workers' => $this->team_number_of_workers,
            'worker_daily_wage' => $this->team_worker_daily_wage,
            'payment_type' => $this->team_payment_type,
            'lump_sum_value' => $this->team_lump_sum_value,
            'work_progress' => $this->team_work_progress,
        ]);

        session()->flash('message', 'Mandor berhasil ditugaskan ke proyek.');
        $this->closeTeamModal();
        $this->calculateFinancials();
    }

    public function removeMandor($teamId)
    {
        ProjectTeam::find($teamId)?->delete();
        session()->flash('message', 'Tugas mandor berhasil dihapus dari proyek.');
        $this->calculateFinancials();
    }

    public function editTeam(ProjectTeam $team)
    {
        $this->editingTeam = $team;
        // Ensure values are set to 0 if they might be null from DB or previous state
        $this->editingTeam->number_of_workers = $team->number_of_workers ?? 0;
        $this->editingTeam->worker_daily_wage = $team->worker_daily_wage ?? 0;
        $this->editingTeam->lump_sum_value = $team->lump_sum_value ?? 0;
        $this->editingTeam->work_progress = $team->work_progress ?? 0;

        $this->isTeamEditModalOpen = true;
    }

    public function updateTeam()
    {
        $validationRules = [
            'editingTeam.payment_type' => 'required|in:harian,borongan',
        ];

        // Atur nilai properti yang tidak digunakan menjadi 0 sebelum validasi dan simpan
        if ($this->editingTeam->payment_type == 'harian') {
            $validationRules['editingTeam.number_of_workers'] = 'required|integer|min:0';
            $validationRules['editingTeam.worker_daily_wage'] = 'required|numeric|min:0';
            $this->editingTeam->lump_sum_value = 0; // Set to 0 if not used
            $this->editingTeam->work_progress = 0; // Set to 0 if not used
        } else {
            $validationRules['editingTeam.lump_sum_value'] = 'required|numeric|min:0';
            $validationRules['editingTeam.work_progress'] = 'required|integer|min:0|max:100';
            $this->editingTeam->number_of_workers = 0; // Set to 0 if not used
            $this->editingTeam->worker_daily_wage = 0; // Set to 0 if not used
        }

        $this->validate($validationRules);

        $this->editingTeam->save();
        session()->flash('message', 'Data tim berhasil diperbarui.');
        $this->isTeamEditModalOpen = false;
        $this->calculateFinancials();
    }

    public function closeTeamEditModal()
    {
        $this->isTeamEditModalOpen = false;
    }

    // --- Manajemen Tugas ---
    public function openTaskModal() { $this->isTaskModalOpen = true; }
    public function closeTaskModal() { $this->isTaskModalOpen = false; $this->resetTaskForm(); }
    public function resetTaskForm()
    {
        $this->taskId = null;
        $this->task_name = '';
        $this->task_description = '';
        $this->task_status = 'Belum Dikerjakan';
        $this->task_start_date = '';
        $this->task_end_date = '';
        $this->task_progress = 0;
    }
    public function createTask() { $this->resetTaskForm(); $this->openTaskModal(); }
    public function storeTask()
    {
        $this->validate(['task_name' => 'required|string|max:255', 'task_start_date' => 'required|date', 'task_end_date' => 'required|date|after_or_equal:task_start_date', 'task_progress' => 'required|integer|min:0|max:100']);
        $status = 'Belum Dikerjakan';
        if ($this->task_progress > 0 && $this->task_progress < 100) { $status = 'Sedang Dikerjakan'; } 
        elseif ($this->task_progress == 100) { $status = 'Selesai'; }
        Task::updateOrCreate(['id' => $this->taskId], ['project_id' => $this->project->id, 'name' => $this->task_name, 'description' => $this->task_description, 'status' => $status, 'progress' => $this->task_progress, 'start_date' => $this->task_start_date, 'end_date' => $this->task_end_date]);
        session()->flash('message', 'Tugas berhasil diperbarui.');
        $this->closeTaskModal();
    }
    public function editTask($id)
    {
        $task = Task::findOrFail($id);
        $this->taskId = $id;
        $this->task_name = $task->name;
        $this->task_description = $task->description;
        $this->task_status = $task->status;
        $this->task_start_date = $task->start_date;
        $this->task_end_date = $task->end_date;
        $this->task_progress = $task->progress;
        $this->openTaskModal();
    }
    public function deleteTask($id) { Task::find($id)?->delete(); session()->flash('message', 'Tugas berhasil dihapus.'); }

    // --- Manajemen Dokumen ---
    public function uploadDocument()
    {
        $this->validate(['document' => 'required|file|max:10240']);
        $path = $this->document->store('project-documents/' . $this->project->id, 'public');
        $this->project->documents()->create(['uploaded_by_id' => Auth::id(), 'name' => $this->document->getClientOriginalName(), 'path' => $path, 'type' => $this->document->getClientMimeType()]);
        session()->flash('message', 'Dokumen berhasil diunggah.');
        $this->reset('document');
    }
    public function deleteDocument($documentId)
    {
        $doc = $this->project->documents()->findOrFail($documentId);
        Storage::disk('public')->delete($doc->path);
        $doc->delete();
        session()->flash('message', 'Dokumen berhasil dihapus.');
    }

    // --- Pengeluaran Lain-lain ---
    public function openExpenseModal() { $this->resetExpenseForm(); $this->isExpenseModalOpen = true; }
    public function closeExpenseModal() { $this->isExpenseModalOpen = false; }
    private function resetExpenseForm() { $this->expense_description = ''; $this->expense_amount = null; }
    public function storeExpense()
    {
        $this->validate(['expense_description' => 'required|string', 'expense_amount' => 'required|numeric|min:1']);
        $this->project->otherExpenses()->create(['expense_date' => now(), 'description' => $this->expense_description, 'amount' => $this->expense_amount, 'user_id' => Auth::id()]);
        session()->flash('message', 'Pengeluaran berhasil dicatat.');
        $this->closeExpenseModal();
        $this->calculateFinancials();
    }
    public function deleteExpense($expenseId) { $this->project->otherExpenses()->find($expenseId)?->delete(); session()->flash('message', 'Pengeluaran berhasil dihapus.'); $this->calculateFinancials(); }

    // --- LOGIKA UNTUK RAB ---
    public function loadRabData()
    {
        $this->rab = Rab::firstOrCreate(['project_id' => $this->project->id]);
        $this->rabItems = $this->rab->items->toArray();
        // Pastikan rabItems diurutkan berdasarkan kategori untuk pengelompokan di tampilan
        usort($this->rabItems, function($a, $b) {
            return strcmp($a['category'], $b['category']);
        });
        $this->calculateTotalRab();
    }

    public function addRabItem($category = 'PEKERJAAN PERSIAPAN')
    {
        $this->rabItems[] = ['category' => $category, 'analysis_id' => null, 'description' => '', 'quantity' => 1, 'unit' => '', 'unit_price' => 0, 'total_price' => 0];
    }

    public function removeRabItem($index)
    {
        unset($this->rabItems[$index]);
        $this->rabItems = array_values($this->rabItems); // Re-index array after unsetting
        $this->calculateTotalRab();
    }

    public function updatedRabItems($value, $path)
    {
        $pathParts = explode('.', $path);

        // Pastikan kita memiliki setidaknya 2 bagian (rabItems, index)
        // dan kemudian coba dapatkan properti.
        if (count($pathParts) >= 2) {
            $index = $pathParts[1];
            $property = isset($pathParts[2]) ? $pathParts[2] : null; // Ambil properti dengan aman, atau null

            // Hanya lanjutkan jika properti ada dan itu adalah 'analysis_id'
            if ($property === 'analysis_id' && $value) {
                // Periksa apakah $index valid sebelum mengakses $this->rabItems[$index]
                if (isset($this->rabItems[$index])) {
                    $analysis = $this->analysisList->find($value);
                    if ($analysis) {
                        $this->rabItems[$index]['description'] = $analysis->name;
                        $this->rabItems[$index]['unit'] = $analysis->unit;
                        $this->rabItems[$index]['unit_price'] = $analysis->total_cost;
                    }
                }
            }
        }
        $this->calculateTotalRab();
    }

    public function calculateTotalRab()
    {
        $total = 0;
        foreach ($this->rabItems as $index => &$item) {
            $quantity = is_numeric($item['quantity'] ?? 0) ? $item['quantity'] : 0;
            $unitPrice = is_numeric($item['unit_price'] ?? 0) ? $item['unit_price'] : 0;
            $item['total_price'] = $quantity * $unitPrice;
            $total += $item['total_price'];
        }
        $this->totalRabAmount = $total;
    }

    public function saveRab()
    {
        // Validasi deskripsi item RAB, sisanya akan divalidasi oleh database atau default 0
        $this->validate([
            'rabItems.*.description' => 'required|string',
            'rabItems.*.quantity' => 'required|numeric|min:0',
            'rabItems.*.unit' => 'required|string',
            'rabItems.*.unit_price' => 'required|numeric|min:0',
        ]);


        DB::transaction(function () {
            $this->rab->items()->delete();
            foreach ($this->rabItems as $item) {
                // Pastikan analysis_id tidak kosong sebelum menyimpan
                // Jika analysis_id kosong, tetap set ke null agar tidak melanggar foreign key jika kolom mengizinkan NULL
                $itemToSave = [
                    'category' => $item['category'],
                    'analysis_id' => !empty($item['analysis_id']) ? $item['analysis_id'] : null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ];
                $this->rab->items()->create($itemToSave);
            }
            $this->rab->update(['total_amount' => $this->totalRabAmount]);
            $this->project->update(['budget' => $this->totalRabAmount]);
        });

        session()->flash('message', 'RAB berhasil disimpan dan anggaran proyek telah diperbarui.');
        $this->loadRabData();
        $this->calculateFinancials();
        $this->project->refresh();
    }
}
