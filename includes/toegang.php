<?php
// toegang.php – Rol-gebaseerde toegangscontrole helpers
function heeftToegang($toegestaneRollen = [])
{
    if (empty($toegestaneRollen)) {
        return true;
    }
    if (!isset($_SESSION['rol'])) {
        return false;
    }
    return in_array($_SESSION['rol'], $toegestaneRollen);
}

function vereistToegang($toegestaneRollen = [])
{
    if (!isset($_SESSION['gebruiker_id'])) {
        header('Location: login.php');
        exit;
    }
    if (!heeftToegang($toegestaneRollen)) {
        header('Location: index.php');
        exit;
    }
}
