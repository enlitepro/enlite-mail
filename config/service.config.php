<?php

return array(
    'service_manager' => array(
        'invokables' => [
            'MailTransport' => 'Zend\Mail\Transport\Sendmail',
        ],
        'factories' => array(
            'EnliteMailService' => 'EnliteMail\Service\MailServiceFactory',
            'EnliteMailServiceOptions' => 'EnliteMail\Service\MailServiceOptionsFactory'
        )
    )
);