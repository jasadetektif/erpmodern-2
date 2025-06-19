<!DOCTYPE html>
<html>
<head>
    <title>Detail Laporan Laba Rugi Proyek</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .summary-table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .summary-table td { padding: 8px; border: 1px solid #ddd; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
        .items-table th { background-color: #f2f2f2; text-align: left; }
        .text-right { text-align: right; }
        .text-green { color: green; }
        .text-red { color: red; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detail Laporan Laba/Rugi</h1>
            <h2>Proyek: {{ $project->name }}</h2>
            <p>Per Tanggal: {{ date('d F Y') }}</p>
        </div>

        <h3>Ringkasan Keuangan</h3>
        <table class="summary-table">
            <tr>
                <td class="font-bold">Total Anggaran</td>
                <td class="text-right">Rp {{ number_format($project->budget, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Total Pengeluaran</td>
                <td class="text-right">Rp {{ number_format($totalExpenses, 2, ',', '.') }}</td>
            </tr>
            <tr class="{{ $profitOrLoss >= 0 ? 'text-green' : 'text-red' }}">
                <td class="font-bold">Laba / Rugi</td>
                <td class="text-right font-bold">Rp {{ number_format($profitOrLoss, 2, ',', '.') }}</td>
            </tr>
        </table>

        <h3>Rincian Pengeluaran (Purchase Orders)</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Nomor PO</th>
                    <th>Tanggal Pesan</th>
                    <th>Pemasok</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($project->purchaseOrders as $po)
                    <tr>
                        <td>{{ $po->po_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                        <td>{{ $po->supplier->name }}</td>
                        <td class="text-right">Rp {{ number_format($po->total_amount, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align: center;">Tidak ada data pengeluaran.</td></tr>
                @endforelse
            </tbody>
             <tfoot>
                <tr class="font-bold bg-gray-50">
                    <td colspan="3" class="text-right">Total Pengeluaran</td>
                    <td class="text-right">Rp {{ number_format($totalExpenses, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
