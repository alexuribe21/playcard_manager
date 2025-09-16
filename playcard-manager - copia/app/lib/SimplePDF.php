<?php
class SimplePDF {
  private $pages=[]; private $current=null;
  public function addPage(){ $this->current=["content"=>[]]; $this->pages[]=&$this->current; }
  public function text($x,$y,$txt){ $y=842-($y*2.83465); $x=($x*2.83465); $s=$this->esc($txt); $this->current["content"][]="BT /F1 10 Tf $x $y Td ($s) Tj ET"; }
  private function esc($s){ return str_replace(['\\','(',')',"\r","\n"],['\\\\','\\(','\\)',' ',' '],$s); }
  public function output($filename='reporte.pdf'){
    $pdf="%PDF-1.4\n"; $ofs=[]; $n=1;
    $ofs[$n]=strlen($pdf); $pdf.="$n 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n"; $font=$n; $n++;
    $kids=[];
    foreach($this->pages as $p){
      $content=implode("\n",$p["content"]); $stream="q\n".$content."\nQ";
      $ofs[$n]=strlen($pdf); $pdf.="$n 0 obj << /Length ".strlen($stream)." >> stream\n$stream\nendstream endobj\n"; $cont=$n; $n++;
      $page=$n; $ofs[$n]=strlen($pdf); $pdf.="$n 0 obj << /Type /Page /Parent 0 0 R /MediaBox [0 0 595 842] /Contents $cont 0 R /Resources << /Font << /F1 $font 0 R >> >> >> endobj\n"; $kids[]="$page 0 R"; $n++;
    }
    $pagesObj=$n; $ofs[$n]=strlen($pdf); $pdf.="$n 0 obj << /Type /Pages /Count ".count($kids)." /Kids [ ".implode(' ',$kids)." ] >> endobj\n"; $n++;
    $ofs[$n]=strlen($pdf); $pdf.="$n 0 obj << /Type /Catalog /Pages $pagesObj 0 R >> endobj\n"; $root=$n; $n++;
    $pdf=str_replace("/Parent 0 0 R","/Parent $pagesObj 0 R",$pdf);
    $xref=strlen($pdf); $pdf.="xref\n0 $n\n0000000000 65535 f \n"; 
    for($i=1;$i<$n;$i++){ $pdf.=sprintf("%010d 00000 n \n",$ofs[$i]); }
    $pdf.="trailer << /Size $n /Root $root 0 R >>\nstartxref\n$xref\n%%EOF";
    header('Content-Type: application/pdf'); header('Content-Disposition: attachment; filename="'.$filename.'"'); echo $pdf;
  }
}
