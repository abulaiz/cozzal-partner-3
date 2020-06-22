<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\FpdfFormat;
use App\Models\Payment;
use App\User;
use Auth;

class PdfController extends Controller
{
    public function payment_invoice($id){
        $data = app('App\Http\Controllers\PaymentController')->invoice($id)->getData();
        foreach ($data->owners as $item) $owner = $item;

        $pdf = new FpdfFormat();
        $pdf->AddPage();
        $pdf->logo('../public/assets/images/logo2.png');
        $pdf->SetFont('Arial','',8);
        $pdf->head("Gateway Apartemen, Tower Shappire A - Lantai G - A 10, Jl. Jend. A. Yani no. 669, Bandung", "022-20546654 / 081809824448");
        $pdf->SetFont('Arial','B',10);
        $pdf->columnEmptyCenter($owner->name, 'INVOICE',' ', 'R');
        $pdf->Cell(189 ,1,'',0,1);       

        $pdf->SetFont('Arial','',10);
        // $pdf->columnEmptyCenter($owner->address, $invoice_number,' ', 'R');
        $pdf->columnEmptyCenter("owner Address", $data->receipt_number,' ', 'R');
        $pdf->columnEmptyCenter('Mobile Phone : '.$owner->phone, 'Invoice Date : '. $data->date ,' ', 'R');
        $pdf->columnEmptyCenter($owner->email, ' ',' ', 'R');
        $pdf->Cell(189 ,5,'',0,1);

        // Expenditure
        $exp_col_width = ['10','30','50','40','20','40'];
        $pdf->SetFont('Arial','B',10);
        $pdf->Row(['Unit Expenditures'], ['190'], ['C'], 5);
        $pdf->Row(['#','Date','Note','Price','Qty','Total'], $exp_col_width, ['C'], 6);
        
        $pdf->SetFont('Arial','',10);
        $i = 1; $exp_total = 0;
        foreach ($data->expenditures as $item) {
            $pdf->Row([
                $i++, substr($item->created_at, 0, 10), $item->description,
                number_format($item->price)." IDR",
                $item->qty, number_format($item->price * $item->qty)." IDR"
            ], $exp_col_width, ['C','C','L','C','C','R'], 5);
            $exp_total += $item->price * $item->qty;
        }
        $pdf->SetFont('Arial','B',10);
        $pdf->Row(['Total Expenditures', number_format($exp_total).' IDR'], ['150', '40'], ['L', 'R'], 5);
        $pdf->Ln();

        // Reservation
        $res_col_width = ['10','60','80','40'];
        $pdf->Row(['Unit Revenue'], ['190'], ['C'], 6);
        $pdf->Row(['#','Check In/Check Out','Unit','Total Price'], $res_col_width, ['C'], 6);
        
        $pdf->SetFont('Arial','',10);
        $i = 1; $res_total = 0;
        foreach ($data->reservations as $item) {
            $total = (int)json_decode($item->owner_rent_prices)->TP;
            $pdf->Row([
                $i++, substr($item->check_in, 0, 10)."/".substr($item->check_out, 0, 10), 
                $item->unit->unit_number." - ".$item->unit->apartment->name,
                number_format( $total )." IDR",
            ], $res_col_width, ['C','C','C','C','R'], 5);
            $res_total += $total;
        }
        $pdf->SetFont('Arial','B',10);
        $pdf->Row(['Total Revenue', number_format($res_total).' IDR'], ['150', '40'], ['L', 'R'], 5);   
        $pdf->Ln();     

        $pdf->SetFont('Arial','B',10);
        $pdf->columnEmptyCenter(' ', 'Earnings :','L', 'L');
        $pdf->Cell(189 ,2,'',0,1);

        $pdf->SetFont('Arial','',10);
        $pdf->columnTriplePullRight('', '     Total Revenue : ', '(+) '.number_format($res_total).' IDR', 'L', 'L', 'R');
        $pdf->columnTriplePullRight('', '     Total Expenditures : ', '(-) '.number_format($exp_total).' IDR', 'L', 'L', 'R');
        if($data->paid_earning < $res_total - $exp_total){
            $pdf->columnTriplePullRight('', '     '.$data->description.' : ', '(-) '.number_format( ($res_total-$exp_total) - $data->paid_earning ).' IDR', 'L', 'L', 'R');
        } elseif($data->paid_earning > $res_total - $exp_total){
            $pdf->columnTriplePullRight('', '     '.$data->description.' : ', '(+) '.number_format( $data->paid_earning - ($res_total-$exp_total) ).' IDR', 'L', 'L', 'R');
        }

        $pdf->Ln(1);
        $pdf->Line(112, $pdf->GetY(), 200, $pdf->getY());
        $pdf->Ln(1);
        $pdf->columnTriplePullRight('', '     Earnings : ', number_format($data->paid_earning).' IDR', 'L', 'L', 'R');
        $pdf->Output();    
        exit;    
    }
}
