<?php
session_start();
session_destroy(); // Apaga os dados de login da memória do servidor
header("Location: index.php"); // Manda de volta para a tela de login
exit();
?>