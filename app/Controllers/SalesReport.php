<?php

namespace App\Controllers;

use App\Models\SalesReportModel;
use CodeIgniter\Controller;

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use \PhpOffice\PhpSpreadsheet\IOFactory;

class SalesReport extends Controller
{
    public function __construct()
    {
        $this->SalesReportModel = new SalesReportModel();
        $this->session = session();
    }
	
    public function index()
    {
		$session = \Config\Services::session();
		
        if (!$this->session->get('logged_in')) 
		{
            return redirect()->to('/login');
        }

	   $data['srcstart'] =  $this->request->getPost('srcstart');
	   $data['srcend'] =  $this->request->getPost('srcend');
	   $data['srcstatus'] =  $this->request->getPost('srcstatus');
	   $data['srcseller'] =  $this->request->getPost('srcseller');
	   $data['list_seller'] =  $this->SalesReportModel->list_seller();
       $data['data'] = $this->SalesReportModel->data($this->request->getPost('srcstart'), $this->request->getPost('srcend'), $this->request->getPost('srcstatus'), $this->request->getPost('srcseller'), $session->get('user_id'), $session->get('group_code'));
		
        return view('salesreport/index', $data);
    }
	
	public function export_excel()
	{
		$session = \Config\Services::session();
		
		$start = $this->request->getGet('start');
		$end = $this->request->getGet('end');
		$status = $this->request->getGet('status');
		$seller = $this->request->getGet('seller');
		
		$data = $this->SalesReportModel->data($start, $end, $status, $seller, $session->get('user_id'), $session->get('group_code'));
		
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('assets/template_penjualan.xlsx');
		
        $sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('C4', date("d-m-Y", strtotime($start)));
		$sheet->setCellValue('C5', date("d-m-Y", strtotime($end)));
		
		$style_data = array(
			'font'  => array('size'  => 10),
			'borders' => array(
				'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '000'),
				),
			)
		);
		
		$style_total1 = array(
			'font'  => array('size'  => 10, 'bold' => true),
			'borders' => array(
				'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '000'),
				),
			),
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => array(
					'argb' => 'CCCCCC',
				),
				'endColor' => array(
					'argb' => 'CCCCCC',
				),
			),
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			)
		);
		
		$style_status_pending = array(
			'font'  => array('size'  => 10, 'bold' => true),
			'borders' => array(
				'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '000'),
				),
			),
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => array(
					'argb' => 'ffff9999',
				),
				'endColor' => array(
					'argb' => 'ffff9999',
				),
			)
		);
		
		$style_status_paid = array(
			'font'  => array('size'  => 10, 'bold' => true),
			'borders' => array(
				'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '000'),
				),
			),
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => array(
					'argb' => 'ffa4ffa4',
				),
				'endColor' => array(
					'argb' => 'ffa4ffa4',
				),
			)
		);
		
		$no = 1;
		$row = 8;
		$total = 0;
		
		foreach($data as $rec)
		{
			$sheet->setCellValue('A'.$row, $no++);
			$sheet->setCellValue('B'.$row, date("d-m-Y", strtotime($rec->created_at)));
			$sheet->setCellValue('C'.$row, $rec->nama_penjual);
			$sheet->setCellValue('D'.$row, $rec->name);
			$sheet->setCellValue('E'.$row, (substr($rec->phone, 0, 1) == '0') ? $rec->phone : "0".substr($rec->phone, 2));
			$sheet->setCellValue('F'.$row, $rec->address);
			$sheet->setCellValue('G'.$row, $rec->status);
			$sheet->setCellValue('H'.$row, $rec->nama_produk);
			$sheet->setCellValue('I'.$row, $rec->amount / $rec->harga);
			$sheet->setCellValue('J'.$row, $rec->amount / ($rec->amount / $rec->harga));
			$sheet->setCellValue('K'.$row, $rec->satuan);
			$sheet->setCellValue('L'.$row, $rec->amount);
			
			$total += $rec->amount;
			
			// $sheet->getStyle('A'.$row.':K'.$row)->applyFromArray($style_data);
			
			if($rec->status == 'paid')
			{
				$sheet->getStyle('A'.$row.':L'.$row)->applyFromArray($style_status_paid);
			}
			else
			{
				$sheet->getStyle('A'.$row.':L'.$row)->applyFromArray($style_status_pending);
			}
			
			$row++;
		}
		
		$sheet->setCellValue('A'.$row, 'TOTAL');
		$sheet->setCellValue('L'.$row, $total);
		
		$spreadsheet->getActiveSheet()->mergeCells('A'.$row.':K'.$row);
	
		$sheet->getStyle('A'.$row.':K'.$row)->applyFromArray($style_total1);
		$sheet->getStyle('L'.$row)->applyFromArray($style_total1);
        
		$writer = new Xlsx($spreadsheet);
		
		$filename = "LAPORAN PENJUALAN";
		
        $writer = new Xlsx($spreadsheet);
				
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		
		$writer->save('php://output');
		
		exit;
	}
	
	public function export_excsel()
	{
		$start = $this->request->getGet('start');
		$end = $this->request->getGet('end');
		$status = $this->request->getGet('status');
		
		$data = $this->SalesReportModel->data($start, $end, $status);
		
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=LAPORAN PENJUALAN.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		echo "<style>body {border-style:solid; border-width:thin; border-color:#ccc;}</style>";
		echo "<body>";
		
		echo "
		<h3>LAPORAN PENJUALAN</h3>
		<table width='100%' border='1'>
			<tr>
				<td colspan=\"11\" style=\"background:#ccc; font-size:12px;\"><b>FILTER</b></td>
			</tr>
			<tr>
				<td style=\"font-size:12px;\">TANGGAL (AWAL)</td>
				<td colspan=\"10\" style=\"text-align:left; font-size:12px;\">".date("d/m/Y", strtotime($start))."</td>
			</tr>
			<tr>
				<td style=\"font-size:12px;\">TANGGAL (AKHIR)</td>
				<td colspan=\"10\" style=\"text-align:left; font-size:12px;\">".date("d/m/Y", strtotime($end))."</td>
			</tr>
			<tr></tr>
			<tr>
				<th style='background:#ccc; font-size:12px;'>NO</th>
				<th style='background:#ccc; font-size:12px;'>TANGGAL TRANSAKSI</th>
				<th style='background:#ccc; font-size:12px;'>NAMA CUSTOMER</th>
				<th style='background:#ccc; font-size:12px;'>NO. HP CUSTOMER</th>
				<th style='background:#ccc; font-size:12px;'>ALAMAT CUSTOMER</th>
				<th style='background:#ccc; font-size:12px;'>STATUS</th>
				<th style='background:#ccc; font-size:12px;'>PRODUK</th>
				<th style='background:#ccc; font-size:12px;'>KUANTITI</th>
				<th style='background:#ccc; font-size:12px;'>HARGA SATUAN (Rp)</th>
				<th style='background:#ccc; font-size:12px;'>SATUAN</th>
				<th style='background:#ccc; font-size:12px;'>TOTAL (Rp)</th>
			</tr>
		";
			
		$no = 1;
		$total = 0;
		
		foreach($data as $rec)
		{
			echo "
				<tr>
					<td style='font-size:12px; text-align:center;'>".($no++)."</td>
					<td style='font-size:12px; text-align:center;'>".$rec->created_at."</td>
					<td style='font-size:12px;'>".$rec->name."</td>
					<td style='font-size:12px;' text-align:center;>(62) ".((substr($rec->phone, 0, 1) == '0') ? substr($rec->phone, 1) : substr($rec->phone, 2))."</td>
					<td style='font-size:12px;'>".$rec->address."</td>
					<td style='font-size:12px; text-align:left;'>".$rec->status."</td>
					<td style='font-size:12px;'>".$rec->nama_produk."</td>
					<td style='font-size:12px; text-align:center;'>".number_format(($rec->amount / $rec->harga), 0, ".", ",")."</td>
					<td style='font-size:12px; text-align:right;'>".number_format(($rec->amount / ($rec->amount / $rec->harga)), 0, ".", ",")."</td>
					<td style='font-size:12px; text-align:center;'>".$rec->satuan."</td>
					<td style='font-size:12px; text-align:right;'>".number_format($rec->amount, 0, ".", ",")."</td>
				</tr>
			";
			
			$total += $rec->amount;
		}
		
		echo "
			<tr>
				<td colspan=\"10\" style=\"background:#ccc; font-size:12px; text-align:right;\"><b>TOTAL</b></td>
				<td style=\"background:#ccc; font-size:12px;\"><b>".number_format($total, 0, ".", ",")."</b></td>
			</tr>
		";
		
		echo "</body>";
		echo "</table>";
	}
}
