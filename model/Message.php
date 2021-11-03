<?php

require_once "framework/Model.php";
require_once "Member.php";

class Message extends Model {

    public $post_id;
    public $author;
    public $recipient;
    public $body;
    public $private;
    public $date_time;

    public function __construct($author, $recipient, $body, $private, $post_id = NULL, $date_time = NULL) {
        $this->author = $author;
        $this->recipient = $recipient;
        $this->body = $body;
        $this->private = $private;
        $this->post_id = $post_id;
        $this->date_time = $date_time;
    }
    
    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public function validate(){
        $errors = array();
        if(!(isset($this->author) && is_a($this->author,"Member") && Member::get_member_by_pseudo($this->author->pseudo))){
            $errors[] = "Incorrect author";
        }
        if(!(isset($this->recipient) && is_a($this->recipient,"Member") && Member::get_member_by_pseudo($this->recipient->pseudo))){
            $errors[] = "Incorrect recipient";
        }
        if(!(isset($this->body) && is_string($this->body) && strlen($this->body) > 0)){
            $errors[] = "Body must be filled";
        }
        if(!(isset($this->private) && is_bool($this->private))){
            $errors[] = "Private status must be boolean";
        }
        return $errors;
    }

    public static function get_messages($member) {
        $query = self::execute("select * from Messages where recipient = :pseudo order by date_time DESC", array("pseudo" => $member->pseudo));
        $data = $query->fetchAll();
        $messages = [];
        foreach ($data as $row) {
            $messages[] = new Message(Member::get_member_by_pseudo($row['author']), Member::get_member_by_pseudo($row['recipient']), $row['body'], $row['private'], $row['post_id'], $row['date_time']);
        }
        return $messages;
    }

    public static function get_message($post_id) {
        $query = self::execute("select * from Messages where post_id = :id", array("id" => $post_id));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Message(Member::get_member_by_pseudo($row['author']), Member::get_member_by_pseudo($row['recipient']), $row['body'], $row['private'], $row['post_id'], $row['date_time']);
        }
    }
   

    //supprimer le message si l'initiateur en a le droit
    //renvoie le message si ok. false sinon.
    public function delete($initiator) {
        if ($this->author == $initiator || $this->recipient == $initiator) {
            self::execute('DELETE FROM Messages WHERE post_id = :post_id', array('post_id' => $this->post_id));
            return $this;
        }
        return false;
    }

    public function update() {
        if($this->post_id == NULL) {
            $errors = $this->validate();
            if(empty($errors)){
                self::execute('INSERT INTO Messages (author, recipient, body, private) VALUES (:author,:recipient,:body,:private)', array(
                    'author' => $this->author->pseudo,
                    'recipient' => $this->recipient->pseudo,
                    'body' => $this->body,
                    'private' => $this->private ? 1 : 0
                ));
                $message = self::get_message(self::lastInsertId());
                $this->post_id = $message->post_id;
                $this->date_time = $message->date_time;
                return $this;
            } else {
                return $errors; //un tableau d'erreur
            }
        } else {
            //on ne modifie jamais les messages : pas de "UPDATE" SQL.
            throw new Exception("Not Implemented.");
        }
    }

}
