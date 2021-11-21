<?php
include 'redirect.php';
//checkt of de submit knop word ingedrukt en filtert de input voor code
if(isset($_POST['submit'])){
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

//checkt of de email daadwerkelijk een email is en checkt of een of meer van de velden leeg zijn
    if($password != "" && $email != "" && filter_var($email, FILTER_VALIDATE_EMAIL)){
        //Deze query checkt of er een email is die overeen komt in de database met de ingevulde email 
        $loginemailquery = "SELECT CustomerAccountID FROM customeraccounts WHERE CustomerEmail = '$email'";
        foreach($db->query($loginemailquery) as $row){
            $accountrecordid = $row['CustomerAccountID'];
	}  
        if(isset($accountrecordid)){
            //Deze query kijkt of het ingevulde wachtwoord overeenkomt met de hash die bij de ingevulde email hoort
            $loginpasswordquery = "SELECT CustomerPassword FROM customeraccounts WHERE CustomerAccountID = '$accountrecordid'";
            foreach($db->query($loginpasswordquery) as $row){
                $accountpasswordhash = $row['CustomerPassword'];
            }
            //Als de inloggegevens kloppen word er een session met de ID van het account aangemaakt en word de gebruiken doorgestuurd naar de volgende pagina
            if(password_verify($password, $accountpasswordhash)){
                $_SESSION['loggedIn'] = $accountrecordid;
                redirect('deliveryDetailsVerification.php');
            } else {
                print("<h5 class='text-center' style='color: red;'>Wrong email or password!</h5>");
            }

        } else {
            print("<h5 class='text-center' style='color: red;'>Wrong email or password!</h5>");
        }
    } else {
        print("<h5 class='text-center' style='color: red;'>Please fill all fields!</h5>");
    }
}
