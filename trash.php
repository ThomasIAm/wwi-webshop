<?php
include 'databaseHandler.php';
?>

<div class="col-sm-3">
	<div class="main">
		<div class="productboxTop">
			<?php
			foreach ( $db->query( $test ) as $row ) {
				echo "<a href='../productPagina?stockKey=" . $row[ 'StockItemID' ] . "'><h3>" . $row[ 'StockItemName' ] . '</h3></a>';
				echo "<img src='" . $row[ 'StockImg' ] . "'>";
				echo "hello";
			}
			?>
		</div>
		<div class="productprice">
			<span class="pricetext">$8.95</span>
			<a href="#" id="toCart" class="btn btn-primary btn-sm" role="button">Add to cart </a>
		</div>
	</div>
</div>