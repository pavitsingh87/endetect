<?php
include_once("connection.php");

// Unset all of the session variables.
session_unset();
unset($_SESSION);
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//         $params["path"], $params["domain"],
//         $params["secure"], $params["httpsonly"]
//     );
// }

// Finally, destroy the session.
unset($_COOKIE['email']);
unset($_COOKIE['name']);
unset($_COOKIE['ownerid']);
unset($_COOKIE['company']);
unset($_COOKIE['ownersession']);
unset($_COOKIE['mobile']);
unset($_COOKIE['pincode']);
session_destroy();
header("Location: ". baseurl ."login.php");
?>
