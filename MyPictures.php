<?php
include_once("./ProjectCommon/Functions.php");
include_once("./ProjectCommon/Header.php");
include_once('./ProjectCommon/EntityClassLib.php');

session_start();

$_SESSION['currentPage'] = $_SERVER['REQUEST_URI'];

if ($_SESSION['user'] == null)
{ 
    exit(header('Location: Login.php'));
}

$user = unserialize(serialize($_SESSION["user"]));
$pictureIdGet = $_GET['pictureId'];
$commentTxt = $_POST['commentTxt'];     
$albums = getAlbumsByUserId($user->getUserId());
$albumId = $_GET['album'];
$validatorError = ""; 

if (isset($_POST['addComment'])) {

    // comment validation
    if($commentTxt == ""){
        $validatorError = "The comment is blank";
    }
    elseif ($albumId == "" || $_POST['selectAlbum'] == '-1') {
        $validatorError = "You need to select a album to add a comment";
    }
    elseif ($pictureIdGet == "") {
        $validatorError = "You need to select a picture to add a comment";
    }
    else{
        $validatorError = "";
    }
    
    if($validatorError == "") {
        addNewComment($user->getUserId(), $pictureIdGet, $_POST['commentTxt']);
    }
    
  
}
?>

<div class="container">
    <form method = "post" action = "MyPictures.php?<?php echo '&album='.$albumId.'&pictureId='.$pictureIdGet; ?>">
        <div style="margin-top:30px"><h1><strong><?php  echo $user->getName();?>'s Pictures</strong></h1></div><br/>
        <div class="row">
            <div class='col-lg-5 col-md-5'>
                <select name='selectAlbum' class='form-control' onchange="OnSelectionChange()" id='albumTitle'>
                    <option value='-1' <?php if(!isset($_GET["album"])) echo "selected";?>>please select</option>
                       
                    <?php       
                        foreach ($albums as $album){
                            
                            echo "<option value='".$album->getAlbumId()."'";
                            
                            if(isset($_GET['album']) && $_GET['album'] == $album->getAlbumId()){
                                   echo "selected";
                            }
                            
                            if(isset($_POST['addComment']) && $_POST['selectAlbum'] == $_GET['album']){ 
                                echo "selected";
                            }
                            
                            echo ">".$album->getTitle()." - updated on ".date("Y-m-d",strtotime($album->getDate()));
                            echo "</option>";                           
                        } 
                    ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class='col-lg-4 col-md-4'>
                <div style="margin-top:30px">
                    <h2 style="text-align:left">
                    <?php 
                    if($_GET['album'] != "" && $_GET['album']!="-1"){
                         echo getAlbumById($_GET['album'])->getTitle();
                    }
                    else
                    {
                        echo 'No Album Select';
                    }
                    ?>
                    </h2>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">

                <div style="width: auto;">  
                    
                    <?php 
                    if($pictureIdGet!=""){
                        $bigpicname=getPictureById($pictureIdGet)->getFileName();
                        $filepath='./images/'.$bigpicname;

                        echo "<img height='400' width='600' src='".$filepath."'>";
                      }
                    else
                    {
                        echo "no Picture Selected";
                    }
                    ?>
                </div>
                
                <div style ='overflow-x:scroll; overflow-y:hidden; display: inline-block; width: 600px;padding: 5px; margin-top: 10px; height: 120px; border: 1px solid gainsboro; white-space:nowrap'  id="thumbnail">
                    <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12" style="overflow-x: auto; white-space: nowrap;">
                    <?php
                    if(isset($_GET['album'])&&$_GET['album']!="-1"){
                        $pictures= getPicturesByAlbumId($_GET['album']);

                        foreach ($pictures as $p){
                            $filepath= getThumbnailFilepathOfPicture($p->getPictureId());
                            echo  "<a style='margin-right:5px;' href='MyPictures.php?&album=".$_GET['album']
                                ."&pictureId=".$p->getPictureId()." '><img  height='100' width='150' src='".$filepath."'></a>";
                        }
                    }
                    else
                    {
                        echo "<div>No Album Selected</div>";
                    }
                    ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 side-comments">
                <div class="comments-list">
                    <p><strong>Description:</strong></p>
                    <?php 
                    if($pictureIdGet != ""){
                        echo getPictureById($pictureIdGet)->getDescription();
                    }
                    else{
                        echo $pictureIdGet;

                    }
                    ?>
                    <br/>
                    
                    <p><strong>Comments:</strong></p>
                    <div style="overflow-y:scroll; overflow-x:hidden; height: 300px" >
                    <?php 
                    if($pictureIdGet != ""){
                        $commentandauthors= getCommentsAndAuthorsByPictureId($pictureIdGet);

                        foreach ($commentandauthors as $commentandauthor) {
                            echo "<div  class='text-primary'>";
                            echo key($commentandauthor);
                            echo" ( ".date("Y-m-d",strtotime($commentandauthor[key($commentandauthor)]->getDate()))." ): ";
                            echo "</div>";
                            echo "<p>".$commentandauthor[key($commentandauthor)]->getCommentText()."</p >";
                            echo '<br/>';
                        }
                    }
                    ?>
                    </div>
                </div>
                
                <br/>
                
                <div class='form-group row'>
                    <div class='col-lg-11 col-md-11 col-sm-11 col-xs-11'>
                        <textarea  class='form-control' id='commentTxt'
                                   name='commentTxt' placeholder="Leave Comment..."
                                    style="width:300px; height: 120px;" ></textarea>
                        <div class="text-danger"><?php echo $validatorError; ?></div>
                    </div>       
                </div>
                
                <div class='row'>
                    <div class='col-lg-6 col-md-8 col-sm-12 col-xs-12 text-left'>
                        <button type='submit' name='addComment' class='btn btn-block btn-primary'>Add Comment</button>
                    </div>
                    <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12 text-left' style="color: red;"></div>
                </div>
            </div>
        </div>
        <input type="hidden" name="selectedImage" value="" /> 
    </form>

</div>          
    
<script type="text/javascript">          
    function OnSelectionChange()
    {
        var albumId = document.getElementById("albumTitle").value;
        insertParam('album',albumId);
        
    }

    function insertParam(key, value) {
    key = encodeURIComponent(key);
    value = encodeURIComponent(value);

    // kvp looks like ['key1=value1', 'key2=value2', ...]
    var kvp = document.location.search.substr(1).split('&');
    let i=0;

    for(; i<kvp.length; i++){
        if (kvp[i].startsWith(key + '=')) {
            let pair = kvp[i].split('=');
            pair[1] = value;
            kvp[i] = pair.join('=');
            break;
        }
    }

    if(i >= kvp.length){
        kvp[kvp.length] = [key,value].join('=');
    }

    // can return this or...
    let params = kvp.join('&');

    // reload page with new params
    document.location.search = params;
}
</script>

<?php include_once './ProjectCommon/Footer.php' ?>
