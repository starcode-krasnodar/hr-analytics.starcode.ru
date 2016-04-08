<?php

return [
    'adminEmail' => getenv('ADMIN_EMAIL'),

    'hh.clientId' => getenv('HH_CLIENT_ID'),
    'hh.clientSecret' => getenv('HH_CLIENT_SECRET'),
    'hh.returnUrl' => getenv('HH_CLIENT_RETURN_URL'),
    'hh.userAgent' => getenv('HH_USER_AGENT'),
];