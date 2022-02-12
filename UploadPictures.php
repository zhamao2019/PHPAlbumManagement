<?php
session_start();
include_once("./ProjectCommon/Functions.php");
include_once("./ProjectCommon/Header.php");
include_once('./ProjectCommon/EntityClassLib.php');
$_SESSION['currentPage'] = $_SERVER['REQUEST_URI'];

$user = unserialize(serialize($_SESSION['user']));

if ($user == null) {
    header('Location: Login.php');
    exit();
}

$albums = getAlbumsByUserId($user->getUserId());
extract($_POST);

//Clear button:
if (isset($_POST['clear'])) {
    $_POST['uploadAlbum'] = $_POST['descriptionTxt'] = $_POST['pictureTitleTxt'] = "";
}

$fileUploadErrMsg = $pictureTitlErrMsg = "";

if (isset($_POST['submit']))
{
    //validation
    if (!isset($_POST['fileUpload'])) {
        $fileUploadErrMsg = "No picture was selected";
    }
    $pictureTitlErrMsg = ValidatePictureTitle($pictureTitleTxt);
    $fileUploadErrMsg = ValidateFileUpload($_FILES, 'fileUpload');
    $_SESSION['albumTitle'] = $albumTitle;

    if ($fileUploadErrMsg == "" && $pictureTitlErrMsg == "") 
    {

        //save the pictures to folder
        $destination = './uploads';        // define the path to a folder to save the file
        $destinationThumbnails = './thumbnails';
        $destinationImages = './images';

        $maxWidthT=50;
        $maxHeightT=50;

        $maxWidthI=200;
        $maxHeightI=200;
        
        if (!file_exists($destination)) {
            mkdir($destination);
        }
        
        //save_uploaded_file($destinationPath);
        for ($j = 0; $j < count($_FILES['fileUpload']['tmp_name']); $j++) {
            if ($_FILES['fileUpload']['error'][$j] == 0) {

                $fileTempPath = $_FILES['fileUpload']['tmp_name'][$j];
                $filePath = $destination . "/" . $_FILES['fileUpload']['name'][$j];

                $pathInfo = pathinfo($filePath);
                $dir = $pathInfo['dirname'];
                $fileName = $pathInfo['filename'];
                $ext = $pathInfo['extension'];
                
                addNewPicture($albumId, $pictureTitleTxt, $descriptionTxt,$fileName);
                
                $i = "";
                while (file_exists($filePath)) {
                    $i++;
                    $filePath = $dir . "/" . $fileName . "_" . $i . "." . $ext;
                }
                
                $fileName = pathinfo($filePath)['basename'];
                addNewPicture($albumIdSelect, $pictureTitleTxt, $descriptionTxt, $fileName);            
                move_uploaded_file($fileTempPath, $filePath);
                
                //resample the original pictures and put them into /thumbnails folder
                resamplePicture($filePath, $destinationThumbnails, $maxWidthT, $maxHeightT);

                //resample the original pictures and put them into /images folder
                resamplePicture($filePath, $destinationImages, $maxWidthI, $maxHeightI);

                echo "<script> alert('".$fileName." is Uploaded to Album ".$albumIdSelect." - ".getAlbumById($albumIdSelect)->getTitle()."')</script>";
            }
        }
    }
}
?>
<div class="container">
    <div style="margin-top:30px"><h1><strong>Upload Pictures</strong></h1></div>
    <div style="margin-top:30px" >
        <p>Accepted picture types: JPG(JPEG), GIF and PNG.</p>
        <p>You can upload multiple pictures at a time by pressing the SHIFT key while selecting pictures.</p>
        <p>When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
    </div>

    <form style="margin-top:30px" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <!--Album Title and ID-->    
        <div class="row" style="margin-bottom: 10px">
            <label class="col-lg-3">Upload To Album:</label>
            <div class="col-lg-6">
                <select class='form-control' style='width:400px' id='albumId' name='albumIdSelect'>
                    <?php
                    foreach ($albums as $album) {
                        $albumId = $album->getAlbumId();
                        $albumTitle = $album->getTitle();
                        if ($albumId == $_POST['albumIdSelect'] && isset($submit)) 
                        {
                            $selected='selected';
                        }
                        echo "<option value=$albumId $selected>" . $albumTitle . "</option>";
                        $selected = "";
                    }
                    ?>
                </select>
                <?php
                if (!empty($albumTitleErrMsg)) {
                    echo "<div class='alert alert-danger' style='width:400px'>$albumTitleErrMsg</div>";
                }
                ?>
            </div>
        </div>

        <!-- choose file -->
        <div class="row" style="margin-bottom: 10px">
            <label class="col-lg-3">File to Upload:</label>
            <div class="col-lg-6">
                <input class='form-control' style="width:400px" type='file' id='fileUpload' name='fileUpload[]' accept="image/gif, image/jpeg, image/png" multiple>
                <div class="text-danger"><?php echo $fileUploadErrMsg; ?></div>
            </div>
        </div>

        <!-- Picture Title -->        
        <div class="row" style="margin-bottom: 10px">
            <label class="col-lg-3">Title:</label>
            <div class="col-lg-6">
                <input class='form-control' style="width:400px" type='text' id='pictureTitleTxt' name='pictureTitleTxt' 
                       value=<?php
                       if (isset($_POST['pictureTitleTxt'])) {
                           echo $_POST['pictureTitleTxt'];
                       }
                       ?>>
                <div class="text-danger"><?php echo $pictureTitlErrMsg; ?></div>
            </div>
        </div>

        <!-- Description --> 
        <div class="row" style="margin-bottom: 10px">
            <label class="col-lg-3">Description :</label>
            <div class="col-lg-6">
                <textarea class="form-control" style="width:400px; height: 200px;" id="descriptionTxt" name="descriptionTxt" maxlength="20"></textarea>
                <p id="countDescription">(no more than 20 characters)</p>
            </div>
        </div>

        <!-- Buttons -->
        
        <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
        <button type="submit" class="btn" name="clear" value="Clear">Clear</button>
        

    </form>
</div>
<?php
include('ProjectCommon/footer.php');
?>
<script type="text/javascript">
    document.getElementById('descriptionTxt').onkeyup = function () {
        document.getElementById('countDescription').innerHTML = "Characters left: " + (200 - this.value.length);
    }

</script>