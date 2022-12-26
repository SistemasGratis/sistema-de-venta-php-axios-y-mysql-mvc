<?php
$id_venta = (empty($_GET['sale'])) ? null : $_GET['sale'] ;
?>
<iframe src="<?php echo RUTA . 'views/ventas/ticket.php?sale='. $id_venta; ?>" frameborder="0">
</iframe>