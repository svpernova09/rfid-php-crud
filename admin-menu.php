<?php
/**
 * Created by JetBrains PhpStorm.
 * User: halo
 * Date: 7/19/13
 * Time: 9:50 PM
 * 
 */
if (array_key_exists('message', $_GET)) {
    $message = filter_var($_GET['message'], FILTER_SANITIZE_STRING);
    if (!empty($message)) {
        ?>
        <div class="col-lg-4 alert alert-warning">
            <?=$message?>
        </div>
        <?php
    }
}
?>
<div class="admin_menu col-lg-4">
    <h1>Welcome to admin <?php if(isset($_SESSION['ircName'])) { echo $_SESSION['ircName']; } ?></h1>
    <a href="admin.php">Admin Home</a> |
    <a href="admin.php?action=add">Add User</a> |
    <a href="admin.php?action=viewlogs">View logs</a> |
    <a href="admin.php?action=pullusers">Pull Users</a> |
</div>