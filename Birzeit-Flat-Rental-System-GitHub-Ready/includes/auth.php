<?php

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__.'/database.inc.php';

function login($username, $password){
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT u.*, a.password_hash
        FROM users u JOIN accounts a ON u.id = a.user_id
        WHERE a.username = :u
    ");
    $stmt->execute([':u' => $username]);
    $row = $stmt->fetch();

    if ($row && password_verify($password, $row['password_hash'])){
        $_SESSION['uid']   = $row['id'];
        $_SESSION['type']  = $row['user_type'];
        $_SESSION['name']  = $row['full_name'];
        $_SESSION['photo'] = $row['photo_url'];
        $_SESSION['mobile']= $row['mobile'];
        return true;
    }
    return false;
}

function require_login($role = null){
    if (empty($_SESSION['uid']) || ($role && $_SESSION['type'] != $role)){
        header('Location: /public/login.php');
        exit;
    }
}

function logout(){
    if (session_status() === PHP_SESSION_ACTIVE) session_destroy();
}
