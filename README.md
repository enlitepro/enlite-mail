EnliteMail for ZendFramework2
=============================

The simple module send mail with templates for ZF2.

Install
=======

The recommended way to install is through composer.

```json
{
    "require": {
        "enlitepro/enlite-mail": "1.*"
    }
}
```

Configure
=========

The module use in default:
- For transport Zend\Mail\Transport\Sendmail
- For renderer - default renderer

For use other writes in service locator, add to config:

```php
[
    'enlite_mail' => [
        'renderer' => 'YOU_RENDERER_FOR_MAIL',
        'transport' => 'YOU_TRANSPORT_FOR_MAIL',
    ]
]
```

For example:

```php
[
    'enlite_mail' => [
        'renderer' => 'MyLikeRenderer',
        'transport' => 'MailTransport',
    ]
]
```

For change transport you may set a new transport
in key "MailTransport" for service_manager. For example:

```php
array(
    'service_manager' => array(
        'invokables' => [
            'MailTransport' => 'Zend\Mail\Transport\Sendmail',
        ],
    )
);
```

Usage
=====

```php
use EnliteMail\Service;
use \Zend\Mail\Message;

class test {
    use MailServiceTrait;

    public function test() {
        // send any mail
        $message = new Message();
        // configure message
        // ...
        // send
        $this->getMailService()->sendMessage($message);

        // send with use template
        $template = $this->getMailService()->createTemplate('my-module\controller\view');
        $this->getMailService()->sendTemplate('qwerty@qwerty.com', $template);
    }
}
```
