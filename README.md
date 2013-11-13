EnliteMail for ZendFramework2
=============================

The simple module send mail with templates for ZF2.

Install
=======

The recommended way to install is through composer.

```json
{
    "require": {
        "enlitepro/enlite-mail": "~1.1.3"
    }
}
```

add `EnliteMail` to `modules` in `config/application.config.php`

Configure
=========

The module use in default:
- For transport Zend\Mail\Transport\Sendmail
- For renderer - default renderer

For use other writes in service locator, add to config:

```php
array(
    'enlite_mail' => array(
        'renderer' => 'YOUR_RENDERER_FOR_MAIL', // default ViewRenderer
        'transport' => 'YOUR_TRANSPORT_FOR_MAIL', // default MailTransport
        'from_mail' => 'YOUR_MAIL',
        'from_name' => 'YOUR_NAME',
    )
)
```

For example:

```php
array(
    'enlite_mail' => array(
        'renderer' => 'ZfcTwigRenderer',
        'transport' => 'MailTransport',
    )
)
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
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class MyService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait,
        MailServiceTrait;

    public function test()
    {
        // send any mail
        $message = $this->getMailService()->factoryMessage(); // this is Zend\Mail\Message
        // configure message
        // ...
        // send
        $this->getMailService()->sendMessage($message);

        // create mail from template
        $template = $this->getMailService()->createTemplate('my-module\controller\view', ['foo' => 'bar']);
        $this->getMailService()->sendTemplate('qwerty@qwerty.com', $template);
        // or
        $message = $this->getMailService()->createMessageFromTemplate($template);
        $this->getMailService()->sendMessage($message);

    }
}
```
