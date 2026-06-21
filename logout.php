<?php
session_start();
//очистка всех переменных сессии
session_unset();
//полное уничтожение сессии пользователя
session_destroy();
//возврат на страницу авторизации
header("Location:login.php");
exit;