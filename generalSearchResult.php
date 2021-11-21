<?php
include 'header.php';
//wordt gebruikt om te checken welk product er toegevoegd moet worden aan het winkelmandje
$stockKey = filter_input(INPUT_GET, "stockKey", FILTER_SANITIZE_NUMBER_INT);
//wordt gebruikt om te checken welk aantal er toegevoegd moet worden aan het winkelmandje
$amount = filter_input(INPUT_GET, "amount", FILTER_SANITIZE_NUMBER_INT);
//geeft aan waar hij op moet sorteren
$orderby = filter_input(INPUT_GET, "orderby", FILTER_SANITIZE_STRING);
//standaardwaarden van de gebruikte variabelen
$aantalresultaten = 0;
$showaantal = 0;
$order = "S.StockItemName ASC";
$stockVar = 1;
//haalt de ingetypte zoekopdracht op
$zoekOpdracht = filter_input(INPUT_GET, "zoekOpdracht", FILTER_SANITIZE_STRING);

//geeft aan waar op gesorteerd moet worden
if ($orderby == "a-z") {
    $order = "S.StockItemName ASC";
} elseif ($orderby == "z-a") {
    $order = "S.StockItemName DESC";
} elseif ($orderby == "price-lh") {
    $order = "S.UnitPrice ASC";
} elseif ($orderby == "price-hl") {
    $order = "S.UnitPrice DESC";
}

//zorgt ervoor dat we meer prodcuten kunnen laden
if (filter_input(INPUT_GET, "meerladen", FILTER_SANITIZE_STRING) != "") {
    $momenteelGeladen = filter_input(INPUT_GET, "meerladen", FILTER_SANITIZE_STRING) + 9;
} else {
    $momenteelGeladen = 9;
}

//zoekopdracht query
$query = "SELECT DISTINCT G.StockItemID, S.StockItemName, S.UnitPrice FROM stockitemstockgroups AS G JOIN stockitems AS S ON G.StockItemID = S.StockItemID WHERE S.StockItemName LIKE '%$zoekOpdracht%' OR G.StockItemID LIKE '%$zoekOpdracht%' OR S.UnitPrice LIKE '%$zoekOpdracht%' OR S.MarketingComments LIKE '%$zoekOpdracht%' OR S.Tags LIKE '%$zoekOpdracht%' ORDER BY $order";
$zoekresultatenTeller = $query.";";
$search = $query." LIMIT $momenteelGeladen;";
?>

<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <a class="navbar-brand" href="designProjectRick/index.php"><img style="height: 80px; margin-top: 20px; margin-bottom: 20px;" src="img/wwi_big.png"></a>
            <ul class="list-group">
                <form action="" method="get">
                    <div class="row">
                        <div class="col">
                            <!-- geeft aan welke manier van sorteren er gebruikt moet worden -->
                            <select style="width: 170px;" class="form-control" name="orderby">
                                <option value="a-z" <?php if ($orderby == "a-z") {echo "selected";} ?>>A-Z</option>
                                <option value="z-a" <?php if ($orderby == "z-a") {echo "selected";} ?>>Z-A</option>
                                <option value="price-lh" <?php if ($orderby == "price-lh") {echo "selected";} ?>>Price Low/High</option>
                                <option value="price-hl" <?php if ($orderby == "price-hl") {echo "selected";} ?>>Price High/Low</option>
                            </select>
                        </div>
                        <div class="col">
                            <?php
                            echo '<input type="hidden" name="zoekOpdracht" value="'.$zoekOpdracht.'">';
                            ?>
                            <button type="submit" class="btn btn-primary">Sort</button>
                        </div>
                    </div>
                </form>
                <br><br>
                <?php
                //laat de groepzoekmachine
                foreach ($db->query($groupName) as $row) {
                    echo "<li class='list-group-item'>";
                    echo '<form action="groupSearchResult.php" method="get">';
                    echo '<button type="submit" name="StockGroupID" value="'.$stockVar.'"><h6><img style="height: 10px;" src="img/arrow.png" alt="">'.$row['StockGroupName'].'</h6></button></form>';
                    echo "</li>";
                    $stockVar++;
                }
                ?>
            </ul>
        </div>
        <!-- /.col-lg-3 -->
        <div class="col-lg-9">
            <div class="row">
                <div class="col-sm-12 text-right">
                    <?php
                    //geeft aan hoeveel producten er worden geladen van de aanwezige producten
                    foreach ($db->query($zoekresultatenTeller)as $row) {
                        $showaantal ++;
                    }
                    foreach ($db->query($search)as $row) {
                        $aantalresultaten ++;
                    }
                    echo "<br><span style='margin-right: 35px; font-weight: bold;'>Showing ".$aantalresultaten." of ".$showaantal." results</span>"; ?>
                </div>
            </div>
            <?php
            $float = 1;
            $idcounter = 1;
            foreach ($db->query($search)as $row) {
                //data van de zoekresultaten worden geladen
                //idcounter wordt gebruikt voor het laden van meer producten
                echo '<div id="loadmore'.$idcounter.'" class="card text-center" style="width:260px; background: white; margin-top: 25px; margin-left: 4px; float: left;">';
                $idcounter++;
                echo '<div class="card-body" style="height: 80px;">';
                echo "<h6><a href='productPagina.php?stockKey=".$row['StockItemID']."'>".$row['StockItemName']."</a></h6>";
                echo "</div>";
                echo '<a href="productPagina.php?stockKey='.$row['StockItemID'].'"><img class="card-img-top img-fluid" src="img/150x150.png" alt=""></a>';
                echo '<div class="card-body">';
				$gemRating2 = "SELECT ROUND(AVG(Rating)) Gem FROM customerreview WHERE StockItemID = ".$row['StockItemID'];
                //de review rating per product rating
                foreach ($db->query($gemRating2) as $row2) {
					$goldStar = $row2['Gem'];
					$legeStar = 5 - $row2['Gem'];
					echo "<span style='font-size: 20px' class='text-warning'>";
					for ($index = 1; $index <= $goldStar ; $index++) {
						echo "&#9733";
					}
					echo "</span>";
					echo "<span style='font-size: 20px' class='text-warning'>";
					for ($index = 1; $index <= $legeStar; $index++) {
						echo "&#9734";
					}
					echo "</span><br>";
				}
                echo "<a href='productPagina.php?stockKey=".$row['StockItemID']."'>"."More information</a>";
                echo "<h6>$".$row['UnitPrice'].'</h6>';
                echo '<form action="" method="get">
                      <input style="float: top; margin-bottom: 10px;" type="number" name="amount" value=';
                if ($row['StockItemID'] == $stockKey) {
                    echo $amount;
                } else {
                    echo '1';
                }
                $code = $row['StockItemID'];
                $productVoorraad =  "SELECT QuantityOnHand FROM stockitemholdings WHERE StockItemID = '$code'";
                foreach ($db->query($productVoorraad) as $ding) {
                    echo ' min=1 max='.$ding["QuantityOnHand"].' step=1><br>';
                }
                echo '<input type="hidden" name="stockKey" value='.$row["StockItemID"].'>
                      <input type="hidden" name="zoekOpdracht" value='.$zoekOpdracht.'>
                      <input type="hidden" name="orderby" value='.$orderby.'>
                      <button name = "winkelmandje_knop" class="btn btn-outline-default my-2 my-sm-0" type="submit">Add to cart <img style="height: 23px; "src="img/cart.png" alt=""></button>';
                if (filter_has_var(INPUT_GET, "winkelmandje_knop") && $row['StockItemID'] == $stockKey) {
                    if (isset($_SESSION["winkelmandje"][$stockKey])) {
                		$_SESSION["winkelmandje"][$stockKey] += $amount;
					} else {
						$_SESSION["winkelmandje"]["$stockKey"] = $amount;
					}
                }
                echo '</form></div></div>';
            }
            ?>
        </div>
    </div>
</div>
<?php
//de +9 knop
if ($aantalresultaten < $showaantal) {
    echo '<div class="row">
              <div class="col-sm-12 text-center">
                  <form class="" action="#loadmore'.$momenteelGeladen.'" method="get">
                      <input type="hidden" name="zoekOpdracht" value="'.$zoekOpdracht.'">
                      <input type="hidden" name="orderby" value="'.$orderby.'">
                      <button class="btn btn-secondary" style="margin-left: 10px; margin-top: 20px;" type="submit" name="meerladen" value="'.$momenteelGeladen.'">Load 9 more
                  </form>
              </div>
          </div>';
}
?>
<div class="row">
    <div class="col-sm-12 text-right">
        <?php
        //geeft aan hoeveel producten er worden geladen van de aanwezige producten
        echo "<br><p style='margin-right: 320px; font-weight: bold;'>Showing ".$aantalresultaten." of ".$showaantal." results</p>";
        ?>
    </div>
</div>
<?php
include 'footer.php'
?>
