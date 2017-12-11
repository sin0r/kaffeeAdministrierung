<?php
/**
 * Created by PhpStorm.
 * User: s.lory
 * Date: 11.12.2017
 * Time: 11:17
 */
session_start();
$username = $_SESSION["username"];
echo 'Herzlich Willkommen, ' . $username . '!';