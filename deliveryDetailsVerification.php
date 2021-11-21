<?php
include 'redirect.php';
include "header.php";
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h5 style="margin-top: 25px;">Check if the following information is correct:</h5><br>
        </div>
		<div style="margin-left: 460px;" class="col-sm-12 text-left">
        <?php
		//alle gegevens van het ingelogde account worden hier opgehaald en worden getoont aan de gebruiker
        if(!isset($_SESSION['loggedIn'])){
            $loggedaccountid = '1234';
            $firstnamequery = "SELECT CustomerNAWFirstname FROM customeraccounts WHERE CustomerAccountID = $loggedaccountid";

            foreach($db->query($firstnamequery) as $row) {
                print("<h6>First name: ".$row['CustomerNAWFirstname']."</h6>");
            }

            $lastnamequery = "SELECT CustomerNAWLastname FROM customeraccounts WHERE CustomerAccountID = $loggedaccountid";
            foreach($db->query($lastnamequery) as $row) {
                print("<h6>Last name: ".$row['CustomerNAWLastname']."</h6>");
            }

            $emailquery = "SELECT CustomerEmail FROM customeraccounts WHERE CustomerAccountID = $loggedaccountid";
            foreach($db->query($emailquery) as $row) {
                print("<h6>Email address: ".$row['CustomerEmail']."</h6>");
            }

            $countryquery = "SELECT CustomerNAWCountry FROM customeraccounts WHERE CustomerAccountID = $loggedaccountid";
            foreach($db->query($countryquery) as $row) {
                print("<h6>Country: ".$row['CustomerNAWCountry']."</h6>");
            }

            $provincequery = "SELECT CustomerNAWProvince FROM customeraccounts WHERE CustomerAccountID = $loggedaccountid";
            foreach($db->query($provincequery) as $row) {
                print("<h6>Province: ".$row['CustomerNAWProvince']."</h6>");
            }

            $cityquery = "SELECT CustomerNAWCity FROM customeraccounts WHERE CustomerAccountID = $loggedaccountid";
            foreach($db->query($cityquery) as $row) {
                print("<h6>Town/City: ".$row['CustomerNAWCity']."</h6>");
            }

            $addressquery = "SELECT CustomerNAWAddress FROM customeraccounts WHERE CustomerAccountID = $loggedaccountid";
            foreach($db->query($addressquery) as $row) {
                print("<h6>Address: ".$row['CustomerNAWAddress']."</h6>");
            }

            $zipquery = "SELECT CustomerNAWZip FROM customeraccounts WHERE CustomerAccountID = $loggedaccountid";
            foreach($db->query($zipquery) as $row) {
                print("<h6>ZIP code: ".$row['CustomerNAWZip']."</h6>");
            }

            $phonequery = "SELECT CustomerNAWPhone FROM customeraccounts WHERE CustomerAccountID = $loggedaccountid";
            foreach($db->query($phonequery) as $row) {
                print("<h6>Phone number: ".$row['CustomerNAWPhone']."</h6>");
            }
		//als je door een fout op deze pagina komt zonder inglogd te zijn krijg je een error
        } else {
            print("<h5 style='color: red;'>An unknown error has occured. Please try again at a later time.</h5>");

        }
        ?>

    </div>
        <div class="col-sm-6" style="margin-left: 450px;">
        <form action="deliveryDetails.php">
			<!--deze knop gaat terug naar customer details om je gegevens aan te passen-->
            <br><input class="btn" style=" margin-top: 20px; width: 250px;" type="submit" value="Information is incorrect"><br>
        </form>
        <form action="" method="post">
			<!--deze knop gaat naar de IDEAL page-->
            <input class="btn" style="margin-top: 20px; width: 250px;" type="submit" name="proceed" value="Proceed to payment">
            <?php
			//update de voorraad
            if(isset($_POST['proceed'])){
                $id = '1234';
                $insertquery = "INSERT INTO customerinvoices (CustomerAccountID) VALUES ($id)";
				//bestelregel word aangemaakt
                $db->exec($insertquery);
                $invoicequery = "SELECT InvoiceID FROM customerinvoices WHERE CustomerAccountID = $id AND Time = (SELECT MAX(Time) FROM customerinvoices WHERE CustomerAccountID = $id)";
				//ID van bestelregel word opgehaald
                foreach($db->query($invoicequery) as $row){
                    $invoiceid = $row['InvoiceID'];
                }
				//update de voorraad
                //foreach($_SESSION['winkelmandje'] as $key => $value){
                //    $productquery = "INSERT INTO customerinvoicesstockitems (InvoiceID,StockItemID,Amount) VALUES ($invoiceid,$key,$value)";
                //    $db->exec($productquery);
                //    $subtractstock = "UPDATE stockitemholdings SET QuantityOnHand = QuantityOnHand - $value WHERE StockItemID = $key";
                //    $db->exec($subtractstock);
                //}
				//leegt winkelmandje en verwijst door naar de iDeal demo
				unset($_SESSION['winkelmandje']);
				redirect('https://www.ideal.nl/demo/?screens=dskweb');
            }


            ?>
        </form>
    </div>
</div>
</div>
<?php include 'footer.php' ?>
