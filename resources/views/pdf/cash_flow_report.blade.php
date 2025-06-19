<!DOCTYPE html>
<html>
<head>
    <title>Laporan Arus Kas</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .report-table { width: 100%; border-collapse: collapse; }
        .report-table th, .report-table td { border: 1px solid #ddd; padding: 8px; }
        .report-table th { background-color: #f2f2f2; text-align: left; }
        .text-right { text-align: right; }
        .text-green { color: green; }
        .text-red { color: red; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Laporan Arus Kas</h1>
            <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th class="text-right">Kas Masuk</th>
                    <th class="text-right">Kas Keluar</th>
                    <th class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php $balance = 0; @endphp
                @forelse ($transactions as $transaction)
                    @php $balance += $transaction->cash_in - $transaction->cash_out; @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-right text-green">{{ $transaction->cash_in > 0 ? 'Rp ' . number_format($transaction->cash_in, 2, ',', '.') : '-' }}</td>
                        <td class="text-right text-red">{{ $transaction->cash_out > 0 ? 'Rp ' . number_format($transaction->cash_out, 2, ',', '.') : '-' }}</td>
                        <td class="text-right font-bold">Rp {{ number_format($balance, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center;">Tidak ada transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="font-bold">
                    <td colspan="2" class="text-right">Total</td>
                    <td class="text-right text-green">Rp {{ number_format($transactions->sum('cash_in'), 2, ',', '.') }}</td>
                    <td class="text-right text-red">Rp {{ number_format($transactions->sum('cash_out'), 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($balance, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
