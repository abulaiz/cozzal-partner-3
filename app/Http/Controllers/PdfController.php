<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\FpdfFormat;

class PdfController extends Controller
{
    public function payment_invoice($code){
        $plain = $this->enc->decrypt($code);
        $param = explode('/', $plain);

        $office_address = Setting::getParam('office_address');
        $office_telephone = Setting::getParam('office_telephone');        
        $reservations = [];
        $expenditures = [];
        $owner; 
        $groupPayment = null;
        $owner_id = null;
        $id = $param[0];

        if (count($param) == 1) {
            $groupPayment = GroupPayment::find($id);
            $owner = User::find($groupPayment->user_id);
            if(Auth::user()->hasRole('Owner') && Auth::user()->id != $groupPayment->user_id)
                return redirect()->back();

            $list = PaymentO::where('group_payment_id', $groupPayment->id)->get();
            foreach ($list as $items) {
                $item = null;
                if($items->reservation_id != null){
                    $item = Reservation::find( $items->reservation_id );
                    $reservations[] = $this->addReservation($item);
                } elseif($items->expenditure_id != null) {
                    $item = Expenditure::find( $items->expenditure_id );
                    $expenditures[] = $this->addExpenditure($item);
                } elseif ($items->broker_take_over_id != null) {
                    $item = BrokerTakeOver::find( $items->broker_take_over_id );
                    $reservations[] = $this->addBrokerTakeOver($item);
                }
            }
        } elseif(count($param) == 2) {
            $ids = explode(",", $id);
            $owner = User::find($param[1]);
            foreach ($ids as $data) {
                $data_id = substr($data, 1);
                $item = null;
                if ($data[0] == 'r') {
                    $item = Reservation::find( $data_id );
                    $reservations[] = $this->addReservation($item);
                } elseif ($data[0] == 'e') {
                    $item = Expenditure::find( $data_id );
                    $expenditures[] = $this->addExpenditure($item);
                } elseif ($data[0] == 'b') {
                    $item = BrokerTakeOver::find( $data_id );
                    $reservations[] = $this->addBrokerTakeOver($item);
                }
            }                            
        }
        $invoice_number = ($groupPayment == null ? '' : 'Invoice Id : '.strtoupper('OPM-'.dechex($groupPayment->id)) );
        $invoice_date = ($groupPayment == null ? date('Y-m-d') : substr($groupPayment->updated_at, 0, 10));

        $pdf = new FpdfFormat();
        $pdf->AddPage();

        $pdf->logo('../public/assets/images/logo2.png');
        $pdf->SetFont('Arial','',8);
        $pdf->head($office_address, $office_telephone);
        $pdf->SetFont('Arial','B',10);
        $pdf->columnEmptyCenter($owner->name, 'INVOICE',' ', 'R');
        $pdf->Cell(189 ,1,'',0,1);       

        $pdf->SetFont('Arial','',10);
        $pdf->columnEmptyCenter($owner->profile('address'), $invoice_number,' ', 'R');
        $pdf->columnEmptyCenter('Mobile Phone : '.$owner->profile('phone'), 'Invoice Date : '. $invoice_date ,' ', 'R');
        $pdf->columnEmptyCenter($owner->email, ' ',' ', 'R');
        $pdf->Cell(189 ,5,'',0,1);

        // Expenditure
        $exp_col_width = ['10','30','50','40','20','40'];
        $pdf->SetFont('Arial','B',10);
        $pdf->Row(['Unit Expenditures'], ['190'], ['C'], 5);
        $pdf->Row(['#','Date','Note','Price','Qty','Total'], $exp_col_width, ['C'], 6);
        
        $pdf->SetFont('Arial','',10);
        $i = 1; $exp_total = 0;
        foreach ($expenditures as $item) {
            $pdf->Row([
                $i++, $item->date, $item->note,
                number_format($item->price)." IDR",
                $item->qty, number_format($item->total)." IDR"
            ], $exp_col_width, ['C','C','L','C','C','R'], 5);
            $exp_total += $item->total;
        }
        $pdf->SetFont('Arial','B',10);
        $pdf->Row(['Total Expenditures', number_format($exp_total).' IDR'], ['150', '40'], ['L', 'R'], 5);
        $pdf->Ln();

        // Reservation
        $res_col_width = ['10','60','30','50','40'];
        $pdf->Row(['Unit Revenue'], ['190'], ['C'], 6);
        $pdf->Row(['#','Check In/Check Out','Unit','Apartement','Total Price'], $res_col_width, ['C'], 6);
        
        $pdf->SetFont('Arial','',10);
        $i = 1; $res_total = 0;
        foreach ($reservations as $item) {
            $pdf->Row([
                $i++, $item->check_in."/".$item->check_out, 
                $item->unit, $item->apartment,
                number_format($item->price)." IDR",
            ], $res_col_width, ['C','C','C','C','R'], 5);
            $res_total += $item->price;
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
        $pdf->Ln(1);
        $pdf->Line(112, $pdf->GetY(), 200, $pdf->getY());
        $pdf->Ln(1);
        $pdf->columnTriplePullRight('', '     Earnings : ', number_format($res_total - $exp_total).' IDR', 'L', 'L', 'R');
        if($groupPayment!=null){
            if($groupPayment->nominal_paid != $groupPayment->nominal){
                $pdf->Ln();   
                $pdf->columnTriplePullRight('', '     Earnings Paid : ', number_format($groupPayment->nominal_paid).' IDR', 'L', 'L', 'R');
                $pdf->columnTriplePullRight('', '     Description : ', $groupPayment->description, 'L', 'L', 'R');
            }
        }

        $pdf->Output();    
        exit;    
    }
}
