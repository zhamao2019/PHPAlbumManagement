<?php 
    include ("./ProjectCommon/Functions.php");
    include("./ProjectCommon/Header.php");
    include_once('./ProjectCommon/EntityClassLib.php');
    
    session_start();
    ?>

<div class='container-fluid'>
    <h1 class="col-lg-12 text-left">Welcome to Algonquin Social Media</h1>
    <p class="col-lg-12">If you have never used this before, you have to <a href="NewUser.php">sign up</a> first.</p>
    <p class="col-lg-12">If you have already signed up, you can <a href="Login.php">log in</a> now.</p>
</div>

<?php include('./ProjectCommon/footer.php'); ?>
