<?php
declare(strict_types=1);

AuthService::logout();
header('Location: /?r=home');
exit;
