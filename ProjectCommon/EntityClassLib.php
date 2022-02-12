<?php

class User {
    private $userId;
    private $name;
    private $phone;
    
    private $messages;
    
    public function __construct($userId, $name, $phone)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->phone = $phone;
        
        $this->messages = array();
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getName() {
        return $this->name;
    }

    public function getPhone() {
        return $this->phone;
    }
}


class Album {
    private $albumId;
    private $title;
    private $description;
    private $date;
    private $ownerId;
    private $accessbilityCode;
    private $pictures;

    public function __construct($albumId, $title, $description, $date, $ownerId, $accessibility)
    {
        $this->albumId = $albumId;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->ownerId = $ownerId;
        $this->accessbilityCode = $accessibility;
        
        $this->pictures = array();
    }

    public function getAlbumId() {
        return $this->albumId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }
    
    public function getDate() {
        return $this->date;
    }
    
    public function getAcessibilityCode() {
        return $this->accessbilityCode;
    }
    
    public function getPictures() {
        return $this->pictures;
    }
    
    public function setAccessbilityCode($accessbilityCode): void {
        $this->accessbilityCode = $accessbilityCode;
    }
 
}

class Accessibility {

    private $accessibilityCode;
    private $description;

    function __construct($accessibilityCode, $description) {
        $this->accessibilityCode = $accessibilityCode;
        $this->description = $description;
    }

    function getAccessibilityCode() {
        return $this->accessibilityCode;
    }

    function getDescription() {
        return $this->description;
    }

}

class Picture {

    private $pictureId;
    private $albumId;
    private $fileName;
    private $title;
    private $description;
    private $dateAdded;

    function __construct($pictureId, $albumId, $fileName, $title, $description, $dateAdded) {
        $this->pictureId = $pictureId;
        $this->albumId = $albumId;
        $this->fileName = $fileName;
        $this->title = $title;
        $this->description = $description;
        $this->dateAdded = $dateAdded;
    }

    function getPictureId() {
        return $this->pictureId;
    }

    function getAlbumId() {
        return $this->albumId;
    }

    function getFileName() {
        return $this->fileName;
    }

    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function getDateAdded() {
        return $this->dateAdded;
    }

}

class Comment {

    private $commentId;
    private $authorId;
    private $pictureId;
    private $commentText;
    private $date;
    function __construct($commentId, $authorId, $pictureId, $commentText, $date) {
        $this->commentId = $commentId;
        $this->authorId = $authorId;
        $this->pictureId = $pictureId;
        $this->commentText = $commentText;
        $this->date = $date;
    }
    function getCommentId() {
        return $this->commentId;
    }

    function getAuthorId() {
        return $this->authorId;
    }

    function getCommentText() {
        return $this->commentText;
    }

    function getDate() {
        return $this->date;
    }

    function setCommentId($commentId): void {
        $this->commentId = $commentId;
    }

    function setAuthorId($authorId): void {
        $this->authorId = $authorId;
    }

    function setPictureId($pictureId): void {
        $this->pictureId = $pictureId;
    }

    function setCommentText($commentText): void {
        $this->commentText = $commentText;
    }


}

class Friendship {

    private $friendshipRequesterId;
    private $friendshipRequesteeId;
    private $status;

    function __construct($friendshipRequesterId, $friendshipRequesteeId, $status) {
        $this->friendshipRequesterId = $friendshipRequesterId;
        $this->friendshipRequesteeId = $friendshipRequesteeId;
        $this->status = $status;
    }

    function getFriendshipRequesterId() {
        return $this->friendshipRequesterId;
    }

    function getFriendshipRequesteeId() {
        return $this->friendshipRequesteeId;
    }

    function getStatus() {
        return $this->status;
    }

}

