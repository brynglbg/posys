<?php
$id = $this->input->get('id');
if(!$id){ show_404(); }
$po = $this->BaseModel->r_tbl('purchase', false, ['id' => $id]);
$po_product = $this->BaseModel->r_tbl('purchase_product', true, ['purchase_id' => $id]);
$long_id = sprintf('%06d', $id);
$w0 = 190;
$h0 = 6;
// START OUTPUT
$pdf = new FPDF('P', 'mm', 'A4'); // 210 mm × 297 mm less the margin 190 x 277
$pdf->SetTitle("PO#$long_id - $po->vendor_name");
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($w0, $h0, sys()->name, 0, 0, 'L');
$pdf->Ln();
$pdf->Cell($w0, $h0, sys()->address, 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($w0, $h0, 'Purchase Order Form', 0, 0, 'L');
$pdf->Ln($h0 * 3);
$w1 = 155;
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($w0 - $w1, $h0, 'Date:', 0, 0, 'L');
$pdf->Cell(1, $h0, date('l, F j, Y', strtotime($po->created_at)), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell($w0 - $w1, $h0, 'PO Number:', 0, 0, 'L');
$pdf->Cell(1, $h0, $long_id, 0, 0, 'L');
$pdf->Ln();
$pdf->Cell($w0 - $w1, $h0, 'Supplier Name:', 0, 0, 'L');
$pdf->Cell(1, $h0, $po->vendor_name, 0, 0, 'L');
$pdf->Ln();
$pdf->Cell($w0 - $w1, $h0, 'Agent:', 0, 0, 'L');
$pdf->Cell(1, $h0, $po->vendor_agent_name, 0, 0, 'L');
$pdf->Ln($h0 * 3);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($w0, $h0, 'ORDER DETAILS', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell($w0 - $w1, $h0, 'QTY.', 1, 0, 'C');
$pdf->Cell($w1, $h0, 'DESCRIPTION', 1, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
// for($i = 1; $i <= 40; $i++): // for sampling only
foreach($po_product as $pp):
$pdf->Cell($w0 - $w1, $h0, $pp->qty, 1, 0, 'C');
$pdf->Cell($w1, $h0, $pp->name, 1, 0, 'L');
$pdf->Ln();
endforeach;
// endfor;
$pdf->Ln($h0 * 10);
$pdf->Cell($w0, $h0 * 3, 'Payment Terms', 'T', 0, 'L');
$pdf->Ln();
$pdf->Cell($w0, $h0 * 3, 'Authorized By:', 'B', 0, 'L');
$pdf->Ln();
// END OUTPUT
$pdf->Output('I', "PO#$long_id - $po->vendor_name.pdf");
