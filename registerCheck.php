<?php
include 'redirect.php';
//deze code filtert de input voor code
$firstname = filter_input(INPUT_POST, 'first_name' , FILTER_SANITIZE_STRING);
$lastname = filter_input(INPUT_POST, 'last_name' , FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email' , FILTER_VALIDATE_EMAIL);
$passwordplain = filter_input(INPUT_POST, 'password' , FILTER_SANITIZE_STRING);
$passwordconfirm =filter_input(INPUT_POST, 'passwordConfirm' , FILTER_SANITIZE_STRING);
$country = filter_input(INPUT_POST, 'country' , FILTER_SANITIZE_STRING);
$province = filter_input(INPUT_POST, 'province' , FILTER_SANITIZE_STRING);
$townCity = filter_input(INPUT_POST, 'town/city' , FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address' , FILTER_SANITIZE_STRING);
$zip = filter_input(INPUT_POST, 'ZIP_code' , FILTER_SANITIZE_STRING);
$phone =filter_input(INPUT_POST, 'phone_number' , FILTER_SANITIZE_NUMBER_INT);

$passwordduplicate = "";
$emailduplicate = "";
//als de register knop word ingedrukt word er gecheckt het ingevulde telefoonnummer wel een nummer is en of het 10 nummers lang is en daarna of de terms of service is aangevinkt
if(isset($_POST['Register'])){
    if(preg_match("/^[0-9]{10}$/", $phone)) {    
		if(isset($_POST['tos'])){
                        //Deze query checkt of de ingevulde email al bestaat in database
			$checkemailquery = "SELECT CustomerEmail FROM customeraccounts WHERE CustomerEmail = '$email'";
			foreach($db->query($checkemailquery) as $row) {
				$emailduplicate = $row['CustomerEmail'];
			}
                        //Deze query checkt of de email die overeenkomt in de database ook een password bevat of niet
			$checkpasswordquery = "SELECT CustomerPassword FROM customeraccounts WHERE CustomerEmail ='$email'";
			foreach($db->query($checkpasswordquery) as $row) {
				$passwordduplicate = $row['CustomerPassword'];
			}
			if($emailduplicate == ""){
				if($passwordplain == $passwordconfirm){
					$checkedpassword = $passwordplain;
					if(isset($_POST['Register'])){
						$password = password_hash($passwordplain, PASSWORD_DEFAULT);
                                                //er word een nieuw account aangemaakt als er geen bestaande email bij de ingevulde gegevens zitten
						$insertquery = "INSERT INTO customeraccounts (CustomerEmail,CustomerPassword,CustomerNAWFirstname,CustomerNAWLastname,CustomerNAWCountry,CustomerNAWProvince,CustomerNAWCity,CustomerNAWAddress,CustomerNAWZip,CustomerNAWPhone) VALUES ('$email','$password','$firstname','$lastname','$country','$province','$townCity','$address','$zip','$phone')";

						if ($firstname == "" || $lastname == "" || $email == "" || $passwordplain == "" || $country == "" || $province == "" || $townCity == "" || $address == "" || $zip == "" || $phone == ""){
							print("<h5 class='' style='color: red;'>Please fill out all fields</h5>");
						} elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$db->exec($insertquery);
							redirect('login.php');
						} else {
							print("<h5 class='' style='color: red;'>Please fill out all fields</h5>");
						}
					}
				} else {
					print("<h5 class='' style='color: red;'>Passwords do not match</h5>");
				}
			} elseif ($emailduplicate == $email && $passwordduplicate == "") {
				if($passwordplain == $passwordconfirm){
					$checkedpassword = $passwordplain;
					if(isset($_POST['Register'])){
                                            //Hier wordt een gast account verandert in een echt account als de overeenkomende email in de database geen wachtwoord bevat
						$idquery2 = "SELECT CustomerAccountID FROM customeraccounts WHERE CustomerEmail = '$email'";   
						foreach($db->query($idquery2) as $row){
							$accountid = $row['CustomerAccountID'];
						}
						$password = password_hash($passwordplain, PASSWORD_DEFAULT);
						$updatequery2 = "UPDATE customeraccounts SET CustomerPassword = '$password', CustomerEmail = '$email', CustomerNAWFirstname = '$firstname', CustomerNAWLastname = '$lastname', CustomerNAWCountry = '$country', CustomerNAWProvince = '$province', CustomerNAWCity = '$townCity', CustomerNAWAddress = '$address', CustomerNAWZip = '$zip', CustomerNAWPhone ='$phone' WHERE CustomerAccountID = $accountid"; 
						if($firstname == "" || $lastname == "" || $email == "" || $passwordplain == "" || $country == "" || $province == "" || $townCity == "" || $address == "" || $zip == "" || $phone == ""){
							print("Please fill out all fields");
						} elseif(filter_var($email, FILTER_VALIDATE_EMAIL)){
							$db->exec($updatequery2);
							redirect('login.php');
						} else {
							print("Please fill out all fields");
						}       
					}
				} else {
					print("Passwords do not match");
				}
			} else {
				print("<h5 class='' style='color: red;'>There is already an account with this email address</h5>");
			}
		} else {
			print("you cannot continue without accepting our terms of service");
		}
	} else {
    	print("Please fill in a valid phone number");
	}
}
?>
