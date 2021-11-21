<?php
////database inlog gegevens
//$host = "localhost";
//$db_name = "wideworldimporters";
//$username = "www";
//$password = "wwi-webshop";

////Rick
//$host = "192.168.64.2";
//$db_name = "wideworldimporters";
//$username = "root";
//$password = "";

//Thomas
$host = "localhost";
$db_name = "wideworldimporters";
$username = "www";
$password = "wwi-webshop";

try {
	$db = new PDO( "mysql:host={$host};dbname={$db_name}", $username, $password );
} catch ( PDOException $exception ) {
	//Connection Error
	echo "Connection error: " . $exception->getMessage();
}

//laat zien welk product de productpagina moet laden
$stockKey = filter_input( INPUT_GET, "stockKey", FILTER_SANITIZE_NUMBER_INT );

//algemene basis queries
$productNaam = "SELECT StockItemName FROM stockitems WHERE StockItemID = '$stockKey'";
$productPrijs = "SELECT UnitPrice FROM stockitems WHERE StockItemID = '$stockKey'";
$productGewicht = "SELECT TypicalWeightPerUnit FROM stockitems WHERE StockItemID = '$stockKey'";
$productOmschrijving = "SELECT SearchDetails FROM stockitems WHERE StockItemID = '$stockKey'";
$productAantal = "SELECT QuantityPerOuter FROM stockitems WHERE StockItemID = '$stockKey'";
$productVoorraad = "SELECT QuantityOnHand FROM stockitemholdings WHERE StockItemID = '$stockKey'";
$productTag = "SELECT Tags FROM stockitems WHERE StockItemID = '$stockKey'";
$productAfbeelding = "SELECT StockImg FROM stockimg WHERE StockItemID = '$stockKey'";
$productVideo = "SELECT StockVid FROM stockvid WHERE StockItemID = '$stockKey'";
$productCategory = "SELECT StockGroupID FROM stockitemstockgroups WHERE StockItemID = '$stockKey'";
$productHerkomst = "SELECT CustomFields FROM stockitems WHERE StockItemID = '$stockKey'";
$productTemperatuur = "SELECT Temperature FROM coldroomtemperatures WHERE ColdRoomSensorNumber = 1 AND ColdRoomSensorNumber = (SELECT IsChillerStock FROM stockitems WHERE StockItemID = '$stockKey')";
$groupID = "SELECT StockGroupID FROM stockgroups";
$groupName = "SELECT StockGroupName, StockGroupID FROM stockgroups ORDER BY StockGroupID";
$gemRating = "SELECT ROUND(AVG(Rating)) Gem FROM customerreview WHERE StockItemID = $stockKey";
?>
