<?php
include 'redirect.php';

//neemt de ingevulde gegevens mee om te filteren
$firstname = filter_input( INPUT_POST, 'first_name', FILTER_SANITIZE_STRING );
$lastname = filter_input( INPUT_POST, 'last_name', FILTER_SANITIZE_STRING );
$email = filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL );
$country = filter_input( INPUT_POST, 'country', FILTER_SANITIZE_STRING );
$province = filter_input( INPUT_POST, 'province', FILTER_SANITIZE_STRING );
$townCity = filter_input( INPUT_POST, 'town/city', FILTER_SANITIZE_STRING );
$address = filter_input( INPUT_POST, 'address', FILTER_SANITIZE_STRING );
$zip = filter_input( INPUT_POST, 'ZIP_code', FILTER_SANITIZE_STRING );
$phone = filter_input( INPUT_POST, 'phone_number', FILTER_SANITIZE_NUMBER_INT );
$checkexistingaccount = "SELECT CustomerPassword FROM customeraccounts WHERE CustomerEmail = '$email'";

if ( isset( $_POST[ 'Submit' ] ) ) {
	if ( preg_match( "/^[0-9]{10}$/", $phone ) ) {
		//deze query checkt of het account bestaat
		foreach ( $db->query( $checkexistingaccount ) as $row ) {
			$checkexistingpassword = $row[ 'CustomerPassword' ];
		}
		//checkt of alle velden ingevuld zijn en zoniet geeft hij aan dat je ze verplicht moet invullen
		if ( $firstname != "" && $lastname != "" && $email != "" && $country != "" && $province != "" && $townCity != "" && $address != "" && $zip != "" && $phone != "" ) {
			//als je ingelogd bent worden je indien aangepaste gegevens geupdate
			if ( isset( !$_SESSION[ 'loggedIn' ] ) ) {
				$loggedinID = '1234';
				$updatequery = "UPDATE customeraccounts SET CustomerEmail = '$email', CustomerNAWFirstname = '$firstname', CustomerNAWLastname = '$lastname', CustomerNAWCountry = '$country', CustomerNAWProvince = '$province', CustomerNAWCity = '$townCity', CustomerNAWAddress = '$address', CustomerNAWZip = '$zip', CustomerNAWPhone ='$phone' WHERE CustomerAccountID = $loggedinID";
				$db->exec( $updatequery );
				redirect( 'deliveryDetailsVerification.php' );
			} else {
				//checkt of het account niet al bestaat
				if ( isset( $checkexistingpassword ) ) {
					print( "An account with this email already exists" );
				//maakt een account aan zonder wachtwoord (guest account)
				} else {
					$idquery = 'SELECT MAX(CustomerAccountID + 1) as lastID FROM customeraccounts';
					foreach ( $db->query( $idquery ) as $row ) {
						$accountid = $row[ 'lastID' ];
					}
					$insertquery2 = "INSERT INTO customeraccounts (CustomerAccountID, CustomerEmail, CustomerNAWFirstname, CustomerNAWLastname, CustomerNAWCountry, CustomerNAWProvince, CustomerNAWCity, CustomerNAWAddress, CustomerNAWZip, CustomerNAWPhone) VALUES ($accountid,'$email','$firstname','$lastname','$country','$province','$townCity','$address','$zip','$phone')";
					$db->exec( $insertquery2 );
					$_SESSION[ 'loggedIn' ] = $accountid;
					redirect( 'deliveryDetailsVerification.php' );
				}
			}
		} else {
			print( "Please fill in all fields" );
		}
	}
}
