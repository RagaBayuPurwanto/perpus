<?php
//koneksi database
$db_host	= "localhost";
$db_user	= "root";
$db_pass	= "";
$db_name	= "perpus";

$konek	= mysql_connect($db_host,$db_user,$db_pass,$db_name) or die ("Gagal koneksi ke server");
mysql_select_db($db_name, $konek) or die ("Gagal mengaktifkan database".mysql_error());
 
//akhir koneksi


//mengambil data dari tabel dan memasukkannya dalam array
$query = "SELECT guru_tr.idtrx, guru_tr.id, guru_tr.judul, guru_tr.nip, guru.nama, guru_tr.tgl_pinjam, guru_tr.tgl_kembali, guru_tr.jmlpinjam from guru_tr,guru where guru_tr.nip=guru.nip order by idtrx";
$sql = mysql_query($query);
$data = array();
if ($sql === FALSE) {
	die(mysql_error());
}
while ($row = mysql_fetch_assoc($sql)) {
	array_push($data, $row);
}

//setting judul laporan dan header tabel
$judul = "LAPORAN PEMINJAMAN PENGUNJUNG";
$subjudul = "PERPUSTAKAAN DESA";
$header = array(
	array("label"=>"idtrx","length"=>15, "align"=>"L"),
	array("label"=>"ID","length"=>15, "align"=>"L"),
	array("label"=>"JUDUL","length"=>120, "align"=>"L"),
	array("label"=>"NAMA","length"=>30, "align"=>"L"),
	array("label"=>"Tgl Pinjam","length"=>23, "align"=>"L"),
	array("label"=>"Tempo","length"=>23, "align"=>"L"),

	array("label"=>"Jumlah","length"=>15, "align"=>"L")
	);

//sertakan library FPDF
require('fpdf17/fpdf.php');
$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();

//tampilkan judul laporan
$pdf->SetFont('Times','B',16);
$pdf->Cell(0,0, $judul,'0', 1, 'C');

//meletakkan gambar
$pdf->Image('mylogo.jpg',10,8);
//tampilkan subjudul laporan
$pdf->SetFont('Courier','B',14);
$pdf->Cell(0,20, $subjudul,'0', 1, 'C');

//buat header tabel
$pdf->Line(10,25,286,25);
$pdf->SetFont('Times','B','12');
$pdf->SetFillColor(173,255,47);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(128,0,0);
foreach ($header as $kolom) {
	$pdf->Cell($kolom['length'], 5, $kolom['label'], 1, '0', $kolom['align'], true);
}
$pdf->Ln();

//tampilkan data tabelnya
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
$fill=false;
foreach ($data as $baris) {
	$i = 0;
	foreach ($baris as $cell) {
		$pdf->Cell($header[$i]['length'], 5, $cell, 1, '0', $kolom['align'], $fill);
		$i++;
	}
	$fill = !$fill;
	$pdf->Ln();
}

//Output File PDF
$pdf->Output($name='laporan-pinjam-guru.pdf', $dest='I');
?>