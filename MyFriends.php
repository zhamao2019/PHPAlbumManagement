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
    $validatorError = "";      
    $user=unserialize(serialize($_SESSION["user"]));
    $friendsArray = getFriendsByUserId($user->getUserId());
    
//Delete Friend
    if(isset($_POST['defriendBtn'])){
        if (isset($_POST['defriendCheckbox'])){
            foreach ($_POST['defriendCheckbox'] as $row)
            {   
                deleteFriendById($user->getUserId(), $row);
            }
            header('Location: MyFriends.php'); 
            exit;             
        }
        else 
        {
            $validatorError = "You must select at least one checkbox!"; 
        }          
    }
    //Accept Selected 
    if (isset($_POST['acceptBtn'])){
        if (isset($_POST['acceptDeny'])){
            foreach ($_POST['acceptDeny'] as $row){   
            updateFriendshipById($row,$user->getUserId()) ;
            }
            header('Location: MyFriends.php'); 
            exit; 
        }
        else 
        {
            $validatorError = "You must select at least one checkbox!"; 
        }   
    }
    //Deny Selected
    if (isset($_POST['denyBtn'])){
        if (isset($_POST['acceptDeny'])){
            foreach ($_POST['acceptDeny'] as $row){
                denyFriendshipById($row,$user->getUserId());
            }
            header('Location: MyFriends.php'); 
            exit;            
        }
        else 
        {
            $validatorError = "You must select at least one checkbox!"; 
        }   
    }
?>
 <div class="container">
            <form method = "post" action ="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h3 class="h3">My Friends</h3><br/>
            <p class="p ">Welcome <strong><?php echo $user->getName();?></strong> (not you? change user <a href="login.php">here</a>)</p>
            <table class="table">
            <thead>
                <tr>
                    <th scope="col-3">Friends:</th>
                    <th scope="col-3"></th>
                    <th scope="col-3"><a href="AddFriend.php">Add Friends</a></th>                                                                             
                </tr>
                <tr>
                    <th scope="col-3">Name</th>
                    <th scope="col-3">Shared Albums</th>
                    <th scope="col-3">Defriend</th>                                                                             
                </tr>
            </thead>           
            <div class='col-3 text-danger'> <?php print $validatorError;?></div>
            <br/>
            <tbody>
            <?php   
            foreach($friendsArray as $row){
                $friendSharedAlbums = getSharedAlbumsByUserId($row);
                $friend = getUserById($row);
                $rowName= $friend->getName();
                    echo "<tr>"; 
                    echo "<td scope='col'><a href='FriendPictures.php?friendId=".$row."'>".$rowName."</a></td>"; 
                    echo "<td scope='col'>".count($friendSharedAlbums)."</td>"; 
                    echo "<td scope='col'><input type='checkbox' name='defriendCheckbox[]' value='$row'/></td>";   
                    echo "</tr>";     
            }
            ?>              
        </tbody>
        </table>
        <div class='form-group row'>                          
            <div class='col-3'>                    
                <button type='submit'style="float:right" name='defriendBtn' class='btn btn-primary col-6' onclick="return confirm('The selected friend will be defriended!')">Defriend Selected</button>  
            </div> 
        </div>     
            <br/>
            <table class="table">
            <thead>
                <tr>
                    <th scope="col-3">Friend Requests:</th>
                    <th scope="col-3"></th>                                                                             
                </tr>
                <tr>
                    <th scope="col-3">Name</th>
                    <th scope="col-3">Accept or Deny</th>                                                                             
                </tr>
            </thead>                          
            <tbody>
           <?php       
            $requestFriend = getFriendRequesterByUserId($user->getUserId());
            foreach ($requestFriend as $row)
            {
                $friendrequest=array();
                $friendr=getUserById($row);
                $fname=$friendr->getName();
                echo "<tr>";
                echo "<td scope='col-3'>".$fname."</td>"; 
                echo "<td scope='col-3'><input type='checkbox' name='acceptDeny[]' value='$row' /></td>";          
                echo "</tr>";
            }            
            ?>  
            </tbody>
        </table>       
        <div class='form-group row' style="float:right">                                   
            <button type='submit' name='acceptBtn' class='btn btn-primary col-3'>Accept Selected</button>                     
                    <button type='submit' name='denyBtn' class='btn col-3' onclick='return confirm("The selected request will be denied!")'>Deny Selected</button>
                </div> 
            </form>
        </div>     
        <?php
        include_once './ProjectCommon/Footer.php'
        ?>

