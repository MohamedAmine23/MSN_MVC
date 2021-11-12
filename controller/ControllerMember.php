<?php

require_once 'model/Member.php';
require_once 'model/Message.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMember extends Controller {
    
    const UPLOAD_ERR_OK = 0;

    //gestion de l'édition du profil
    public function edit_profile() {
        $member = $this->get_user_or_redirect();
        $errors = [];
        $success = "";

        // Il est nécessaire de vérifier le statut de l'erreur car, dans le cas où on fait un submit
        // sans avoir choisi une image, $_FILES['image'] est "set", mais le statut 'error' est à 4 (UPLOAD_ERR_NO_FILE).
        if (isset($_FILES['image']) && $_FILES['image']['error'] === self::UPLOAD_ERR_OK) {
            $errors = Member::validate_photo($_FILES['image']);
            if (empty($errors)) {
                $saveTo = $member->generate_photo_name($_FILES['image']);
                $oldFileName = $member->picture_path;
                if ($oldFileName && file_exists("upload/" . $oldFileName)) {
                    unlink("upload/" . $oldFileName);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], "upload/$saveTo");
                $member->picture_path = $saveTo;
                $member->update();
                $success = "Your profile has been successfully updated.";
            } 
        }

        if (isset($_POST['profile'])) {
            //le profil peut être vide : pas de soucis.
            $profile = $_POST['profile'];
            $member->profile = $profile;
            $member->update();
            $success = "Your profile has been successfully updated.";
        }
        (new View("edit_profile"))->show(array("member" => $member, "errors" => $errors, "success" => $success));
    }


    //page d'accueil. 
    public function index() {
        $this->profile();
    }

    //profil de l'utilisateur connecté ou donné
    public function profile() {
        $member = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $member = Member::get_member_by_pseudo($_GET["param1"]);
        }
        (new View("profile"))->show(array("member" => $member));
    }

    public function members(){
        $member=$this->get_user_or_redirect();
        $members=Member::get_members();
        $relations_of_member=$member->get_other_members_and_relationships();
        echo "<pre>";
        print_r($relations_of_member);
        echo "</pre>";
        echo "<pre>";
        print_r($members);
        echo "</pre>";
       
        $view=new View("members");
        $view->show(array("members"=>$members,"relations"=>$relations_of_member));
    }
    function follow(){
        $following="follow";

    }
}
