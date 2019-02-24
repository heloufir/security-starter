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

That's all, the installation and configuration of **security-starter** is done.

You can check the [wiki](https://github.com/heloufir/security-starter/wiki) for more information about this package.
