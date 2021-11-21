<?php
include 'redirect.php';
include 'header.php';

if (isset($_SESSION['loggedIn'])) {
	redirect('deliveryDetailsVerification.php');

} else {

	?>
	<div class="container">
		<div class="row text-center">
			<div class="col-sm-12">
				<form action="login.php">
					<input class="btn" style="width: 300px; margin-top: 250px;" type="submit" value="Login with existing account">
				</form>
				<form action="register.php">
					<input class="btn" style="width: 300px; margin-top: 15px;" type="submit" value="Create new account">
				</form>
				<form action="deliveryDetails.php">
					<input class="btn" style="width: 300px; margin-top: 15px; margin-bottom: 300px;" type="submit" value="Checkout without account">
				</form>
			</div>
		</div>
	</div>

	<?php
	include 'footer.php';
}
?>
