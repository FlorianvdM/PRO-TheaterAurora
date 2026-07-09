<?php
// toegang.php – Rol-gebaseerde toegangscontrole helpers
function heeftToegang($toegestaneRollen = []) // Check of gebruiker toegang heeft
{
    if (empty($toegestaneRollen)) {
        return true;
    }
    if (!isset($_SESSION['rol'])) {
        return false;
    }
    return in_array($_SESSION['rol'], $toegestaneRollen);
}

function vereistToegang($toegestaneRollen = []) // Redirect als geen toegang
{
    if (!isset($_SESSION['gebruiker_id'])) { // Niet ingelogd
        header('Location: login.php');
        exit;
    }
    if (!heeftToegang($toegestaneRollen)) { // Onvoldoende rechten
        header('Location: index.php');
        exit;
    }
}
