<!DOCTYPE html>
<html>
<head>
    <title>Faktur Klien</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .details-table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .details-table td { padding: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
        .items-table th { background-color: #f2f2f2; text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>FAKTUR</h1>
        </div>

        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <strong>Ditagihkan Kepada:</strong><br>
                    {{ $clientInvoice->project->client }}<br>
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Nomor Faktur:</strong> {{ $clientInvoice->invoice_number }}<br>
                    <strong>Tanggal Faktur:</strong> {{ \Carbon\Carbon::parse($clientInvoice->invoice_date)->format('d F Y') }}<br>
                    <strong>Tanggal Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($clientInvoice->due_date)->format('d F Y') }}
                </td>
            </tr>
        </table>

        <h3>Proyek: {{ $clientInvoice->project->name }}</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $clientInvoice->description }}</td>
                    <td class="text-right">Rp {{ number_format($clientInvoice->amount, 2, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="font-bold bg-gray-50">
                    <td class="text-right">Total Tagihan</td>
                    <td class="text-right">Rp {{ number_format($clientInvoice->amount, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 40px;">
            <p>Silakan lakukan pembayaran ke rekening berikut:</p>
            <p><strong>Bank XYZ</strong><br>
            No. Rek: 123-456-7890<br>
            Atas Nama: PT. Kontraktor Jaya</p>
        </div>
    </div>
</body>
</html>
