<?php
//настройки подключения к бд
$host="mysql-8.0";
$user="root";
$password="";
$database="vodit_rf";
//подключение к бд
$conn=mysqli_connect($host,$user,$password,$database);
//проверка подключения
if (!$conn){
    die("Ошибка подключения: ".mysqli_connect_error());
}