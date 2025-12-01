<?php
require_once 'utils/session.php';

SessionManager::start();
SessionManager::destroySession();

header("Location: login.php");
exit;

