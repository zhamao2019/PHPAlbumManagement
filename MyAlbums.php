<?php 
    session_start();
    include("./ProjectCommon/functions.php");
    include("./ProjectCommon/header.php");
    include_once ('./ProjectCommon/EntityClassLib.php');
    $MyAlubms='MyAlbums.php';
    $_SESSION['currentPage'] = $_SERVER['REQUEST_URI'];
    
    extract($_POST);
    extract($_GET);
    
    if($_SESSION["user"]==null)
    {
        header("Location: Login.php");
        exit();
    }
    else{
        $user = unserialize(serialize($_SESSION['user']));
    }

    if(isset($btnSave))
    {
        foreach ($selectAlbumAcs as $var)
        {
            $albumId= substr($var, 1);
            $AcCode= substr($var, 0,1);
            if($AcCode==1)
            {
                $albumAccessibility= 'private';
            }
            if($AcCode==2)
            {
                $albumAccessibility= 'shared';
            }
            
            updateAlbumAccessibilityById($albumId, $albumAccessibility);

        }   
        // alert box
            echo '<script language="javascript">';
            echo 'alert("You have changed the album accessibility successfully!")';
            echo '</script>';
    }
    
    if(isset($_GET['AlbumId']))
    {
        $deleteAlbumId = $_GET['AlbumId'];
        $picturesInAlbum = getPicturesByAlbumId($deleteAlbumId);
        foreach ($picturesInAlbum as $picture) {
            $pictureId=$picture->getPictureId();
            deletePictureAndCommentsByPictureId($pictureId);
        }
        deleteAlbumById($deleteAlbumId);
    }

    $albums = getAlbumsByUserId($user->getUserId());
    $_SESSION['albums'] = $albums;    
    
    ?>

<div class='container-fluid'>
    <div class="col-lg-12">
        <h1 class="text-center">My Albums</h1>
    </div>
    <div class="col-lg-12">
        <p class="text-left">Welcome<strong> <?php print $user->getName()?> </strong> (not you? change user <a href='Logout.php'>here</a>)</p>
    </div>
    <div class="col-lg-12">
        <p class="col-lg-9 text-right"><a href="AddAlbum.php">Create a new album</a></p>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]);?>" method="post" id="myAlbum">
        <div class='col-lg-4' style='color:red'> <?php print $errorMsg;?></div><br>        
        <div class="col-lg-10">
            <table class="table" >
                <thead >
                    <tr>
                        <th style="text-align: center">Title</th><th style="text-align: center">Date Updated</th><th style="text-align: center">Number of Pictures</th><th style="text-align: center">Accessibility</th><th style="text-align: center">Delete</th>
                    </tr>
                </thead>              
                <tbody>

                    <?php

                  
                        for($i=0; $i<count($albums);$i++)
                        {
                            $albumTitle=$albums[$i]->getTitle();

                            $albumId=$albums[$i]->getAlbumId();
                            $albumAccessibility=$albums[$i]->getAcessibilityCode();                                


                            $albumDateTime=$albums[$i]->getDate();
                            $albumDate= substr($albumDateTime,0,10);
                            $numberOfPictures= count(getPicturesByAlbumId($albumId));
                            if($albumAccessibility=='private')
                            {
                                $privateSelected = 'selected';
                                $sharedSelected = '';
                            }
                            if($albumAccessibility=='shared')
                            {
                                $sharedSelected = 'selected';
                                $privateSelected = '';
                            }

                            echo "<tr><td style='text-align: center'>"
                            . "<a href='MyPictures.php?AlbumId=$albumId' >$albumTitle</a>"
                                    . "</td><td style='text-align: center'>$albumDate</td><td style='text-align: center'>$numberOfPictures</td>"
                                    . "<td style='text-align: center'><select name='selectAlbumAcs[]' id='select' onchange='checkField(this.value)'>"
                                    . "<option value=1$albumId name= 'private' $privateSelected>by owner</option>"
                                    . "<option value=2$albumId name = 'shared' $sharedSelected>by all friends</option></td>"
                                    . "<td style='text-align: center'><a href='MyAlbums.php?AlbumId=$albumId' onclick=\" return confirm('Do you want to delete?')\" >Delete</a></td></tr>";

                        }                        

                    ?>

                </tbody>
            </table>
        </div>
       
        <div class='col-lg-4' style="float:right"><button type="submit" class="btn btn-primary" name="btnSave" >Save changes</button></div>
  
    </form>
 
</div>

<?php
    include('./ProjectCommon/footer.php');
?>

