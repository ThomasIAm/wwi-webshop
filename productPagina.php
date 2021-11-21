<?php
include 'redirect.php';
include 'header.php';

$stockKey = filter_input(INPUT_GET, "stockKey", FILTER_SANITIZE_NUMBER_INT);

?>

<div class="padding">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-left">
                <!--Product Naam-->
                <?php
                foreach ($db->query($productNaam) as $row) {
                    echo "<h3 style='margin-top: 20px;'>".$row['StockItemName'].'<h3/>';
                }
                ?>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5 text-center" style="height: 300px;">
                <!--Product Afbeeldingen-->
                <div id="carouselExampleIndicators" class=" card carousel slide my-4" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <?php $teller1 = 0;
                        foreach ($db->query($productAfbeelding) as $row) {
                            $teller1++;
                            if ($teller1 > 1) {
                                echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$teller1.'"></li>';
                            }
                        }
                        ?>
                        <?php
                        foreach ($db->query($productVideo) as $row) {
                            if ($row != '') {
                                echo '<li data-target="#carouselExampleIndicators" data-slide-to="3"></li>';
                            }
                        }
                        ?>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <!--Afbeelding 1-->
                        <?php
                        echo '<div class="carousel-item active">';
                        foreach ($db->query($productAfbeelding) as $row) {
                            echo '<img class="d-block img-fluid" src="'.$row['StockImg'].'">';
                            break;
                        }
                        echo '</div>';
                        ?>
                        <!--Afbeelding 2 en meer -->
                        <?php
						$teller2 = 0;
                        foreach ($db->query($productAfbeelding) as $row) {
                            $teller2++;
                            if ($teller2 > 1) {
                                echo '<div class="carousel-item">';
                                echo '<img class="d-block img-fluid" src="'.$row['StockImg'].'">';
                                echo '</div>';
                            }
                        }
                        ?>
                        <!--Video-->
                        <?php
                        echo '<div class="carousel-item">';
                        foreach ($db->query($productVideo) as $row) {
                            echo '<iframe width="500" height="395" src="https://www.youtube.com/embed/'.$row['StockVid'].'"></iframe>';
                            break;
                        }
                        echo '</div>';
                        ?>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Vorige</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Volgende</span>
                    </a>
                </div>
            </div>
            <div class=" col-sm-7 text-left" style="margin-top: 20px;">
                <!--Product Prijs-->
                <?php
                foreach ($db->query($productPrijs) as $row) {
                    echo "<h3>$ ".$row['UnitPrice'].'<h3/>';
                }
                ?>
                <!--Product Voorraad-->
                <?php
                foreach($db->query($productVoorraad) as $row) {
                    if($row['QuantityOnHand'] > 0){
                        echo "<p style='font-size: 15px; color: green;'>Quantity on hand: ".$row['QuantityOnHand'].'<p/>';
                    } else {
                        echo "<p style='font-size: 15px; color: red;'>Product not available<p/>";
                    }
                }
                ?>
                <!--Product Details-->
                <h4 style="margin-top: 20px; color: #007BFF; font-weight: bold;">Product specifications</h4>
                <?php
                foreach ($db->query($productOmschrijving) as $row) {
                    //Omschrijving
                    echo '<h6><img style="height: 20px; margin-right: 6px;" src="img/check.png" alt="">';
                    echo "Product: ".$row['SearchDetails'].' <h6/>';
                }

				//artikelnr
				echo '<h6><img style="height: 20px; margin-right: 6px;" src="img/check.png" alt="">';
				echo "Productnr: ".$stockKey;

                foreach($db->query($productGewicht) as $row){
                    if($row['TypicalWeightPerUnit'] >= 1){
						//Gewicht in kilo
                        echo '<h6><img style="height: 20px; margin-right: 6px;" src="img/check.png" alt="">';
                        echo "Weight: ".round($row['TypicalWeightPerUnit'],1)."kg";
                    } else {
                    	//Gewicht in gram
                        $gram = $row['TypicalWeightPerUnit'] * 1000;
                        echo '<h6><img style="height: 20px; margin-right: 6px;" src="img/check.png" alt="">';
                        echo "Weight: ".$gram."g";
                    }
                }

                foreach ($db->query($productAantal) as $row) {
                    //Aantal per verpakking
                    echo '<h6><img style="height: 20px; margin-right: 6px;" src="img/check.png" alt="">';
                    echo "Quantity: ".$row['QuantityPerOuter'].' per package<h6/>';
                }

                foreach ($db->query($productTag) as $row) {
                    //Tags
                    $tags = str_replace("[", "", $row['Tags']);
                    $tags = str_replace("]", "", $tags);
                    $tags = str_replace("\"", "", $tags);
                    $tags = str_replace(",", ", ", $tags);
                    if ($tags != "") {
                        echo '<h6><img style="height: 20px; margin-right: 6px;" src="img/check.png" alt="">';
                        if ($tags == str_replace(",", "", $tags)) {
                            echo "Tag: ".$tags."</h6>";
                        } else {
                            echo "Tags: ".$tags."</h6>";
                        }
                    }
                }
                echo '<h6><img style="height: 20px; margin-right: 6px;" src="img/check.png" alt="">';
                $count = 0;
                foreach ($db->query($productCategory) as $row) {
                    $count ++;
                }
                if ($count == 1) {
                    echo "Category: ";
                } else {
                    echo "Categories: ";
                }
                foreach ($db->query($productCategory) as $row) {
                    //Categorienummers
                    $test = $row['StockGroupID'];
                    $query = "SELECT StockGroupName FROM stockgroups WHERE StockGroupID = $test";
                    foreach ($db->query($query) as $row1) {
                        echo $row1['StockGroupName'];
                        if ($count > 1) {
                            echo ", ";
                        }
                        $count --;
                    }
                }
                echo "</h6>";
                foreach($db->query($productHerkomst) as $row) {
                    //Productherkomst
                    if(preg_match("/(CountryOfManufacture)/",$row['CustomFields'])){
                        $explode = explode("\"",$row['CustomFields']);
                        echo '<h6><img style="height: 20px; margin-right: 6px;" src="img/check.png" alt="">';
                        print "Country of manufacture: ".$explode[3]."</h6>";
                    }
                }
                foreach ($db->query($productTemperatuur) as $row) {
                    //$productTemperatuur
                    if ($row['Temperature'] != '') {
                        echo '<h6><img style="height: 20px; margin-right: 6px;" src="img/check.png" alt="">';
                        echo "Temperature in storage: ".$row['Temperature']."°C</h6>";
                    }
                }
                ?>
                <h5 style="float: left; margin-top: 2px;"><span class="text-warning">
				<?php
				foreach ($db->query($gemRating) as $row) {
					$goldStar = $row['Gem'];
					$legeStar = 5 - $row['Gem'];
					echo "<span>";
					for ($index = 1; $index <= $goldStar ; $index++) {
						echo "&#9733";
					}
					echo "</span>";
					echo "<span>";
					for ($index = 1; $index <= $legeStar; $index++) {
						echo "&#9734";
					}
					echo "</span>";
				}
				?>
				</h5><br><br>
				<!-- form voor de winkelwagenknop -->
                <form action="" method="get">
                    <?php
					// input om het toe te voegen aantal aan te passen
                    echo '<input style="float: left; margin-bottom: 10px; margin-top: 5px;" type="number" name="amount" value='.$amount;
					// haal de huidige voorraad van een product op
                    $productVoorraad =  "SELECT QuantityOnHand FROM stockitemholdings WHERE StockItemID = '$stockKey'";
                    foreach ($db->query($productVoorraad) as $ding) {
                        echo ' min=1 max='.$ding["QuantityOnHand"].' step=1>';
					}
                    ?>
					<!-- geef het huidige artnr mee zodat er na een refresh weer de productpagina getoond wordt -->
                    <input type="hidden" name="stockKey" value="<?php print($stockKey);?>">
					<!-- add to cart button -->
                    <button style="margin-left: 25px; margin-bottom: -2px; width: 135px; float: left;" name = "winkelmandje_knop" class="btn btn-outline-default" type="submit" <?php foreach($db->query($productVoorraad) as $row){if($row["QuantityOnHand"] < 1){echo 'disabled';}} ?>>Add to cart <img style="height: 20px; "src="img/cart.png" alt=""></button>

                    <?php
					// wordt uitgevoerd als er een product toegevoegd moet worden
                    if (filter_has_var(INPUT_GET, "winkelmandje_knop")) {
                        if (isset($_SESSION["winkelmandje"][$stockKey])) {
							// product zit al in winkelmandje, dus optellen
                    		$_SESSION["winkelmandje"][$stockKey] += $amount;
						} else {
							// product zit nog niet in winkelmandje, dus aanmaken
							$_SESSION["winkelmandje"]["$stockKey"] = $amount;
						}
                    }
                    ?>
                </form>
            </div>
        </div>
        <div class="row">
			<div class="col-sm-12 text-left">
				<!--Product Omschrijving-->
				<div class="row">
					<h4 style="margin-left: 14px; margin-top: 100px; color: #007BFF; font-weight: bold;">Product description</h4>
					<p style="margin-left: 14px; margin-right: 20px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa </p>
				</div>
			</div>

		</div>

		<!-- review input -->
		<div class="row">
			<div class="col-sm-3" style="margin-right: 15px;">
				<form method="get" accept-charset="utf-8">
					<div class="form-group">
						<h4 style="margin-top: 20px; color: #007BFF; font-weight: bold;">Review this product</h4>
						<label for="rating">Rating:</label>
						<br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="rating" id="1" value="1">
							<label class="form-check-label" for="1">1</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="rating" id="2" value="2">
							<label class="form-check-label" for="2">2</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="rating" id="3" value="3">
							<label class="form-check-label" for="3">3</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="rating" id="4" value="4">
							<label class="form-check-label" for="4">4</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="rating" id="5" value="5">
							<label class="form-check-label" for="5">5</label>
						</div>
					</div>
					<div class="form-group">
						<label for="review">Review:</label>
						<textarea class="form-control" id="review" rows="3" name="review"></textarea>
						<input type="hidden" name="stockKey" value="<?php echo $stockKey?>" id="StockItemID">
					</div>
					<button type="submit" class="btn btn-primary" name="submit">Submit Review</button>
				</form>
				<?php
				// wordt uitgevoerd als er een review geplaatst moet worden
				if(isset($_SESSION['loggedIn']) && filter_has_var(INPUT_GET, 'submit')){
					// sla het CustomerAccountID op
					$customer = $_SESSION['loggedIn'];

					if (!filter_has_var(INPUT_GET, 'rating')) {
						// error voor niet ingevulde rating
						echo "<h5 class='text-center' style='color: red;'>Please rate the product</h5>";
					} else {
						if (filter_input(INPUT_GET, 'rating', FILTER_SANITIZE_NUMBER_INT) == "") {
							// error voor niet ingevulde rating
							echo "<h5 class='text-center' style='color: red;'>Please rate the product</h5>";
						} else {
							// sla de rating op om later te verwerken
							$rating = filter_input(INPUT_GET, 'rating', FILTER_SANITIZE_NUMBER_INT);
						}
					}

					if (!filter_has_var(INPUT_GET, 'review')) {
						echo "<h5 class='text-center' style='color: red;'>Please review the product</h5>";
					} else {
						if (filter_input(INPUT_GET, 'review', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK, FILTER_FLAG_ENCODE_AMP) == "") {
							echo "<h5 class='text-center' style='color: red;'>Please review the product</h5>";
						} elseif (isset($rating)) {
							$review = filter_input(INPUT_GET, 'review', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK, FILTER_FLAG_ENCODE_AMP);
							$stockKey = filter_input(INPUT_GET, 'stockKey', FILTER_SANITIZE_NUMBER_INT);
							$sql = "INSERT INTO wideworldimporters.customerreview VALUES ('$customer','$stockKey','$rating','$review')";
							$db->exec($sql);
						}
					}
				} elseif (filter_has_var(INPUT_GET, 'submit')) {
					redirect("loginhead.php?stockKey=".filter_input(INPUT_GET, 'stockKey', FILTER_SANITIZE_NUMBER_INT));
				}
				?>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<?php
				//RICK
				$Querryreview = "SELECT CustomerNAWFirstname, Rating, Review, StockItemID FROM customerreview Cr JOIN customeraccounts Ca USING(CustomerAccountID)";
				foreach ($db->query($Querryreview) as $review) {
					if ($review['StockItemID']==$stockKey){
						echo "<hr>";
						echo "<h5>".$review["CustomerNAWFirstname"]."</h5>";
						echo "<span style='font-size: 40px' class='text-warning'>";
						for ($index = 1; $index <= $review["Rating"] ; $index++) {
							echo "&#9733";
						}
						echo "</span>";
						echo "<span style='font-size: 40px' class='text-warning'>";
						for ($index = 1; $index <= 5 - $review["Rating"]; $index++) {
							echo "&#9734";
						}
						echo "</span>";
						print "<p>".$review["Review"]."</p>";
					}
				}
				?>
			</div>

		</div>
	</div>
	<hr>
</div>
<?php include 'footer.php' ?>
