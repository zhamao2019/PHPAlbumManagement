<?php 
include './ProjectCommon/Header.php';
include './ProjectCommon/Functions.php';
include_once './ProjectCommon/EntityClassLib.php'; 

session_start();        
$id = $_POST["userId"];
$password = $_POST["password"];
$hashedPassword = hash("sha256", $_POST["password"]);

$loginErrorMsg = "";

if(isset($_POST["btnClear"])){
    $id=$password="";
    $_POST = array();
}

// validate all the form
if(isset($_POST["btnSubmit"])){
    try {
        $user= getUserByIdAndPassword($id, $hashedPassword);
        if (!trim($id) || !trim($password)){
            $loginErrorMsg = "User ID and Password can not be blank";
        }
        elseif ($user == null)
        {
            $loginErrorMsg = 'Incorrect User ID and/or Password';
        }
        else
        {
            $_SESSION['user'] = $user; 
            if(!isset($_SESSION['currentPage'])){
                header("Location: Index.php");
                exit();
            }
            else {
                header("Location: ". $_SESSION['currentPage']);
                exit();
            }
                        
        }
    }
    catch (Exception $e)
    {
        die("The system is currently not available, try again later");
    }
}
?>

<div class="container">
    <h1>Log In</h1>
    <p>If you do not have an account, please <a href="NewUser.php">Sign up</a></p>
    
    <form method = "POST" action = "<?=$_SERVER['PHP_SELF'];?>">
        <div class="text-danger"><?php echo $loginErrorMsg ?></div>
        <div class="form-group row">
            <label for="studentId" class="col-lg-2">Student ID:</label>
            <div class="col-lg-3">
                <input type="text" class="form-control" name="userId" id="studentId" value="<?php echo $_POST["userId"]; ?>"/>
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-lg-2">Password:</label>
            <div class="col-lg-3">
                <input type="password" class="form-control" name="password" id="password" value="<?php echo $_POST["password"]; ?>"/>
            </div>
        </div>
        
        <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
        <button type="submit" name="btnClear" id="btnClear" value="clear" class="btn btn-primary">Clear</button>
    </form>
    
</div>




<?php include './ProjectCommon/Footer.php'; ?>