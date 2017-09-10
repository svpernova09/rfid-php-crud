<?php

namespace App;

class MemberController extends Config
{
    private $crud;

    public function __construct()
    {
        parent::__construct();
        $this->crud = new Crud();
    }

    public function updateMember($user)
    {
        $update = $this->crud->UpdateUser($user);

        if ($update['status'] !== 'success')
        {
            throw new \Exception($update['reason']);
        }

        return $update['rowid'];
    }

    public function pollMembers()
    {
        $results = $this->client->getMembers();

        foreach((array) $results['members'] as $member)
        {
            $this->crud->UpdateUser([
                ':key' => $member['key'],
                ':hash' => $member['hash'],
                ':ircName' => $member['irc_name'],
                ':spokenName' => $member['spoken_name'],
                ':addedBy' => $member['added_by'],
                ':dateCreated' => $member['date_created'],
                ':lastLogin' => $member['last_login'],
                ':isAdmin' => $member['admin'],
                ':isActive' => $member['active'],
            ]);
        }

        header('Location: /admin.php?message=Members Updated');
    }

    public function doEditUser()
    {
        $sanitizer = new Sanitizer();
        $_POST = $sanitizer($_POST);
        $key = filter_var($_GET['key'], FILTER_SANITIZE_NUMBER_INT);
        //look at $_SESSION['key'] and check if Admin
        // Or we could look at rowid and see if that key is $_SESSION['key']
        $crypto = new Crypto();
        $this_user = $this->crud->GetThisUser($key);
        if($this->debug) { var_dump($this_user); }
        if($this->debug) { var_dump($_POST); }
        if($_SESSION['key'] == $this_user['0']['key']){
            if(isset($_POST)){
                foreach($_POST AS $post_key => $value){
                    $_POST[$post_key] = $value;
                }
            }
            $data = array(':ircName'=>$_POST['ircName'],
                          ':spokenName'=>$_POST['spokenName'],
                          ':key'=>$key);
            if(!empty($_POST['pin'])){
                $hash = $crypto->SecureThis($_POST['pin']);
                $data[':hash'] = $hash;

            }
            $errors = $this->crud->UpdateUserSelf($data);
            if($errors['status'] == 'success'){
                $msg = "Update Successful";
            } else {
                $error_msg = "Failed to Update";
                echo $error_msg . "<br />";
                if($debug) { var_dump($errors); }
            }
        }
        header('Location: /user.php?message='.$msg);
    }

    public function delete()
    {
        $row_id = filter_var($_GET['rowid'], FILTER_SANITIZE_NUMBER_INT);
        $errors = $this->crud->Remove($row_id);

        if($errors['status'] == 'success'){
            header('Location: /admin.php?message=Remove Successful');
        } else {
            if($this->debug) { var_dump($errors); }
            throw new \Exception('Something Went Wrong');
        }
    }
}