<?php
require_once __DIR__ . "/../../middleware/auth.php";

sessionStart();
$_SESSION = [];
session_destroy();
header('Location: ' . LOGIN_URL);
exit;