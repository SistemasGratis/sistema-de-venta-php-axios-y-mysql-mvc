<?php
$id_compra = (empty($_GET['shoping'])) ? null : $_GET['shoping'] ;
if ($id_compra != null) {
    require_once '../../config.php';
    require_once '../../models/reporte.php';
    $compras = new Reporte();
    $datos = $compras->getConfiguracion();
    $result = $compras->getShoping($id_compra);
    $products = $compras->getProductsCompra($id_compra);

    require('../fpdf/fpdf.php');

    $pdf = new FPDF('P','mm',array(80, 200));
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(60,10,$datos['nombre'], 0, 1, 'C');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(60,5,utf8_decode('Telefono: ' . $datos['telefono']), 0, 1, 'C');
    $pdf->Cell(60,5,'Correo: '. $datos['email'], 0, 1, 'C');
    $pdf->Cell(60,5, utf8_decode('Dirección: '. $datos['direccion']), 0, 1, 'C');

    $pdf->Cell(60,5, '===============================', 0, 1, 'C');
    //########## Datos del cliente
    $pdf->Cell(60,5, utf8_decode('Nombre: '. $result['nombre']), 0, 1, 'C');
    $pdf->Cell(60,5, utf8_decode('Telefono: '. $result['telefono']), 0, 1, 'C');
    $pdf->Cell(60,5, utf8_decode('Dirección: '. $result['direccion']), 0, 1, 'C');
    

    $pdf->Cell(60,5, '===============================', 0, 1, 'C');

    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(20,5, utf8_decode('Cant - Precio: '), 0, 0, 'C');
    $pdf->Cell(40,5, utf8_decode('Producto: '), 0, 1, 'C');
    $pdf->SetFont('Arial','', 11);
    $total = 0;
    foreach ($products as $product) {
        $total += $product['cantidad'] * $product['precio'];
        $pdf->Cell(20,5, $product['cantidad'] . ' x ' . $product['precio'], 0, 0, 'C');
        $pdf->MultiCell(40,5, $product['descripcion'], 0,'C');
        $pdf->Cell(60,5, number_format($product['cantidad'] * $product['precio'], 2), 0, 1, 'R');
        $pdf->Cell(60,5, '------------------------------------------------------', 0, 1, 'C');
    }
    $pdf->Cell(60,5, 'Total: ' . number_format($total, 2), 0, 1, 'R');

    $pdf->Output();
}else{
    echo '<div class="text-center">
    <div class="error mx-auto" data-text="404">404</div>
    <p class="lead text-gray-800 mb-5">Page Not Found</p>
</div>';
}
