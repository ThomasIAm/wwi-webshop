<?php
include 'designProjectRick/header.php'
?>
<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-4"></div>
			<div class="col-sm-4">
				<div style="margin-left: 70px;">
					<form method="post" action="">
						<h5 style="margin-top: 200px;">Email:</h4>
						<input name="email" id="email" type="email" placeholder="Email">
						<h5 style="margin-top: 30px;">Password:</h5>
						<input name="password" id="password" type="password" placeholder="Password"><br>
						<?php
						if (filter_has_var(INPUT_GET, 'stockKey')) {
							echo '<input type="hidden" name="stockKey" value="'.filter_input(INPUT_GET, 'stockKey', FILTER_SANITIZE_NUMBER_INT).'">';
						}
						?>
						<input class="btn" style="margin-left: 65px; margin-top: 30px; margin-bottom: 250px;" name="submit" type="submit" value="Login">
					</form>
					<form action="designProjectRick/registerhead.php">
						<?php
						if (filter_has_var(INPUT_GET, 'stockKey')) {
							echo '<input type="hidden" name="stockKey" value="'.filter_input(INPUT_GET, 'stockKey', FILTER_SANITIZE_NUMBER_INT).'">';
						}
						?>
						<input style="margin-left: -35px;" class="btn" name="submit" type="submit" value="Don't have an account? Register here!">
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php include 'designProjectRick/loginCheckhead.php'; ?>
</body>
<?php include 'designProjectRick/footer.php' ?>