<?php

class Png {
     
    public function __construct() {
        //
    }
     
    public function imagePngCreate($tekst) {
		// 600x300 dimenzije
		$im = imagecreate(600, 300);

		// bijela pozadina, plavi tekst
		$bg = imagecolorallocate($im, 255, 255, 255);
		$textcolor = imagecolorallocate($im, 0, 0, 255);

		// String u gornjem lijevom kutu
		imagestring($im, 5, 0, 0, $tekst, $textcolor);

		// Output
		header('Content-type: image/png');

		imagepng($im);
		imagedestroy($im);

        echo "PNG slika je kreirana.";
    }
}

class Pdf {
     
    public function __construct() {
        //
    }
     
    public function fpdf181Create($tekst) {
        require('fpdf181/fpdf.php');
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(40,10,$tekst);
		$content = $pdf->Output('kreirani_dokument.pdf','F');
        echo "PDF datoteka je pohranjena.";
    }
}
 
class Txt {
 
    public function __construct() {
        //
    }
 
    public function PHPfwrite($tekst) {
		$myfile = fopen("kreirani_dokument.txt", "w") or die("Unable to open file!");
		$txt = $tekst;
		fwrite($myfile, $txt);
		fclose($myfile);
        echo "Txt datoteka je pohranjena.";
    }
}


// Jednostavni Interface za svaki adapter koji koristimo
interface spremiDatotekuAdapter {
    public function spremi($tekst);
}
 
// Adapter
class pdfAdapter implements spremiDatotekuAdapter { 
     
    private $pdf;
 
    public function __construct(Pdf $pdf) {
        $this->pdf = $pdf;
    }
     
    public function spremi($tekst) {
        $this->pdf->fpdf181Create($tekst);
    }
}

class txtAdapter implements spremiDatotekuAdapter {
 
    private $txt;
 
    public function __construct(Txt $txt) {
        $this->txt = $txt;
    }
 
    public function spremi($tekst) {
        $this->txt->PHPfwrite($tekst);
    }
}

class pngAdapter implements spremiDatotekuAdapter {
 
    private $png;
 
    public function __construct(Png $png) {
        $this->png = $png;
    }
 
    public function spremi($tekst) {
        $this->png->imagePngCreate($tekst);
    }
}


// Klijentski kod
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$tekst = $_POST["tekst"];
	$datoteka = $_POST["datoteka"];

	if($datoteka==".pdf"){
		$pdf = new pdfAdapter(new Pdf());
		$pdf->spremi($tekst);	
	}elseif
	($datoteka==".txt"){
		$txt = new txtAdapter(new Txt());
		$txt->spremi($tekst);
	}elseif
	($datoteka==".png"){
		$txt = new pngAdapter(new Png());
		$txt->spremi($tekst);
	}

}else{ ?>
	
	<form action="" method="post">
	  Novi tekstualni dokument:<br>
		<textarea name="tekst" rows="20" cols="100"></textarea>
		<br><br>
		Spremi kao: 
		<select name="datoteka">
		  <option value=".txt">.txt</option>
		  <option value=".pdf">.pdf</option>
		  <option value=".png">.png</option>
		</select>
		<br><br>
	  <input type="submit" value="Spremi">
	</form> 
	<?php
}

?>

