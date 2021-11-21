<?php
include 'header.php'
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
                    <input class="btn" style="margin-left: 65px; margin-top: 30px; margin-bottom: 250px;"  name="submit" type="submit" value="Login">            
                </form>
                </div>
            </div>
        </div>
    </div>
    <?php include 'loginCheck.php'; ?>
</body>
<?php include 'footer.php' ?>
