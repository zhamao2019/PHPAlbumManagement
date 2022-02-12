<?php
include_once 'EntityClassLib.php';

function getPDO()
{
    $dbConnection = parse_ini_file("DBConnection.ini");
    extract($dbConnection);
    return new PDO($dsn, $scriptUser, $scriptPassword);  
}


// User functions
// add a new user
function addNewUser($userId, $name, $phone, $password)
{
    $pdo = getPDO();
     
    $sql = "INSERT INTO User VALUES( :userId, :name, :phone, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId, 'name' => $name, 'phone' => $phone, 'password' => $password]);
}

// get user by userId and password; 
function getUserByIdAndPassword($id, $password)
{
    $pdo = getPDO();
    
    $sql = "SELECT UserId, Name, Phone FROM User WHERE UserId = :userId AND Password = :password";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['userId'=>$id, 'password'=>$password]);
    $row = $pStmt->fetch(PDO::FETCH_ASSOC);
        
    if ($row)
    {       
        return new User($row['UserId'], $row['Name'], $row['Phone'] );            
    }
    else
    {
        return null;
    }   
}

// get a user by id
function getUserById($id){
    $pdo = getPDO();
    
   $sql = "SELECT UserId, Name, Phone FROM User WHERE UserId = :userId";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['userId'=>$id]);
    $row = $pStmt->fetch(PDO::FETCH_ASSOC);
        
    if ($row)
    {       
        return new User($row['UserId'], $row['Name'], $row['Phone'] );            
    }
    else
    {
        return null;
    }   
}


// Album functions
// add a new Album
function addNewAlbum($userId, $title, $accessibility, $description)
{
    $pdo = getPDO();
    
    $sql = "INSERT INTO Album (Title, Description, Owner_Id, Accessibility_Code) "
            . "VALUES( :title, :description, :userId, :accessibilityCode)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId,
                    'title' => $title, 
                    'accessibilityCode' => $accessibility, 
                    'description' => $description]);
}

// get Album by albumId; 
function getAlbumById($id)
{
    $pdo = getPDO();
    
    $sql = "SELECT Album_Id, Title, Description, Date_Updated, Owner_Id, Accessibility_Code "
            . "FROM Album WHERE Album_Id = :albumId";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['albumId'=>$id]);
    $row = $pStmt->fetch(PDO::FETCH_ASSOC);
        
    if ($row)
    {       
        return new Album($row['Album_Id'], 
                $row['Title'],
                $row['Description'], 
                $row['Date_Updated'],
                $row['Owner_Id'],
                $row['Accessibility_Code']);            
    }
    else
    {
        return null;
    }   
}

//get albums for a user
function getAlbumsByUserId($id)
{
    $albums = array();
    $pdo = getPDO();

    $sql = "SELECT Album_Id, Title, Description, Date_Updated, Owner_Id, Accessibility_Code " 
            . "FROM Album WHERE Owner_Id = :userId";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['userId'=>$id]);      
       
    foreach ($pStmt as $row ){
            $album = new Album( $row['Album_Id'], 
                    $row['Title'], 
                    $row['Description'],
                    $row['Date_Updated'],
                    $row['Owner_Id'],
                    $row['Accessibility_Code']);
            
            $albums[] = $album;
        }
    return $albums;
}

//get shared albums of a user
function getSharedAlbumsByUserId($id)
{
    $albums = array();
    $pdo = getPDO();

    $sql = "SELECT Album_Id, Title, Description, Date_Updated, Owner_Id, Accessibility_Code " 
            . "FROM Album WHERE Owner_Id = :userId AND Accessibility_Code = 'shared'";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['userId'=>$id]);      
       
    foreach ($pStmt as $row ){
            $album = new Album( $row['Album_Id'], 
                    $row['Title'], 
                    $row['Description'],
                    $row['Date_Updated'],
                    $row['Owner_Id'],
                    $row['Accessibility_Code']);
            
            $albums[] = $album;
        }
    return $albums;
}


// change album accessibility
function updateAlbumAccessibilityById($id, $accessibilityCode){
    $pdo = getPDO();
    
    $sql = "Update Album SET Accessibility_Code = :accessibility WHERE Album_Id = :albumId";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['albumId'=>$id, 'accessibility'=>$accessibilityCode]);        
}

// delete an album 
function deleteAlbumById($id){
    $pdo = getPDO();
    $sql = "DELETE FROM Album WHERE Album_Id = :albumId ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['albumId'=>$id]); 
}




// Pictures functions
// add a new picture
function addNewPicture($albumId, $title, $description, $file)
{
    $pdo = getPDO(); 
    
    $sql = "INSERT INTO Picture (Album_Id, FileName, Title, Description) "
            . "VALUES( :albumId, :fileName, :title, :description)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['albumId' => $albumId,
                    'fileName' => $file,
                    'title' => $title, 
                    'description' => $description]);
}

//Get pictures of an album
function getPicturesByAlbumId($id)
{
    $pictures = array();
    $pdo = getPDO();
        
    $sql = "SELECT Picture_Id, Album_Id, FileName, Title, Description, Date_Added FROM Picture "
            . "WHERE Album_Id = :albumId";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['albumId'=>$id]);      
       
    foreach ($pStmt as $row ){
            $picture = new Picture( $row['Picture_Id'],
                    $row['Album_Id'],
                    $row['FileName'],
                    $row['Title'], 
                    $row['Description'],
                    $row['Date_Added']);
            $pictures[] = $picture;
        }
    return $pictures;
}


// get Picture by id; 
function getPictureById($id)
{
    $pdo = getPDO();
    
    $sql = "SELECT Picture_Id, Album_Id, FileName, Title, Description, Date_Added "
            . "FROM Picture WHERE Picture_Id = :pictureId";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['pictureId'=>$id]);
    $row = $pStmt->fetch(PDO::FETCH_ASSOC);
        
    if ($row)
    {       
        return new Picture($row['Picture_Id'],
                $row['Album_Id'], 
                $row['FileName'],
                $row['Title'],
                $row['Description'], 
                $row['Date_Added']);            
    }
    else
    {
        return null;
    }   
}

// get the file path of a thumbnail picture
function getThumbnailFilepathOfPicture($pictureId)
{
        $myPdo= getPDO();
        $sqlFindName = 'select FileName from Picture where Picture_Id = :pictureId';
        $stmtFindName = $myPdo->prepare($sqlFindName);
        $stmtFindName->execute(['pictureId'=>$pictureId]);
        $row=$stmtFindName->fetch(PDO::FETCH_ASSOC);
        $fileName=$row['FileName'];
        $filePath = './thumbnails/'.$fileName;

        return $filePath;
}

// resample Picture
function resamplePicture($filePath, $destinationPath, $maxWidth, $maxHeight)
{
    if (!file_exists($destinationPath))
    {
        mkdir($destinationPath);
    }

    $imageDetails = getimagesize($filePath);

    $originalResource = null;
    if ($imageDetails[2] == IMAGETYPE_JPEG) 
    {
        $originalResource = imagecreatefromjpeg($filePath);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_PNG) 
    {
        $originalResource = imagecreatefrompng($filePath);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_GIF) 
    {
        $originalResource = imagecreatefromgif($filePath);
    }
    $widthRatio = $imageDetails[0] / $maxWidth;
    $heightRatio = $imageDetails[1] / $maxHeight;
    $ratio = max($widthRatio, $heightRatio);

    $newWidth = $imageDetails[0] / $ratio;
    $newHeight = $imageDetails[1] / $ratio;

    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    $success = imagecopyresampled($newImage, $originalResource, 0, 0, 0, 0, $newWidth, $newHeight, $imageDetails[0], $imageDetails[1]);

    if (!$success)
    {
        imagedestroy(newImage);
        imagedestroy(originalResource);
        return "";
    }
    $pathInfo = pathinfo($filePath);
    $newFilePath = $destinationPath."/".$pathInfo['filename'];
    if ($imageDetails[2] == IMAGETYPE_JPEG) 
    {
        $newFilePath .= ".jpg";
        $success = imagejpeg($newImage, $newFilePath, 100);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_PNG) 
    {
        $newFilePath .= ".png";
        $success = imagepng($newImage, $newFilePath, 0);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_GIF) 
    {
        $newFilePath .= ".gif";
        $success = imagegif($newImage, $newFilePath);
    }

    imagedestroy($newImage);
    imagedestroy($originalResource);

    if (!$success)
    {
        return "";
    }
    else
    {
        return "newFilePath";
    }
}


//Delete a picture and all its comments. 
//A picture's comments must be deleted before delete the picture.
function deletePictureAndCommentsByPictureId($id){
    $pdo = getPDO();
    $sql = "DELETE FROM Comment WHERE Picture_Id = :pictureId"; 
    $sql1 = "DELETE FROM Picture WHERE Picture_Id = :pictureId";
    
    $stmt = $pdo->prepare($sql);
    $stmt1 = $pdo->prepare($sql1);
 
    $stmt->execute(['pictureId'=>$id]); 
    $stmt1->execute(['pictureId'=>$id]); 
}





// comment functions
// add a comment
function addNewComment($userId, $pictureId, $text)
{
    $pdo = getPDO(); 

    $sql = "INSERT INTO Comment (Author_Id, Picture_Id, Comment_Text)"
            . " VALUES( :authorId, :pictureId, :commentText)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['authorId' => $userId,
                    'pictureId' => $pictureId,                   
                    'commentText' => $text]);
}

//Get comments and their authors for a picture
function getCommentsAndAuthorsByPictureId($id)
{
    $commentInfo = array();
    $commentInfos = array();
    $pdo = getPDO();
    
    $sql = "SELECT Comment_Id, Picture_Id, Comment_Text, Date, UserId, Name, Phone FROM Comment " 
           . "INNER JOIN User ON Comment.Author_Id = User.UserId WHERE Picture_Id = :pictureId";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['pictureId'=>$id]);
   
    foreach ($pStmt as $row ){
        $comment = new Comment( $row['Comment_Id'],$row['UserId'], $row['Picture_Id'], $row['Comment_Text'], $row['Date']);
        $author = new User($row['Author_Id'], $row['Name'], $row['Phone']);
        $commentInfo[$author->getName()] = $comment;

        $commentInfos[] = $commentInfo;
        $commentInfo = array();
    }

    return $commentInfos;   
}




// friend functions
// add a friendship
function addNewFriendshipRequest($userId, $requesteeId)
{
    $pdo = getPDO(); 
    
    $sql = "INSERT INTO Friendship VALUES( :userId, :requesteeId, 'request')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId, 'requesteeId' => $requesteeId]);
}

//Accept a friend request
function updateFriendshipById($requesterId, $requesteeId){
    $pdo = getPDO();
    
    $sql = "UPDATE Friendship SET Status = 'accepted' "
           . "WHERE Friendship_RequesterId = :requesterId AND Friendship_RequesteeId = :userId";
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['requesterId'=>$requesterId, 'userId'=>$requesteeId]);        
}

//Deny a friend request
function denyFriendshipById($requesterId, $requesteeId){
    $pdo = getPDO();
    
//    $sql = "DELETE FROME Friendship "
//           . "WHERE Friendship_RequesterId = :requesterId "
//            . "AND Friendship_RequesteeId = :userId "
//            . "AND Status='request'";
    $sql = "DELETE FROM `friendship` "
            . "WHERE `friendship`.`Friendship_RequesterId` = :requesterId "
            . "AND `friendship`.`Friendship_RequesteeId` = :userId ";
    
    $stmt = $pdo->prepare($sql);
 
    $stmt->execute(['requesterId'=>$requesterId, 'userId'=>$requesteeId]); 
}

//Get friends for a user. 
function getFriendsByUserId($id)
{
    $friends = array();
    $pdo = getPDO();

    //The first query returns all friends to whom the user initiated the requests. 
    $sql = "SELECT Friendship_RequesteeId FROM Friendship " 
            . "WHERE Friendship_RequesterId = :userId AND Status = 'accepted'"; 
    
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['userId'=>$id]);  
    
    foreach ($pStmt as $row ){
            $friends[] = $row['Friendship_RequesteeId'];
        }
    
    //The second query returns all friends whose requests the user accepted. 
    $sql = "SELECT Friendship_RequesterId FROM Friendship " 
            . "WHERE Friendship_RequesteeId = :userId AND Status = 'accepted'";
    
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['userId'=>$id]);      
       
    foreach ($pStmt as $row ){
            $friends[] = $row['Friendship_RequesterId'];
        }
    
    return $friends;
    
}

//Get friend requesters of a user
function getFriendRequesterByUserId($id)
{
    $requesters = array();
    $pdo = getPDO();

    $sql = "SELECT Friendship_RequesterId FROM Friendship " 
            . "WHERE Friendship_RequesteeId = :userId AND Status = 'request'"; 
    
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['userId'=>$id]);  
    
    foreach ($pStmt as $row ){
            $requesters[] = $row['Friendship_RequesterId'];
        }
    
    return $requesters;
    
}

//Delete a friend of a user:
function deleteFriendById($userId, $friendId){
    $pdo = getPDO();
    
    $sql = "DELETE FROM Friendship " 
           . "WHERE ((Friendship_RequesterId = :userId AND Friendship_RequesteeId = :friendId) " 
           . " OR (Friendship_RequesterId = :friendId AND Friendship_RequesteeId= :userId)) " 
           . " AND Status='accepted'";
    
    $stmt = $pdo->prepare($sql);
 
    $stmt->execute(['userId'=>$userId, 'friendId'=>$friendId]); 
}


// get accessibilities
function getAllAccessibilities() {

    $accessibilities = array();

    $pdo = getPDO();

    $sql = "SELECT Accessibility_Code, Description FROM Accessibility";
    $stmt = $pdo->query($sql);
    if ($stmt) {
        foreach ($stmt as $row) {
            // Add each record to an array
            $accessibility = new Accessibility($row['Accessibility_Code'], $row['Description']);
            $accessibilities[] = $accessibility;
        }
        // Return the array of course codes
        return $accessibilities;
    } else {
        throw new Exception("query failed, SQL statement: $sql");
    }
}




// Validation functions 

//
// 
function ValidatePictureTitle($txtPictureTitle) {
    if ($txtPictureTitle == "") {
        return "Picture Title cannot be blank";
    }
}

function ValidateFileUpload($files, $name) {
    $allowed = array('gif', 'png', 'jpg', 'jpeg');
    $total = count($_FILES[$name]['name']);

    if (in_array(1, $files[$name]['error'], false)) {
        return "Upload file is too large";
    }
    if (in_array(4, $files[$name]['error'], false)) {
        return "No upload file specified";
    }

    //validates extensions and sizes for all files 
    for ($i = 0; $i < $total; $i++) {
        $filename = $files[$name]['name'][$i];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            return 'Accepted picture types: JPG(JPEG), GIF and PNG!';
        }
    }
}

// Add friend validation
function ValidateAddFriendById($userId, $friendId) {
    $requsters = getFriendRequesterByUserId($userId);
    $friend = getUserById($friendId);
    $userHasRequest = false;
    $friendIdExised = false;
    $friendshipExisted = false;
    $friendshipRequestExisted = false;
    $friends = getFriendsByUserId($userId);
    
    // check if the friend id is existed
    if(isset($friend)){
        $friendIdExised = true;
    }
 
    // If A sends a friend request to B, 
    // while A has a friend request from B waiting for A to accept
    foreach($requsters as $requster){
        if($requster == $friendId){
            $userHasRequest = true;
        }
    }
    
    // send to the same friend request twice
    $friendRequesters = getFriendRequesterByUserId($friendId);
    foreach ($friendRequesters as $fr){
        if($fr == $userId){
            $friendshipRequestExisted = true;
        }
    }
    
    // If A and B are friends
    foreach ($friends as $f){
        if($f == $friendId ){
            $friendshipExisted = true;
        }
    }
    
    if($friendId == null){
        return $errorMsg = "You must enter a fiend ID";
    }
    // The entered user ID must exist.
    elseif (!$friendIdExised) {
         return $errorMsg = "This user not existed";
    }
    // A user can not send a friend request to her/himself
    elseif ($userId == $friendId) {
        return $errorMsg = "You can not send a friend request to yourself";
    }
    elseif ($userHasRequest) {
        updateFriendshipById($friendId, $userId);
        return $errorMsg = "You and ".$friend ->getName()."(ID: ".$friendId." ) are friends now";
    }
    elseif($friendshipRequestExisted){
        return $errorMsg = "You can not sent the request to the same user twice";
    }
    elseif ($friendshipExisted) {
        return $errorMsg = "You and this user are already friends";
    }
    else {
        addNewFriendshipRequest($userId, $friendId);
        return $errorMsg = "Your request was sent to ". $friend ->getName() 
                        . " (ID:" . $friendId . "). "
                        . "<br>" ."Once " . $friend ->getName() . " accepts your request, you and ". $friend ->getName() . " will be friends "
                        . "and will be able to see each others' shared albums.";
    }
}

//validate album and file
function ValidateBlankAlbum($albumTxt){
        if ($albumTxt == ""){
            return "Album is required";
        }
    }





// if id is blank
function ValidateId($id) {
    $pdo = getPDO();  
    $sql = "SELECT UserId FROM User WHERE UserId = :userId";      
    $pStmt = $pdo ->prepare($sql); 
    $pStmt ->execute(['userId'=>$id]);
    $row = $pStmt->fetch(PDO::FETCH_ASSOC);
    
    $idExised = false;
    
    if ($row)
    {
       $idExised = true;
    }
    else
    {
        return null;
    }

    if( !trim($id) ){
        return $errorMsg = 'User ID can not be blank';
    }
    elseif ($idExised) {
         return $errorMsg = "A User with this ID has already existed";
    }
    else {
        return $errorMsg = "";
    }
}

// if name is blank
function ValidateName($name) {
    if( !trim($name) ){
        return $errorMsg = 'Name can not be blank';
    }
    else {
        return $errorMsg = "";
    }
}

// if phone is blank or incorrect format
function ValidatePhone($phone) {
    // nmm-nmm-mmm, n is not 0 or 1
    $phoneRege = "/^[2-9](\d{2})-[2-9](\d{2})-(\d{4})$/i";

    if( !trim($phone) ){
        return $errorMsg = 'Phone number can not be blank';
    }
    elseif(!preg_match($phoneRege, $phone)) {
        return $errorMsg = "Incorrect Phone Format";
    }
    else {
        return $errorMsg = "";
    }
}
// if email is blank or incorrect format
function ValidatePassword($password) {

    $passwordRege = "^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$^";

    if( !trim($password)){
        return $passwordErrorMsg = 'Password can not be blank';
    }
    elseif(!preg_match($passwordRege, $password)) {
        return $errorMsg = "Password contains should at least 6 characters,one upper case, one lowercase and one digit.";
    }
    else {
        return $errorMsg = "";
    }
}

function ValidateRePassword($password, $re) {

    if( !trim($re)){
        return $passwordErrorMsg = 'Enter your password again to confirm';
    }
    elseif($password != $re){
        return $errorMsg = "Your password are not same";
    }
    else {
        return $errorMsg = "";
    }
}




?>
