<?php
/**
 * Created by JetBrains PhpStorm.
 * User: halo
 * Date: 6/30/13
 * Time: 3:26 PM
 * 
 */
// Admin Functions
include(dirname(__FILE__) . '/config/config.php');
$key = filter_var($_POST['pin'], FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
require_once(dirname(__FILE__) . '/lib/autoloader.php');
$crypto = new Crypto();
try{
    //open the database
    $db = new PDO('sqlite:' . $database_path . $database_name);
    $params = array(':key' => $key);
    $query = "SELECT * FROM users WHERE key = :key";
    $rows = $db->prepare($query);
    $rows->execute($params);
    $data = $rows->fetchall();
    // close the database connection
    $errors = $db->errorInfo();
    $db = NULL;
    $stored_hash = $data['0']['hash'];
} catch(PDOException $e) {
    print 'Exception : '.$e->getMessage();
}
$stored = explode('$',$stored_hash);
$supplied_check = $crypto->CheckThis($password, $stored['2']);
$supplied = explode('$',$supplied_check);
$user_hash = $stored['3'];
$supplied_hash = $supplied['3'];
if(($supplied_hash == $user_hash) && $data['0']['isAdmin']){
    //user authenticated
    //setcookie("logged_in", 1, time()+3600);  /* expires in 1 hour */
    ?>
    Welcome to admin <?php if(isset($data['0']['ircName'])) { echo $data['0']['ircName']; } ?>
    <?php
    $crud = new Crud();
    $all_users = $crud->GetAll();
    ?>
    <pre><?php var_dump($all_users);?></pre>
    <?php
} else {
    //login failed
    header('Location: /login.php');
}