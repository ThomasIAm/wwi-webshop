<?php
include 'header.php'
?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <a class="navbar-brand" href="index.php"><img style="height: 80px; margin-top: 20px; margin-bottom: 20px;" src="img/wwi_big.png"></a>
            <ul class="list-group">
                <?php
                $stockVar = 1;
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
                <img style="margin-top: 30px; height:650px; width: 824px;" src="img/300x300.png" alt="">
            </div>

    </div>
    <div class="row">
        <div class="col-sm-12 text-left">
			<h2 style="margin-top: 20px; margin-left: 2px;">Popular Products</h2>
        </div>
        <?php
        $float = 1;
		//selecteer de producten met de meeste voorraad
		$popularquery = "SELECT q.StockItemID, StockItemName, UnitPrice FROM stockitems q JOIN stockitemholdings USING (StockItemID) ORDER BY QuantityOnHand DESC LIMIT 4";
        foreach ($db->query($popularquery)as $row) {
            //laad popular products
            echo '<div class="card text-center" style="width:260px; margin-left: 16px; margin-right: 6px; background: white; margin-top: 25px; float: left;">';
            echo '<div class="card-body" style="height: 80px;">';
            echo "<h6><a href='productPagina.php?stockKey=".$row['StockItemID']."'>".$row['StockItemName']."</a></h6>";
            echo "</div>";
            echo '<a href="productPagina.php?stockKey='.$row['StockItemID'].'"><img class="card-img-top img-fluid" src="img/150x150.png" alt="'.$row['StockItemName'].'"></a>';
            echo '<div class="card-body">';
            $gemRatinghome = "SELECT ROUND(AVG(Rating)) Gem FROM customerreview WHERE StockItemID =".$row['StockItemID'].";";
            foreach ($db->query($gemRatinghome) as $row2) {
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
            echo '<input type="hidden" name="stockKey" value='.$row["StockItemID"].'><button name = "winkelmandje_knop" class="btn btn-outline-default my-2 my-sm-0" type="submit">Add to cart <img style="height: 23px; "src="img/cart.png" alt=""></button>';
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
<!-- /.container -->
<?php
include 'footer.php';
?>
