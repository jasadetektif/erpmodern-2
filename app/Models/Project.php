<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'client',
        'budget',
        'status',
        'start_date',
        'end_date',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model Task.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Mendefinisikan relasi one-to-many ke model ProjectTeam.
     */
    public function teams()
    {
        return $this->hasMany(ProjectTeam::class);
    }

    /**
     * Mendefinisikan relasi one-to-many ke model Employee.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Mendefinisikan relasi one-to-many ke model ProjectDocument.
     */
    public function documents()
    {
        return $this->hasMany(ProjectDocument::class)->latest();
    }
    
    /**
     * Mendefinisikan relasi has-many-through ke PurchaseOrder.
     */
    public function purchaseOrders()
    {
        return $this->hasManyThrough(PurchaseOrder::class, PurchaseRequest::class);
    }

    /**
     * Mendefinisikan relasi one-to-many ke ClientInvoice.
     */
    public function clientInvoices()
    {
        return $this->hasMany(ClientInvoice::class);
    }

    /**
     * Mendefinisikan relasi has-many-through ke ClientPayment.
     */
    public function clientPayments()
    {
        return $this->hasManyThrough(ClientPayment::class, ClientInvoice::class);
    }

    public function otherExpenses()
{
    return $this->hasMany(ProjectExpense::class);
}

    /**
     * Accessor untuk mendapatkan total biaya tenaga kerja riil.
     *
     * @return float
     */
   public function getTotalLaborCostAttribute()
{
    $totalCost = 0;
    $workingDaysInPeriod = 12; // Asumsi 2 minggu kerja (Senin-Sabtu x 2)

    $this->loadMissing('teams.employee');

    foreach ($this->teams as $team) {
        // Biaya Site Manager (jika ada) - kita asumsikan dihitung terpisah atau dialokasikan
        // Untuk saat ini, kita fokus pada tim mandor

        if ($team->payment_type == 'harian') {
            // Biaya gaji mandor (bulanan dibagi periode 2 mingguan)
            $mandorSalary = ($team->employee->basic_salary ?? 0) / 2;
            // Biaya upah tukang (harian x jumlah x hari kerja)
            $workerWages = $team->number_of_workers * $team->worker_daily_wage * $workingDaysInPeriod;
            $totalCost += $mandorSalary + $workerWages;

        } elseif ($team->payment_type == 'borongan') {
            // Biaya borongan berdasarkan progress
            $progressDecimal = $team->work_progress / 100;
            $totalCost += $team->lump_sum_value * $progressDecimal;
        }
    }
    return $totalCost;
}

    /**
     * Accessor untuk membuat tautan ke halaman detail proyek.
     *
     * @return string
     */
    public function getLinkAttribute()
    {
        return route('projects.show', $this->id);
    }

    public function rab()
{
    return $this->hasOne(Rab::class);
}

}
