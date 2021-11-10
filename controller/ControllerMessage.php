<?php
require_once 'Model/Member.php';
require_once 'Model/Message.php';
require_once 'framework/Configuration.php';
require_once 'framework/Tools.php';

class ControllerMessage extends Controller{
    
    

    function index(){
        $user = $this->get_user_or_redirect();
        $errors = [];
        $recipient = $this->get_recipient($user);
    if (isset($_POST['body'])) {
        $body = $_POST['body'];
        $private = isset($_POST['private']) ? TRUE : FALSE;
        $message = new Message($user, $recipient, $body, $private);
        $errors = $message->validate();
        if(empty($errors)){
            $user->write_message($message);                
        }
    }
    //if(isset($_GET["action"]) && $_GET["action"] != ""){
      //  $action = $_GET["action"];
        //if($action === "erase"){
          //  $this->delete($user);
        //}
    //}
    $view=new View("messages");
    $messages = $recipient->get_messages();
    $view->show(array("messages"=>$messages,"recipient"=>$recipient,"errors"=>$errors,"user"=>$user));

    }
    
    //supprime le message dont l'id est dans la request.
    function delete(){
        
        $user = $this->get_user_or_redirect();
        if (isset($_POST['param']) && $_POST['param'] != "")
        {
            $post_id = $_POST['param'];
            $message = Message::get_message($post_id);
            
            if ($message && ($message->author == $user || $message->recipient == $user))
            {
                $user->delete_message($message);
                $member = $message->recipient;
                $this->redirect("message","index",$member->pseudo);
            }
            else{
                Tools::abort("Wrong/missing param or action no permited");
            }
        }
        else{
            Tools::abort("Wrong/missing param or action no permited");   
        }
    }
    //détermine le destinataire courant
    function get_recipient($user){
    if(!isset($_GET["param1"]) || $_GET["param1"]==""){
        return $user;
    } else{
        return Member::get_member_by_pseudo($_GET["param1"]);
    }
}
}