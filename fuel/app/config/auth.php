<?php

return array(
    'driver' => 'Simpleauth',
    'verify_multiple_logins' => false,
    'salt' => '2e1bea72d464f3405022d917c104dd6f0333f37c',
    'iterations' => 10000,
    'remember_me' => [
        'cookie_name' => 'cas-remember-new',
        'expiration'  => 31 * 24 * 3600
    ]
);
