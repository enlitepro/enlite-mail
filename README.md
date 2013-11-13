EnliteMail for ZendFramework2
=============================

The simple module send mail with templates for ZF2.

Install
=======

The recommended way to install is through composer.

```json
{
    "require": {
        "enlitepro/enlite-mail": "~1.2.0"
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
        'invokables' => array(
            'MailTransport' => 'Zend\Mail\Transport\Sendmail',
        ),
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
        $mailService = $this->getMailService();

        // send any mail
        $message = $mailService->factoryMessage(); // this is Zend\Mail\Message
        // configure message
        // ...
        // send
        $mailService->sendMessage($message);

        // create mail from template and variables
        // variable will be pass to template
        $template = $mailService->createTemplate('my-module\controller\view', ['foo' => 'bar']);
        $mailService->sendTemplate($template, 'qwerty@example.com');
        // or, if you want operate message object before send
        $message = $mailService->createMessageFromTemplate($template);
        $mailService->sendMessage($message);
    }
}
```

Templates
---------

Template system based on you renderer with all it benefits. There are only one new feature, headTitle plugin set message
subject, not only page title.

example of __layout/mail.twig__

```html
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    {% block title %}
        {% do headTitle('My Application').setSeparator(' - ') %}
    {% endblock %}
</head>
<body marginheight="0" topmargin="0" marginwidth="0" leftmargin="0" style="">
<div class="padded" style="padding:20px 20px 20px 20px">
    <table style="">
        <tr>
            <td style="">
{% block content %}{{ content|raw }}{% endblock content %}
            </td>
        </tr>
    </table>
</div>
</body>
</html>
```

example of __application/mail/invite.twig__

```html
{% extends "layout/mail" %}

{% block content %}
    {% do headTitle("You're invited to MyApplication") %}
    
    Some variable: {{some_variable}}
{% endblock %}
```
