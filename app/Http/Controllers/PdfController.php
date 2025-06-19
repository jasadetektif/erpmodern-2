<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payslip;
use App\Models\PurchaseOrder;
use App\Models\Project;
use App\Models\ClientInvoice;
use App\Models\ClientPayment;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    /**
     * Generate PDF for a single payslip.
     *
     * @param  \App\Models\Payslip  $payslip
     * @return \Illuminate\Http\Response
     */
    public function generatePayslipPdf(Payslip $payslip)
    {
        $payslip->load('employee.user', 'payroll', 'items');
        $pdf = Pdf::loadView('pdf.payslip', compact('payslip'));
        return $pdf->stream('payslip-' . $payslip->id . '.pdf');
    }

    /**
     * Generate PDF for a single purchase order.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function generatePoPdf(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(
            'supplier',
            'orderBy',
            'items',
            'purchaseRequest.project'
        );
        $pdf = Pdf::loadView('pdf.purchase_order', compact('purchaseOrder'));
        return $pdf->stream('po-' . $purchaseOrder->po_number . '.pdf');
    }

    /**
     * Generate PDF for the Project P&L summary report.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateProjectPlPdf()
    {
        $projects = Project::with('purchaseOrders')->get()->map(function ($project) {
            $project->total_expenses = $project->purchaseOrders->sum('total_amount');
            $project->profit_loss = $project->budget - $project->total_expenses;
            $project->margin = ($project->budget > 0) ? ($project->profit_loss / $project->budget) * 100 : 0;
            return $project;
        });

        $pdf = Pdf::loadView('pdf.project_pl_report', compact('projects'));
        return $pdf->stream('laporan-laba-rugi-proyek.pdf');
    }

    /**
     * Generate PDF for a single project's P&L detail.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function generateProjectPlDetailPdf(Project $project)
    {
        $project->load('purchaseOrders.supplier');

        $totalExpenses = $project->purchaseOrders->sum('total_amount');
        $profitOrLoss = $project->budget - $totalExpenses;
        
        $pdf = Pdf::loadView('pdf.project_pl_detail_report', compact('project', 'totalExpenses', 'profitOrLoss'));
        return $pdf->stream('laporan-detail-' . $project->name . '.pdf');
    }

    /**
     * Generate PDF for a client invoice.
     *
     * @param  \App\Models\ClientInvoice  $clientInvoice
     * @return \Illuminate\Http\Response
     */
    public function generateClientInvoicePdf(ClientInvoice $clientInvoice)
    {
        $clientInvoice->load('project');
        $pdf = Pdf::loadView('pdf.client_invoice', compact('clientInvoice'));
        return $pdf->stream('invoice-' . $clientInvoice->invoice_number . '.pdf');
    }

    /**
     * Generate PDF for the Cash Flow report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateCashFlowPdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $cashIn = ClientPayment::with('clientInvoice.project')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get()
            ->map(function ($payment) {
                return (object)[
                    'date' => $payment->payment_date,
                    'description' => 'Penerimaan dari ' . $payment->clientInvoice->project->client . ' (Inv: ' . $payment->clientInvoice->invoice_number . ')',
                    'cash_in' => $payment->amount,
                    'cash_out' => 0,
                ];
            });

        $cashOut = Payment::with('invoice.supplier')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get()
            ->map(function ($payment) {
                return (object)[
                    'date' => $payment->payment_date,
                    'description' => 'Pembayaran ke ' . $payment->invoice->supplier->name . ' (Inv: ' . $payment->invoice->invoice_number . ')',
                    'cash_in' => 0,
                    'cash_out' => $payment->amount,
                ];
            });

        $transactions = $cashIn->concat($cashOut)->sortBy('date');

        $pdf = Pdf::loadView('pdf.cash_flow_report', compact('transactions', 'startDate', 'endDate'));
        return $pdf->stream('laporan-arus-kas.pdf');
    }
}
