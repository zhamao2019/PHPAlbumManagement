<?php

include "./ProjectCommon/Header.php";
include_once ("./ProjectCommon/EntityClassLib.php");
include_once ("./ProjectCommon/Functions.php");

session_start();

extract($_POST);

if($_SESSION['user']==null)
{
    header('Location: Login.php');
}
else{
$user = unserialize(serialize($_SESSION['user']));
$userName = $user->getName();
}

$albumTitleErrMsg = $descriptionErrMsg = "";
$albumTitle = trim($albumTitle);
$desciption = trim($desciption);

if (isset($btnSubmit)) {
    if($albumTitle == ""){
        $albumTitleErrMsg = "Your title can not be blank";
    }
    
    if ($albumTitleErrMsg == "") {
        addNewAlbum($user -> getUserId(), $albumTitle, $accessibility, $description); //can ask if to add one more here
        header("Location: MyAlbums.php");
        exit();
    }
}

if(isset($btnClear)){
    $_POST = array();
}
?>



<div class='container'>

    <h1><strong>Create New Album</strong></h1>

    <div style="margin-bottom: 50px"><p>Welcome <strong><?php print $userName ;?></strong>! (not you? change user <a href='Login.php'>here</a>)</p></div>


    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="addAlbum">

        <!-- Title -->
        <div class="row" style="margin-bottom: 10px">
           <label class="col-lg-3">Title :</label>
           <div class="col-lg-6">
               <input class="form-control" style="width:400px" type="text" id="albumTitle" name="albumTitle" maxlength="256" value="<?php if (!isset($btnClear)) echo $_SESSION['albumTitle']; ?>"/>
               <p id="countTitle">(no more than 256 characters)</p>
               <div class="text-danger"><?php echo $albumTitleErrMsg; ?></div>
           </div>
       </div>

        <!-- Accessibility -->
        <div class="row" style="margin-bottom: 10px">
            <label class="col-lg-3">Accessibility :</label>
            <div class="col-lg-6">
                <select class="form-control" style="width:400px"  name="accessibility" />
                <option value="private">Accessible only by the owner</option>
                <option value="shared">Accessible by owner and friends</option>
                </select>
            </div>
        </div>            

        <!-- Description -->
        <div class="row" style="margin-bottom: 10px">
            <label class="col-lg-3">Description :</label>
            <div class="col-lg-6">
                <textarea class="form-control" style="width:400px; height: 200px;" id="description" name="description" maxlength="3000"></textarea>
                <p id="countDescription">(no more than 3000 characters)</p>
            </div>
        </div>

        <!-- Buttons -->
        <button type="submit" class="btn btn-success" name="btnSubmit" value="Submit">Submit</button>
        <button type="submit" class="btn" name="btnClear" value="Clear">Clear</button>    
        
    </form>
</div>
   


<script type="text/javascript">
    document.getElementById('albumTitle').onkeyup = function () {
        document.getElementById('countTitle').innerHTML = "Characters left: " + (20 - this.value.length);
    }
    document.getElementById('description').onkeyup = function () {
        document.getElementById('countDescription').innerHTML = "Characters left: " + (3000-this.value.length);
    }

</script>
<?php include_once "./ProjectCommon/Footer.php" ?>

