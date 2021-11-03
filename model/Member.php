<?php

require_once "framework/Model.php";
require_once "Message.php";

class Member extends Model {

    public $pseudo;
    public $hashed_password;
    public $profile;
    public $picture_path;

    public function __construct($pseudo, $hashed_password, $profile = null, $picture_path = null) {
        $this->pseudo = $pseudo;
        $this->hashed_password = $hashed_password;
        $this->profile = $profile;
        $this->picture_path = $picture_path;
    }

    public function write_message($message) {
        return $message->update();
    }

    public function delete_message($message) {
        return $message->delete($this);
    }

    public function get_messages() {
        return Message::get_messages($this);
    }

    public function get_other_members_and_relationships() {
        $query = self::execute("SELECT pseudo,
                     (SELECT count(*) 
                      FROM Follows 
                      WHERE follower=:user and followee=Members.pseudo) as follower,
                     (SELECT count(*) 
                      FROM Follows 
                      WHERE followee=:user and follower=Members.pseudo) as followee
              FROM Members 
              WHERE pseudo <> :user 
              ORDER BY pseudo ASC", array("user" => $this->pseudo));
        return $query->fetchAll();
    }

    public function follow($followee) {
        self::add_follower($this->pseudo, $followee->pseudo);
    }

    public function unfollow($followee) {
        self::delete_follower($this->pseudo, $followee->pseudo);
    }

    private static function add_follower($user, $followee) {
        self::execute("INSERT INTO Follows VALUES (:user,:other)", array("user"=>$user, "other"=>$followee));
        return true;
    }

    private static function delete_follower($user, $followee) {
        self::execute("DELETE FROM Follows WHERE follower = :user AND followee = :other", array("user"=>$user, "other"=>$followee));
        return true;
    }

    public function update() {
        if(self::get_member_by_pseudo($this->pseudo))
            self::execute("UPDATE Members SET password=:password, picture_path=:picture, profile=:profile WHERE pseudo=:pseudo ", 
                          array("picture"=>$this->picture_path, "profile"=>$this->profile, "pseudo"=>$this->pseudo, "password"=>$this->hashed_password));
        else
            self::execute("INSERT INTO Members(pseudo,password,profile,picture_path) VALUES(:pseudo,:password,:profile,:picture_path)", 
                          array("pseudo"=>$this->pseudo, "password"=>$this->hashed_password, "picture_path"=>$this->picture_path, "profile"=>$this->profile));
        return $this;
    }

    public static function get_member_by_pseudo($pseudo) {
        $query = self::execute("SELECT * FROM Members where pseudo = :pseudo", array("pseudo"=>$pseudo));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Member($data["pseudo"], $data["password"], $data["profile"], $data["picture_path"]);
        }
    }

    public static function get_members() {
        $query = self::execute("SELECT * FROM Members", array());
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new Member($row["pseudo"], $row["password"], $row["profile"], $row["picture_path"]);
        }
        return $results;
    }

    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    //ne s'occupe que de la validation "métier" des champs obligatoires (le pseudo)
    //les autres champs (mot de passe, description et image) sont gérés par d'autres
    //méthodes.
    public function validate(){
        $errors = array();
        if (!(isset($this->pseudo) && is_string($this->pseudo) && strlen($this->pseudo) > 0)) {
            $errors[] = "Pseudo is required.";
        } if (!(isset($this->pseudo) && is_string($this->pseudo) && strlen($this->pseudo) >= 3 && strlen($this->pseudo) <= 16)) {
            $errors[] = "Pseudo length must be between 3 and 16.";
        } if (!(isset($this->pseudo) && is_string($this->pseudo) && preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->pseudo))) {
            $errors[] = "Pseudo must start by a letter and must contain only letters and numbers.";
        }
        return $errors;
    }
    
    private static function validate_password($password){
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }
    
    public static function validate_passwords($password, $password_confirm){
        $errors = Member::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }
    
    public static function validate_unicity($pseudo){
        $errors = [];
        $member = self::get_member_by_pseudo($pseudo);
        if ($member) {
            $errors[] = "This user already exists.";
        } 
        return $errors;
    }

    //indique si un mot de passe correspond à son hash
    private static function check_password($clear_password, $hash) {
        return $hash === Tools::my_hash($clear_password);
    }


    
    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public static function validate_login($pseudo, $password) {
        $errors = [];
        $member = Member::get_member_by_pseudo($pseudo);
        if ($member) {
            if (!self::check_password($password, $member->hashed_password)) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a member with the pseudo '$pseudo'. Please sign up.";
        }
        return $errors;
    }

    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public static function validate_photo($file) {
        $errors = [];
        if (isset($file['name']) && $file['name'] != '') {
            if ($file['error'] == 0) {
                $valid_types = array("image/gif", "image/jpeg", "image/png");
                if (!in_array($_FILES['image']['type'], $valid_types)) {
                    $errors[] = "Unsupported image format : gif, jpg/jpeg or png.";
                }
            } else {
                $errors[] = "Error while uploading file.";
            }
        }
        return $errors;
    }

    //pre : validate_photo($file) returns true
    public function generate_photo_name($file) {
        //note : time() est utilisé pour que la nouvelle image n'aie pas
        //       le meme nom afin d'éviter que le navigateur affiche
        //       une ancienne image présente dans le cache
        if ($_FILES['image']['type'] == "image/gif") {
            $saveTo = $this->pseudo . time() . ".gif";
        } else if ($_FILES['image']['type'] == "image/jpeg") {
            $saveTo = $this->pseudo . time() . ".jpg";
        } else if ($_FILES['image']['type'] == "image/png") {
            $saveTo = $this->pseudo . time() . ".png";
        }
        return $saveTo;
    }

}
