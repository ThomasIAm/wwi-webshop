<?php include 'header.php' ?>
<div class="container content">
	<?php
	//De tekst die getoond wordt als de winkelmand leeg is
	$emptyerror = "Your shopping cart is currently empty";
	$empty = FALSE;
	//check of de winkelmand leeg is
	if (filter_has_var(INPUT_GET, 'clear')) {
		session_destroy();
		echo '<br><h1>'.$emptyerror.'</h1>';
		$empty = TRUE;
	} else {
		$stockKey = filter_input(INPUT_GET, "stockKey", FILTER_SANITIZE_NUMBER_INT);

		$totaalprijs = 0;

		// winkelwagen moet geüpdatet worden
		if (filter_has_var(INPUT_GET, 'update')) {
			// haal alle artnrs uit GET
			$artikelnrs = array_keys($_GET);

			// unset alle artnrs waar niks mee moet gebeuren
			foreach ($artikelnrs as $key => $value) {
				if ($value == "update" || $value == "") {
					unset($artikelnrs[$key]);
				}
			}

			// update winkelmandje
			foreach ($artikelnrs as $key => $value) {
				// filter_input() werkt niet met onbekende reden
				//$aantal = filter_input(INPUT_GET, $value, FILTER_SANITIZE_NUMBER_INT);
				
				// onveilige definitie
				$aantal = $_GET[$value];
				$_SESSION["winkelmandje"][$value] = $aantal;
			}
		}

		if(isset($_SESSION["winkelmandje"])) {
			//verwijder artikel uit winkelmand
			if (filter_has_var(INPUT_GET, "deleteKnop")) {
				if (isset($_SESSION["winkelmandje"][$stockKey])) {
					unset($_SESSION["winkelmandje"][$stockKey]);
				}
			}
			if (!empty($_SESSION["winkelmandje"])) {
				echo '<br><h1>Shopping cart</h1><br><br>';
				//toon inhoud winkelmandje
				foreach($_SESSION["winkelmandje"] as $code => $aantal) {
					if(array_key_exists($code, $_SESSION["winkelmandje"])) {
						?>
						<div class="winkelmandrij">
							<?php
							//queries om gegevens op te halen
							$afbeeldingQuery = "SELECT StockImg FROM stockimg WHERE StockItemID = '$code'";
							$naamQuery = "SELECT StockItemName FROM stockitems WHERE StockItemID = '$code'";
							$prijsQuery =  "SELECT UnitPrice FROM stockitems WHERE StockItemID = '$code'";
							$voorraadQuery = "SELECT QuantityOnHand FROM stockitemholdings WHERE StockItemID = '$code'";

							echo '<div class="row"><div class="col-sm-6" style="height: 200px;">';

							//toon eerste afbeelding van het product
							foreach ($db->query($afbeeldingQuery) as $row) {
								echo '<a href="productPagina.php?stockKey='.$code.'"><img style="height: 200px; float: left;" class="d-block img-fluid" src="'.$row['StockImg'].'"></a>';
								break;
							}

							//toon productnaam
							foreach ($db->query($naamQuery) as $row) {
								echo '<h5 style="color: #0069D9;"><a href="productPagina.php?stockKey='.$code.'">'.$row['StockItemName'].'</a></h5>';
							}

							//variabele aanmaken om prijs op te tellen
							$prijs = 0;

							//toon productprijs
							foreach ($db->query($prijsQuery) as $row) {
								echo '<b>$'.$row['UnitPrice'].'</b><br>';
								$prijs = $row["UnitPrice"];
							}

							//toon voorraad
							foreach ($db->query($voorraadQuery) as $row) {
								echo 'Quantity on hand: '.$row['QuantityOnHand'].'<br></div>';
							}

							echo '<div class="col-sm-6" style="height: 200px;"><form action="" method="get">';

							//veld om aantal producten aan te passen
							$productVoorraad =  "SELECT QuantityOnHand FROM stockitemholdings WHERE StockItemID = '$code'";
							foreach ($db->query($productVoorraad) as $row) {
								echo '<input style="float: top; margin-bottom: 10px;" type="number" name="'.$code.'" value="'.$aantal.'" max="'.$row["QuantityOnHand"].'" min="1" step="1">';
							}

							//knop om de waarde te updaten
							echo '&nbsp&nbsp&nbsp<button type="submit" name="update" class="btn btn-secondary">Update</button>';

							echo '</form>';

							//totaalprijs voor het bepaalde product wordt berekend
							$productprijs = $prijs * $aantal;

							//knop om artikel te verwijderen en totaalprijs voor het product wordt getoond
							echo '<form action="" method="get">
								  <input type="hidden" name="stockKey" value='.$code.'>
								  <button style="float: right;" name="deleteKnop" type="submit" class="btn btn-danger">X</button>
								   <h6 style="float: right; margin-right: 20px; margin-top: 9px;">$'.number_format($productprijs,2).'</h6>
								  </form></div></div><hr>';

							//productprijs wordt bij de totaalprijs opgeteld
							$totaalprijs += $productprijs;
							?>
						</div>
						<?php
					}
				}
				//totale prijs wordt getoond
				echo '<div class="row"><div class="col-sm-12"><br><br><br><h4 style="float: right;">Subtotal: $'.number_format($totaalprijs,2).'</h4></div>';
				echo '<div class="col-sm-12"><form action="" method="get">';
				//knop om de winkelmand te legen
				echo '<button style="float:right; margin-top: 20px;" type="submit" name="clear" class="btn btn-danger">Clear cart</button>';
				echo '</form></div></div>';
			} else {
				echo '<br><h1>'.$emptyerror.'</h1>';
				$empty = TRUE;
			}
		} else {
			echo '<br><h1>'.$emptyerror.'</h1>';
			$empty = TRUE;
		}
	}
	//knop om door te gaan naar de checkout, als het winkelmandje niet leeg is
	if ($empty == FALSE) {
		echo '<form action="account.php" method="get">
				  <button type="submit" name="submit" class="btn btn-primary">Proceed to checkout</button>
			  </form>';
	}
	?>
</div>

<?php include 'footer.php' ?>
