Security-Starter is a ready to use package that provide to you a complete CRUD REST system for a User/Profile/Role architecture, and also provide a middleware to specify roles that the user must have to access your routes, and it's based on the **heloufir/simple-passport** package that offers to you a **forgot password** system, you can refer to this [link](https://github.com/heloufir/simple-passport) to know more about this package.

![Security starter architecture](https://lh3.googleusercontent.com/-ZPq7gXOK7gM/XHO22Ns2z4I/AAAAAAAAEsQ/6lu1zpoi_n81rEEqGlSG4btyNST6Up9wgCLcBGAs/s0/2019-02-25_102913.png "2019-02-25_102913.png")

# A full implementation

You can find a complete implementation of this repository in [Ngx Security Starter](https://github.com/heloufir/ngx-security-starter)

# Installation

	composer require heloufir/security-starter

# Configuration

**Method 1.** You can configure this package automatically, by using the command `php artisan starter:config` (if you want to configure it manually, go to **Method 2**)
When executing this command you will be asked to answer some questions, and at the very end you will need to complete 3 steps manually :

- Add Laravel\Passport\HasApiTokens trait to the User model

```php
<?php

namespace App;
    
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
    
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```

- Add Heloufir\SecurityStarter\Core\UserProfiles trait to the User model

```php
<?php

namespace App;
    
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Heloufir\SecurityStarter\Core\UserProfiles;
    
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, UserProfiles;
}
```

- Add '`roles' => \Heloufir\SecurityStarter\Http\Middleware\RoleMiddleware::class` to the $routeMiddleware in Kernel

**File: app/Http/Kernel.php**
```php
protected $routeMiddleware = [
    // ...
    'roles' => \Heloufir\SecurityStarter\Http\Middleware\RoleMiddleware::class
];
```

**Method 2.** (Manual configuration)

First, you need to publish the **heloufir/simple-passport**:

	php artisan vendor:publish --provider=Heloufir\SimplePassport\SimplePassportServiceProvider

After, you need to publish the **heloufir/security-starter**:

	php artisan vendor:publish --provider=Heloufir\SecurityStarter\SecurityStarterServiceProvider

Then, if you want to customize the **profiles**, **roles**, **profile_roles** and **user_profiles** tables you need first to update the file **config/security-starter.php**, then go to next step.

Launch migrations to add **laravel/passport** tables and **heloufir/security-starter** tables:

	php artisan migrate

Then, install **laravel/passport** oauth clients, by executing:

	php artisan passport:install

Add Laravel\Passport\HasApiTokens trait to the User model

```php
<?php

namespace App;
    
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
    
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```

Add Heloufir\SecurityStarter\Core\UserProfiles trait to the User model

```php
<?php

namespace App;
    
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Heloufir\SecurityStarter\Core\UserProfiles;
    
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, UserProfiles;
}
```

Add `'roles' => \Heloufir\SecurityStarter\Http\Middleware\RoleMiddleware::class` to the $routeMiddleware in Kernel

**File: app/Http/Kernel.php**
```php
protected $routeMiddleware = [
    // ...
    'roles' => \Heloufir\SecurityStarter\Http\Middleware\RoleMiddleware::class
];
```

> Don't forget to update the guards in your **auth.php** configuration file for the `api` to **passport**

```php
'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport', // <- Here
            'provider' => 'users',
        ],
    ],
```

That's all, the installation and configuration of **security-starter** is done.

You can check the [wiki](https://github.com/heloufir/security-starter/wiki) for more information about this package.
