<?php
$id_compra = (empty($_GET['shoping'])) ? null : $_GET['shoping'] ;
?>
<iframe src="<?php echo RUTA . 'views/compras/ticket.php?shoping='. $id_compra; ?>" frameborder="0">
</iframe>