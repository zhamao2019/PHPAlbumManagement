
<?php
    require_once "ProjectCommon/Functions.php"; 
    include 'ProjectCommon/Header.php';

    session_start();
    
    $_SESSION['currentPage'] = $_SERVER['REQUEST_URI'];
    $friendId = $_POST["friendId"];
    $friend = getUserById($friendId);
    $validateError = ""; 

    $user=unserialize(serialize($_SESSION["user"]));
    
    if ($_SESSION['user'] == null)
   { 
        exit(header('Location: Login.php'));
    }
     if(isset($_POST['sendFriendRequest']))
    {
        $myPdo = getPDO(); 
        $validateError = ValidateAddFriendById($user->getUserId(), $friendId);   
    }        
?>


<div class="container">
   
    <h3 class="h3">Add Friend</h3><br/>
    <p class="p ">Welcome <strong><?php echo $user->getName();?></strong> (not you? change user <a href="login.php">here</a>)</p>
    <p class="p "> Enter the ID of the user you want to be friends with</p>
    <br/>

    <form method = "post" class="form-inline" action ="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name" class="col-form-label col-l-2">ID:</label>

        <span>&nbsp;</span>
        <input type='text'id='name' class="form-control" value="<?php echo  $_POST['friendId'];?>" name='friendId' >
        <span>&nbsp;</span>
        
        <button type='submit' name='sendFriendRequest' class='btn btn-primary'>Send</button>
    </form>
    
    <div class="text-danger mt-3"><?php print $validateError;?></div>
</div>

<?php include 'ProjectCommon/Footer.php';?>

