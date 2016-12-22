# commandbus

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This a CommandBus implementation for php5.3. It's based on illuminate/events. This though will require a few other packages: illuminate/support, illuminate/contracts, and illuminate/container.

A CommandBus allows you to leverage commands and domain events in your php projects.

Essentially, the value add is to replace lines and lines of procedural style code in a class or method, with classes that do one thing.
These classes are loosely coupled together through eventing initiated by the CommandBus and CommandHandlers.

The usage shown below is the best explanation.

I would note the contributors but this was forked from [Jeffrey at Laracasts](https://github.com/laracasts/Commander)
My sole contributions are making his Commander Package compatible for legacy web apps that have a bit of illuminate sprinkled in. 

## Install  

Via Composer

``` bash
$ composer require robrogers3/commandbus
```

## Usage

### Requirements:

You have an app which uses illimunate packages. Especially illimunate/events.

### Your Goal:

Have a way to accomplish some task which takes many subtasks.
Perhaps currently you have the subtasks inside one class or event one method. Some kind of God Object.

### The Solution:

Use a command bus to kick off (dispatch) the necessary classes to complete accomplish what was done in one place.

### Initial Setup:

You need to create a boot method or function that initialize the illuminate Container (IOC).
This method should:
* Register the container.
* Create an INSTANCE of the illimunate/event dispatcher.
* Register all the listeners for your events.
** The listeners can be fully written out, like 'Acme\UserHasRegistered'. Or better, 'Acme.*' The latter can catch all events prefixed by Acme

1. Register your listeners
``` php
    $listeners = array(
        'Acme\SendWelcomeEmail',
        'Acme\AddToLdap',
        'Acme\ConfigurePermissions'
    );
```

2. In your boot method, register them:
```php
    App::instance('Dispatcher', $dispatcher);

    $listeners = getAppListeners();

    foreach ($listeners as $listener) {
        $dispatcher->listen('CrowdStar.*', $listener);
    }
```

### Get to Work

1) Create your command. It's a simple class, a DTO of sorts. This is what gets thrown into the commandbus. Here is a simple one

``` php
namespace Acme;

class RegisterUserCommand
{
    public $username;

    public function __construct($username)
    {
        $this->username = $username;
    }
}
```

2) Next, lets create a listener, like:

```php
namespace Acme;

use RobRogers\CommandBus\Eventing\EventListener;

class SendWelcomeEmail extends EventListener
{

    public function whenUserWasRegistered(UserWasRegistered $event)
    {
        dump( 'Sending mail to ' . $event->user->username);
    }
}
```

3) Create your command handler. The commandbus calls the handle method on this class.

```php
namespace Acme;

use RobRogers\CommandBus\CommandHandler;

class UserRegisterCommandHandler implements CommandHandler
{
    /**
     * @var EventDispatcher
     */
    private $dispatcher;
    /**a
     * @var EventGenerator
     */
    private $eventGenerator;

    /**
     * @param EventDispatcher $dispatcher
     * @param EventGenerator $eventGenerator
     */
    public function __construct(EventDispatcher $dispatcher, EventGenerator $eventGenerator)
    {
        $this->dispatcher = $dispatcher;

        $this->eventGenerator = $eventGenerator;
    }

    public function handle(/* user registered command */ $command)
    {
        $event = new Acme\UserWasRegistered($command);
        $this->eventGenerator->register($event); //you can register many events
    }
}
```

4) Finally create the 'event' registered above. This also a DTO, which contains the $command.

```php
namespace Acme;

/**
* I am  THE EVENT
* the command data get's shoved inside me. like $this->user->name
*/
class UserHasRegistered
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
```

5) Use it:

```php
$command = new UserRegisterCommand("Rob Rogers");

/** @var \RobRogers\CommandBus\BaseCommandBus $commandBus */
$commandBus = App::Make('\RobRogers\CommandBus\BaseCommandBus');

$commandBus->execute($command);
```

6) See what happens:

If you have registered the three listeners above, they all get fired.
* send a welcome email to the user.
* add them to ldap (or wherever)
* configure their system permissions

Bottom line in your controller, or wherever.
You just need 3 lines:
* instantiate the command.
* make the command bus.
* call the execute method.

For the three tasks above, it's not a big deal to call them directly. But if you have many many things to do, then it cleans things up rather nicely.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email robrogers@me.com instead of using the issue tracker.

## Credits

- [Rob Rogers][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/robrogers3/agnostic-commandbus.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/robrogers3/agnostic-commandbus/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/robrogers3/agnostic-commandbus.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/robrogers3/agnostic-commandbus.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/robrogers3/agnostic-commandbus.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/robrogers3/commandbus
[link-travis]: https://travis-ci.org/robrogers3/commandbus
[link-scrutinizer]: https://scrutinizer-ci.com/g/robrogers3/commandbus/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/robrogers3/commandbus
[link-downloads]: https://packagist.org/packages/robrogers3/commandbus
[link-author]: https://github.com/robrogers3
[link-contributors]: ../../contributors
