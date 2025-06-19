<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header, .footer { text-align: center; }
        .header h1 { margin: 0; }
        .details-table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .details-table td { padding: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
        .items-table th { background-color: #f2f2f2; text-align: left; }
        .totals { float: right; width: 40%; }
        .totals table { width: 100%; }
        .totals table td { padding: 5px; }
        .signature { margin-top: 50px; }
        .signature div { display: inline-block; width: 45%; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Purchase Order</h1>
            <p><strong>Nomor PO:</strong> {{ $purchaseOrder->po_number }}</p>
        </div>

        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <strong>Kepada Yth:</strong><br>
                    {{ $purchaseOrder->supplier->name }}<br>
                    {{ $purchaseOrder->supplier->address }}<br>
                    Up: {{ $purchaseOrder->supplier->contact_person }}
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <strong>Tanggal Pesan:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d F Y') }}<br>
                    <strong>Proyek:</strong> {{ $purchaseOrder->purchaseRequest->project->name }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th>Satuan</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: right;">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->items as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td>{{ $item->unit }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->price, 2, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->total_price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; border: none;"><strong>Grand Total</strong></td>
                    <td style="text-align: right; border: 1px solid #ddd;"><strong>Rp {{ number_format($purchaseOrder->total_amount, 2, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <div>
            <strong>Catatan:</strong><br>
            <p>{{ $purchaseOrder->notes ?? '-' }}</p>
        </div>

        <div class="signature">
            <div>
                <p>Dipesan Oleh,</p>
                <br><br><br>
                <p>( {{ $purchaseOrder->orderBy->name }} )</p>
            </div>
             <div>
                <p>Disetujui Oleh,</p>
                <br><br><br>
                <p>(_________________________)</p>
            </div>
        </div>
    </div>
</body>
</html>