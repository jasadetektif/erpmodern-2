<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .details-table { width: 100%; margin-bottom: 20px; }
        .details-table td { padding: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
        .items-table th { background-color: #f2f2f2; text-align: left; }
        .totals { float: right; width: 40%; }
        .totals table { width: 100%; }
        .totals table td { padding: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Slip Gaji</h1>
            <p>{{ $payslip->payroll->payroll_period }}</p>
        </div>
        <table class="details-table">
            <tr>
                <td><strong>Nama Karyawan:</strong></td>
                <td>{{ $payslip->employee->user->name ?? $payslip->employee->employee_id_number }}</td>
                <td><strong>Tanggal Pembayaran:</strong></td>
                <td>{{ \Carbon\Carbon::parse($payslip->payroll->end_date)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Jabatan:</strong></td>
                <td>{{ $payslip->employee->position }}</td>
                <td><strong>ID Karyawan:</strong></td>
                <td>{{ $payslip->employee->employee_id_number }}</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr><th colspan="2">Pendapatan</th></tr>
            </thead>
            <tbody>
                @foreach($payslip->items->where('type', 'earning') as $item)
                <tr><td>{{ __($item->description) }}</td><td style="text-align: right;">Rp {{ number_format($item->amount, 2, ',', '.') }}</td></tr>
                @endforeach
            </tbody>
        </table>

         <table class="items-table">
            <thead>
                <tr><th colspan="2">Potongan</th></tr>
            </thead>
            <tbody>
                @forelse($payslip->items->where('type', 'deduction') as $item)
                <tr><td>{{ __($item->description) }}</td><td style="text-align: right;">(Rp {{ number_format($item->amount, 2, ',', '.') }})</td></tr>
                @empty
                <tr><td>{{__('No deductions')}}</td><td style="text-align: right;">Rp 0.00</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr><td><strong>Gaji Kotor:</strong></td><td style="text-align: right;">Rp {{ number_format($payslip->gross_salary, 2, ',', '.') }}</td></tr>
                <tr><td><strong>Total Potongan:</strong></td><td style="text-align: right;">(Rp {{ number_format($payslip->total_deductions, 2, ',', '.') }})</td></tr>
                <tr><td style="border-top: 1px solid #ddd;"><strong>Gaji Bersih:</strong></td><td style="border-top: 1px solid #ddd; text-align: right;"><strong>Rp {{ number_format($payslip->net_salary, 2, ',', '.') }}</strong></td></tr>
            </table>
        </div>
    </div>
</body>
</html>