<?php
//  session_start();
$session = new SecureSessionHandler('cheese');

ini_set('session.save_handler', 'files');
session_set_save_handler($session, true);
session_save_path(__DIR__ . '/sessions');
$session->start();

if ( ! $session->isValid(5)) {
    $session->destroy();
}

$session->put('hello.world', 'bonjour');

$session->get('hello.world'); // bonjour
