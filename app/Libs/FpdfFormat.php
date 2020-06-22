<?php
  namespace App\Libs;
  use App\Libs\FPDF;

  class FpdfFormat extends FPDF
  {
    var $widths;
    var $aligns;
    var $cellHeigth = 5;
    var $padding = [1, 1, 1, 1]; // left, top, right, buttom 

    function SetWidths($w)
    {
        $this->widths=$w;
    }

    function SetAligns($a)
    {
        $this->aligns=$a;
    }

    function SetCellHeight($ch){
        $this->cellHeigth = $ch;
    }

    function SetPadding($pd){
        $this->padding = $pd;
    }

    function Row($data, $widths = null, $aligns = null, $cellHeigth = null, $padding = null)
    {
        if($widths != null)
            $this->widths = $widths;
        if($aligns != null)
            $this->aligns = $aligns;
        if($cellHeigth != null)
            $this->cellHeigth = $cellHeigth;
        if($padding != null)
            $this->padding = $padding;
                                
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

        $h = $this->cellHeigth * $nb +($this->padding[3]*2); //+($this->padding[3]*2) to set Bottom Padding
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            //Get Original Posistion
            $x = $this->GetX();
            $y = $this->GetY();

            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : (isset($this->aligns[0]) ? $this->aligns[0] : 'L');
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            //Set padding
            $this->SetX($x + $this->padding[0]); // Set Left Padding
            $w = $w - $this->padding[0] - $this->padding[2]; // Set Right Padding
            $this->SetY($y + $this->padding[1], false); // Set TOp Padding
            //Print the text
            $this->MultiCell($w,$this->cellHeigth,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w+$this->padding[0]+$this->padding[2], $y );
        }

        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    function logo($src)
    {
        Fpdf::Image($src,12,12,30);
        Fpdf::Cell(189 ,5,'',0,1);
    }

    function head($office_address, $office_telephone)
    {
        Fpdf::Cell(80);
        Fpdf::Cell(50,10,'',0,0,'C');
        Fpdf::Cell(50,10,'',0,1,'C');

        $address = explode(" ", $office_address);
        $line1 = '';
        $line2 = '';
        for ($i=0; $i < (count($address)/2) ; $i++) {
          $line1 = $line1.' '.$address[$i];
        }
        for ($i=(count($address)/2); $i < count($address) ; $i++) {
          $line2 = $line2.' '.$address[$i];
        }

        Fpdf::Cell(120 ,5,$line1,0,1);
        Fpdf::Cell(120 ,5,$line2,0,1);
        Fpdf::Cell(120 ,5,' '.$office_telephone,0,1);
        Fpdf::Cell(189 ,5,'',0,1);
    }

    function columnTripleBordered($first, $second, $last, $align1, $align2, $align3)
    {
        Fpdf::Cell(10 ,5,' '.$first,1,0,$align1);
        Fpdf::Cell(85 ,5,' '.$second,1,0,$align2);
        Fpdf::Cell(95 ,5,$last,1,1,$align3);
    }

    function columnTriplePullRight($first, $second, $last, $align1, $align2, $align3)
    {
        Fpdf::Cell(95 ,5,' '.$first,0,0,$align1);
        Fpdf::Cell(50 ,5,' '.$second,0,0,$align2);
        Fpdf::Cell(45 ,5,$last,0,1,$align3);
    }

    function columnEmptyCenter($left, $right, $align1, $align2)
    {
        Fpdf::Cell(95 ,5,' '.$left,0,0,$align1);
        Fpdf::Cell(95 ,5,$right,0,1,$align2);
    }

  }
