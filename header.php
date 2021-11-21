<?php
session_start();
include 'databaseHandler.php';
$stockKey = filter_input(INPUT_GET, "stockKey", FILTER_SANITIZE_NUMBER_INT);
$amount = filter_input(INPUT_GET, "amount", FILTER_SANITIZE_NUMBER_INT);
if ($amount == "") {
    $amount = 1;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Wide World Importers</title>

        <!--BS CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!--BS Jquery -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

        <!--BS JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <!--alert timer-->
        <script>
        setTimeout(function() {
            $(".alert").fadeOut().empty();
        }, 5000);
        </script>

        <link rel="stylesheet" type="text/css" href="index.css">
        <!--alert styling-->
        <style>
        .alert {
            padding: 20px;
            background-color: #4bf442;
            color: white;
            margin: 10px;
        }

        .closebtn {
            margin-right: 15px;
            color: white;
            font-weight: bold;
            float: left;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }
        </style>

		<!-- Favicons -->
		<link rel="apple-touch-icon" sizes="76x76" href="/img/favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
		<link rel="manifest" href="/img/favicons/site.webmanifest">
		<link rel="mask-icon" href="/img/favicons/safari-pinned-tab.svg" color="#00aff4">
		<link rel="shortcut icon" href="/img/favicons/favicon.ico">
		<meta name="msapplication-TileColor" content="#2d89ef">
		<meta name="msapplication-config" content="/img/favicons/browserconfig.xml">
		<meta name="theme-color" content="#00aff4">
    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">Wide World Importers</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                </ul>
                <form action="generalSearchResult.php" class="form-inline my-2 my-lg-0" method="get">
                    <input class="form-control mr-sm-2" type="search" name="zoekOpdracht" value="" placeholder="Search">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                </form>
                <?php
                //checkt of er iemand is ingelogd
				if(isset($_POST['logout'])){
					unset($_SESSION['loggedIn']);
				}

                if(isset($_SESSION['loggedIn'])){
                ?>
                <form method="post">
                    <input style="margin-left: 7px;" class="btn btn-secondary" type="submit" name="logout" value="Logout">
                </form>
                <?php
                } else {
                ?>
                <form method="post" action="loginhead.php">
                    <input style="margin-left: 7px;" class="btn btn-secondary" type="submit" value="Login">
                </form>
                <?php
                }
                ?>
                <a href="winkelmandje.php">
                    <img style="height: 36px; padding: 8px 12px 8px 12px; background: white; border-radius: 4px; margin-left: 7px;"src="img/cart.png" alt="cart">
                </a>
            </div>
        </nav>
        <?php
            //alert knop
            if (filter_has_var(INPUT_GET, "winkelmandje_knop")) {
                foreach ($db->query($productNaam) as $row) {
                    $naam = $row['StockItemName'];
                }
                echo '<div class="alert row" style="">
                        <div class="col-sm-12">
                          <span class="closebtn" style="float: left;margin-top: 12px; font-size: 40px;" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                        <p style="float: left; padding-top: 10.5px;font-size: 20px;">Added <b>'.$amount.'x</b> <i>'.$naam.'</i> to cart!</p>';
                          echo '<a href="winkelmandje.php">
                                    <img style="float: right; height: 36px; margin-top: 10px; margin-left: 10px; padding: 8px 12px 8px 12px; background: white; border-radius: 4px; margin-left: 7px;"src="img/cart.png" alt="">
                                </a>';
                          echo '<p style="float: right; padding-top: 10.5px;font-size: 20px;">Go to cart:</p>';

                          echo '<script>this.parentElement.style.display=\'none\';</script>
                      </div></div>';
            }
        ?>
