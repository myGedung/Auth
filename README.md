# **INTRODUCTION**
----------

A modern and framework agnostic authorization and authentication package featuring roles, permissions, custom hashing algorithms and additional security features.

The package follows the FIG standard PSR-4 to ensure a high level of interoperability between shared PHP code.

The package requires PHP 7.0+ and comes bundled with a Laravel 5 Facade and a Service Provider to simplify the optional framework integration.

Have a read through the Installation Guide and on how to Integrate it with Laravel 5.

[![Build Status](https://travis-ci.org/myGedung/Auth.svg?branch=master)](https://travis-ci.org/mygedung/auth)

### **Create a user**

``` bash
Auth::register(array(
    'email'    => 'john.doe@example.com',
    'password' => 'foobar',
));
```

### **Authenticate a user**

``` bash
Auth::authenticate(array(
    'email'    => 'john.doe@example.com',
    'password' => 'foobar',
));
```

## **Features**

Auth is a complete refactor of our popular authentication & authorization library. Everything you admired plus a whole lot more.

 - Authentication. 
 - Authorization. 
 - Registration. 
 - Users & Roles Management. 
 - Flexible activation scenarios. 
 - Reminders (password reset). 
 - Inter-account throttling with DDoS protection. 
 - Custom hashing strategies. 
 - Multiple sessions.
 - Multiple login columns. 
 - Integration with Laravel. 
 - Allow use of multiple ORM implementations. 
 - Native facade for easy usage outside Laravel. 
 - Interface driven (your own implementations at will).

# **INSTALLATION**
----------

The best and easiest way to install Auth is with **Composer**.

If you have **installed Composer globally** run the following:

``` bash
composer require mygedung/auth "1.0.*"
```

Otherwise you'll have to manually download the composer.phar file:

``` bash
curl -sS https://getcomposer.org/installer | php 
php composer.phar require mygedung/auth "1.0.*"
```

Now you are able to require the vendor/autoload.php file to autoload the package.

# **INTEGRATION**
----------

myGedung packages are framework agnostic and as such can be integrated easily natively or with your favorite framework.

## **Laravel 5**

The Auth package has optional support for Laravel 5 and it comes bundled with a Service Provider and a Facade for easy integration.

After installing the package, open your Laravel config file located at config/app.php and add the following lines.

In the *$providers* array add the following service provider for this package.

> myGedung\Auth\Laravel\AuthServiceProvider::class,

In the *$aliases* array add the following facades for this package.

> 'Activation' => myGedung\Auth\Laravel\Facades\Activation::class,
>
> 'Reminder'   => myGedung\Auth\Laravel\Facades\Reminder::class, 
>
> 'Auth'  => myGedung\Auth\Laravel\Facades\Auth::class,

### **Assets**

Run the following command to publish the migrations and config file.

``` bash
php artisan vendor:publish --provider="myGedung\Auth\Laravel\AuthServiceProvider"
```

### **Migrations**

Run the following command to migrate Auth after publishing the assets.

> Note: Before running the following command, please remove the default
> Laravel migrations to avoid table collision. 

``` bash
php artisan migrate
```

### **Configuration**

After publishing, the auth config file can be found under config/mygedung.auth.php where you can modify the package configuration.

## **Native**

Auth ships with default implementations for illuminate/database, in order to use it, make sure you require it on your composer.json file.

``` bash
// Import the necessary classes
use myGedung\Auth\Native\Facades\Auth;
use Illuminate\Database\Capsule\Manager as Capsule;

// Include the composer autoload file
require 'vendor/autoload.php';

// Setup a new Eloquent Capsule instance
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'auth',
    'username'  => 'user',
    'password'  => 'secret',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
]);

$capsule->bootEloquent();
```

The integration is done and you can now use all the available methods, here's an example:

``` bash
// Register a new user
Auth::register([
    'email'    => 'test@example.com',
    'password' => 'foobar',
]);
```

# **USAGE**
----------

Auth provides you all the tools you need to manage a role based authentication and authorization system.

## **Authentication**

In this section, we will cover the Auth authentication methods.

### **Auth::authenticate()**

This method authenticates a user against the given $credentials, additionally a second bool argument of true can be passed to set the remember state on the user.

Returns: *myGedung\Auth\Users\UserInterface or false.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.
$remember |	false |	bool |	false |	Flag to set the remember cookie.

#### Example

``` bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];

Auth::authenticate($credentials);
```

#### Example Response

``` bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::authenticateAndRemember()**

This method authenticates and remembers the user, it's an alias fore the authenticate() method but it sets the $remember flag to true.

Returns: *myGedung\Auth\Users\UserInterface or false.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.

#### Example

``` bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];

Auth::authenticateAndRemember($credentials);
```

#### Example Response

``` bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::forceAuthenticate()**

Authenticates a user bypassing all checkpoints.

Returns: *myGedung\Auth\Users\UserInterface or false.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.
$remember |	false |	bool |	false |	Flag to set the remember cookie.

#### Example

``` bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];

Auth::forceAuthenticate($credentials);
```

#### Example Response

``` bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::forceAuthenticateAndRemember()**

Authenticates and remembers a user bypassing all checkpoints.

Returns: *myGedung\Auth\Users\UserInterface or false.*

#### Arguments

Key |	Required |	Type |	Default |	Description 
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.

#### Example

``` bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];
```

### **Auth::forceAuthenticateAndRemember($credentials);**

#### Example Response

``` bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::stateless()**

Performs stateless authentication.

Returns: *myGedung\Auth\Users\UserInterface or false.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.

#### Example

``` bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];

if ($user = Auth::stateless($credentials))
{
    // Authentication successful and the user is assigned to the `$user` variable.
}
else
{
    // Authentication failed.
}
```

#### Example Response

``` bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::basic()**

Authenticates using the HTTP basic auth.

Returns the auth response.

#### Example

``` bash
return Auth::basic();
```

## **Authorization**

In this section, we will cover authorization methods.

### **Auth::check()**

Check if a user is logged in.

Returns: *myGedung\Auth\Users\UserInterface or false.*

#### Example

```bash
if ($user = Auth::check())
{
    // User is logged in and assigned to the `$user` variable.
}
else
{
    // User is not logged in
}
```

#### Example Response

``` bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::forceCheck()**

Check if a user is logged in, bypassing all checkpoints.

Returns: *myGedung\Auth\Users\UserInterface or false.*

#### Example

```bash
if ($user = Auth::forceCheck())
{
    // User is logged in and assigned to the `$user` variable.
}
else
{
    // User is not logged in
}
```

#### Example Response

```bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::guest()**

Check if no user is currently logged in.

Returns true if the user is not logged in and false otherwise.

#### Example

```bash
if (Auth::guest())
{
    // User is not logged in
}
```

#### Example Response

```bash
true
```

### **Auth::getUser()**

Retrieves the currently logged in user.

Returns: *myGedung\Auth\Users\UserInterface or null.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$check |	false |	bool |	true |	A flag to instruct auth whether it should perform a check for a logged in user if it hasn't been checked yet on the given request.

#### Example

```bash
if ($user = Auth::getUser())
{
    // User is logged in and assigned to the `$user` variable.
}
```

## **Registration**

In this section, we will cover registration methods.

### **Auth::register()**

With this method you'll be able to register new users onto your application.

The first argument is a key/value pair which should contain the user login column name, the password and other attributes you see fit.

The second argument is a boolean, that when set to true will automatically activate the user account.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.
$callback |	false |	bool ; Closure |	null |	This argument is used for two things, either pass in true to activate the user or a Closure that would be executed before the user is created and can prevent user creation if it returns false.

#### Example

```bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];

$user = Auth::register($credentials);
```

#### Example Response

```bash
{
    email: "john.doe@example.com",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:01"
    id: 2
}
```

### **Auth::registerAndActivate()**

This method registers and activates the user, it's an alias for the register() method but it sets the $callback flag to true.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.

#### Example

```bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];

$user = Auth::registerAndActivate($credentials);
```

#### Example Response

```bash
{
    email: "john.doe@example.com",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:01"
    id: 2
}
```

## **Login**

In this section, we will cover login methods.

### **Auth::login()**

This method logs the given a user in, additionally a second bool argument of true can be passed to set the remember state on the user.

Returns: *myGedung\Auth\Users\UserInterface or false.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$user |	true |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.
$remember |	false |	bool |	false |	Flag to set the remember cookie.

#### Example

```bash
$user = Auth::findById(1);

Auth::login($user);
```

#### Example Response

```bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::loginAndRemember()**

This method logs and remembers the given user, it's an alias fore the login() method but it sets the $remember flag to true.

Returns: *myGedung\Auth\Users\UserInterface or false.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$user |	true |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.

#### Example

```bash
$user = Auth::findById(1);

Auth::loginAndRemember($user);
```

#### Example Response

```bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::logout()**

Logs a user out, optionally can be passed a bool parameter true that will flush all active sessions for the user.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$user |	false |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.
$everywhere |	false |	bool |	false |	Flag for whether it should terminate all sessions.

#### Examples

Please refer to the examples below for different ways on terminate your users sessions.

Destroy the current logged in user session

```bash
Auth::logout();
```

Destroy all sessions for the current logged in user

```bash
Auth::logout(null, true);
```

Destroy the given user session

```bash
$user = Auth::findUserById(1);

Auth::logout($user);
```

Destroy all sessions for the given user

```bash
$user = Auth::findUserById(1);

Auth::logout($user, true);
```

#### Example Response

```bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

## **Users**

The user repository can be accessed using Auth::getUserRepository() and allows you to manage users using Auth.

> **Note 1** You can use the methods below directly on the Auth facade without the getUserRepository part. Example Auth::findById(1) instead of Auth::getUserRepository()->findById(1). 
>
> **Note 2** You can add the word User between find and the method name and drop the getUserRepository call. Example Auth::findUserByCredentials($credentials) instead of Auth::getUserRepository()->findByCredentials($credentials). 

### **Auth::findById()** 

Finds a user using it's id.

Returns: *myGedung\Auth\Users\UserInterface or null.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$id |	true |	int |	null |	The user unique identifier.

#### Example

```bash
$user = Auth::findById(1);
```

#### Example Response

```bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::findByCredentials()**

Finds a user by it's credentials.

Returns: *myGedung\Auth\Users\UserInterface or null.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.

#### Example

```bash
$credentials = [
    'login' => 'john.doe@example.com',
];

$user = Auth::findByCredentials($credentials);
```

#### Example Response

```bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::findByPersistenceCode()**

Finds a user by persistence code.

Returns: *myGedung\Auth\Users\UserInterface or null.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$code |	true |	string |	null |	The persistence code.

#### Example

```bash
$user = Auth::findByPersistenceCode('persistence_code_here');
```

#### Example Response

```bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::validateCredentials()**

Validates the user credentials.

This is useful when you want to verify if the current user password matches the given password.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.

#### Example

```bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];

$user = Auth::findUserById(1);

$user = Auth::validateCredentials($user, $credentials);
```

#### Example Response

```bash
true
```

### **Auth::validForCreation()**

Validates a user for creation.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.

#### Example

```bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];

$user = Auth::validForCreation($credentials);
```

#### Example Response

```bash
true
```

### **Auth::validForUpdate()**

Validates a user for update.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$user |	true |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.
$credentials |	true |	array |	null |	The user credentials.

#### Example

```bash
$user = Auth::findById(1);

$credentials = [
    'email' => 'johnathan.doe@example.com',
];

$user = Auth::validForUpdate($user, $credentials);
```

#### Example Response

```bash
true
```

### **Auth::create()**

Creates a new user.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$credentials |	true |	array |	null |	The user credentials.
$callback |	false |	Closure |	null |	A Closure that would be executed before the user is created and can prevent user creation if it returns false.

#### Example

```bash
$credentials = [
    'email'    => 'john.doe@example.com',
    'password' => 'password',
];

$user = Auth::create($credentials);
```

#### Example Response

```bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::update()**

Updates an existing user.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$user |	true |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.
$credentials |	true |	array |	null |	The user credentials.

#### Example

```bash
$user = Auth::findById(1);

$credentials = [
    'email' => 'new.john.doe@example.com',
];

$user = Auth::update($user, $credentials);
```

#### Example Response

```bash
{
    id: "1",
    email: "john.doe@example.com",
    permissions: {
        admin: true
    },
    last_login: {
        date: "2017-02-17 03:44:31",
        timezone_type: 3,
        timezone: "UTC"
    },
    first_name: "John",
    last_name: "Doe",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **$user->delete()**

A user object can be deleted by calling eloquent's delete method on the user object. All related records for that specific user will be deleted as well.

#### Example

```bash
$user = Auth::findById(1);

$user->delete();
```

### **Auth::getHasher()**

Returns the current hasher.

#### Example

```bash
$hasher = Auth::getHasher();
```

### **Auth::setHasher()**

Sets the hasher.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$hasher |	true |	myGedung\Auth\Hashing\HasherInterface |	null |	The hasher object.

#### Example

```bash
Auth::setHasher(new myGedung\Auth\Hashing\WhirlpoolHasher);
```

### **Auth::inRole($role)**

Check if the current user belongs to the given role.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$role |	true |	string |	null |	The role to check against.

#### Example

```bash
$admin = Auth::inRole('admin');
```

### **Auth::createModel()**

Creates a new user model instance.

```bash
$user = Auth::createModel();
```

### **Auth::setModel()**

Sets the user model.

Your new model needs to extend the myGedung\Auth\Users\EloquentUser class.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$model |	true |	string |	null |	The users model class name.

#### Example

```bash
Auth::setModel('Acme\Models\User');
```

## **Roles**

The role repository can be accessed using Auth::getRoleRepository() and allows you to manage roles using Auth.

> Note You can add the word Role between find and the method name and drop the getRoleRepository call. Example Auth::findRoleBySlug($slug) instead of Auth::getRoleRepository()->findBySlug($slug).

### **Auth::findRoleById()**

Finds a role by its ID.

Returns: *myGedung\Auth\Roles\RoleInterface or null.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$id |	true |	int |	null |	The role unique identifier.

#### Example

```bash
$role = Auth::findRoleById(1);
```

#### Example Response

```bash
{
    id: "1",
    slug: "admin",
    name: "Admin",
    permissions: {
        admin: true
    },
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::findRoleBySlug()**

Finds a role by its slug.

Returns: *myGedung\Auth\Roles\RoleInterface or null.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$slug |	true |	string |	null |	The role slug.

#### Example

```bash
$role = Auth::findRoleBySlug('admin');
```

#### Example Response

```bash
{
    id: "1",
    slug: "admin",
    name: "Admin",
    permissions: {
        admin: true
    },
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::findRoleByName()**

Finds a role by its name.

Returns: *myGedung\Auth\Roles\RoleInterface or null.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$name |	true |	string |	null |	The role name.

#### Example

```bash
$role = Auth::findRoleByName('Admin');
```

#### Example Response

```bash
{
    id: "1",
    slug: "admin",
    name: "Admin",
    permissions: {
        admin: true
    },
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Auth::getRoleRepository()->createModel()**

Creates a new role model instance.

```bash
$role = Auth::getRoleRepository()->createModel();
```

###* *Auth::getRoleRepository()->setModel()**

Sets the role model.

Your new model needs to extend the myGedung\Auth\Roles\EloquentRole class.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$model |	true |	string |	null |	The roles model class name.

#### Example

```bash
Auth::getRoleRepository()->setModel('Acme\Models\Role');
```

### **Create a new role.**

Create a new role.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |	--------
$attributes |	true |	array |	null |	The role attributes.

#### Example

```bash
$role = Auth::getRoleRepository()->createModel()->create([
    'name' => 'Subscribers',
    'slug' => 'subscribers',
]);
```

#### Example Response

```bash
{
    name: "Subscribers",
    slug: "subscribers",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37",
    id: 2
}
```

### **Assign a user to a role.**

```bash
$user = Auth::findById(1);

$role = Auth::findRoleByName('Subscribers');

$role->users()->attach($user);
```

### **Remove a user from a role.**

```bash
$user = Auth::findById(1);

$role = Auth::findRoleByName('Subscribers');

$role->users()->detach($user);
```

## **Permissions**

Permissions can be broken down into two types and two implementations. Depending on the used implementation, these permission types will behave differently.

* Role Permissions
* User Permissions

Standard - This implementation will give the user-based permissions a higher priority and will override role-based permissions. Any permissions granted/rejected on the user will always take precendece over any role-based permissions assigned.

Strict - This implementation will reject a permission as soon as one rejected permission is found on either the user or any of the assigned roles. Granting a user a permission that is rejected on a role he is assigned to will not grant that user this permission.

Role-based permissions that define the same permission with different access rights will be rejected in case of any rejections on any role.

If a user is not assigned a permission, the user will inherit permissions from the role. If a user is assigned a permission of false or true, then the user's permission will override the role permission.

> Note The permission type is set to StandardPermissions by default; it can be changed on the config file.

#### **Administrator Role**

```bash
{
    "name" : "Administrator",
    "permissions" : {
        "user.create" : true,
        "user.delete" : true,
        "user.view"   : true,
        "user.update" : true
    }
}
```

#### **Moderator Role**

```bash
{
    "name" : "Moderator",
    "permissions" : {
        "user.create" : false,
        "user.delete" : false,
        "user.view"   : true,
        "user.update" : true
    }
}
```

And you have these three users, one as an Administrator, one as a Moderator and the last one has both the Administrator and Moderator roles assigned.

#### **User - John Doe**

```bash
{
    "id" : 1,
    "first_name" : "John",
    "last_name" : "Doe",
    "roles" : ["administrator"],
    "permissions" : null
}
```
This user has access to everything and can execute every action on your application.

#### **User - Jane Smith**

```bash
{
    "id" : 2,
    "first_name" : "Jane",
    "last_name" : "Smith",
    "roles" : ["moderator"],
    "permissions" : {
        "user.update" : false
    }
}
```

* Can view users.
* Cannot create, update or delete users.

> Note: The use of user.update : false demonstrates Permission Inheritance, which applies only when using Standard Mode (inheritance is disabled, by design, when using Strict Mode). When a permission is defined at the user-level, it overrides the same permission that is defined on the role. Given the above example, the user will be denied the user.update permission, even though the permission is allowed on the role.

#### **User - Bruce Wayne**

```bash
{
    "id" : 3,
    "first_name" : "Bruce",
    "last_name" : "Wayne",
    "roles" : ["administrator", "moderator"],
    "permissions" : {
        "user.create" : true
    }
}
```

* Can create, update and view users.
* Cannot execute delete users.

This is a special user, mainly because this user has two roles assigned. There are some things that you should know when assigning multiple roles to a user.

When a user has two or more roles assigned, if those roles define the same permissions but they have different values (e.g., one role grants the creation of users and the other role denies it), once any of those role permissions are denied, the user will be denied access to that permission, no matter what the other roles have as a permission value and no matter which permission type (standard or strict) is being used.

This means that for you to allow a permission for this specific user, you have to be using standard permissions and you have to change the user permission to grant access.

### **Usage**

Permissions live on permissible models, users and roles.

You can add, modify, update or delete permissions directly on the objects.

#### **Storing Permissions**

Permissions can either be stored as associative arrays on the Eloquent user or role by assigning it to the permissions attribute or using designated permission methods which make the process easier.

**Array**

Grant the user user.create and reject user.delete.

```bash
$user = Auth::findById(1);

$user->permissions = [
    'user.create' => true,
    'user.delete' => false,
];

$user->save();
```

Grant the role user.update and user.view permissions.

```bash
$role = Auth::findRoleById(1);

$role->permissions = [
    'user.update' => true,
    'user.view' => true,
];

$role->save();
```

#### **Designated methods**

> Note addPermission and updatePermission will default to true, calling addPermission('x') will grant the user or role that permission, passing false as a second parameter will deny that permission.

Grant the user user.create and reject user.update.

```bash
$user = Auth::findById(1);

$user->addPermission('user.create');
$user->addPermission('user.update', false);

$user->save();
```

Remove user.delete from the user.

> Note Removing a permission does not explicitly mean rejection, it will fallback to permission inheritance.

```bash
$user = Auth::findById(1);

$user->removePermission('user.delete')->save();
```

Update existing user.create and reject user.update

```bash
$role = Auth::findRoleById(1);

$role->updatePermission('user.create');
$role->updatePermission('user.update', false, true)->save();
```

> Note 1: addPermission, updatePermission and removePermission are chainable. Note 2: On updatePermission, passing true as a third argument will create the permission if it does not already exist.

#### **Checking for Permissions**

Permissions checks can be conducted using one of two methods.

Both methods can receive an argument of either a single permission passed as a string or an array of permissions.

#### **hasAccess**

This method will strictly require all passed permissions to be true in order to grant access.

This test will require both user.create and user.update to be true in order for permissions to be granted.

```bash
$user = Auth::findById(1);

if ($user->hasAccess(['user.create', 'user.update']))
{
    // Execute this code if the user has permission
}
else
{
    // Execute this code if the permission check failed
}
```

#### **hasAnyAccess**

This method will grant access if any permission passes the check.

This test will require only one permission of user.admin and user.create to be true in order for permissions to be granted.

```bash
if (Auth::hasAnyAccess(['user.admin', 'user.update']))
{
    // Execute this code if the user has permission
}
else
{
    // Execute this code if the permission check failed
}
```

> Note You can use Auth::hasAccess() or Auth::hasAnyAccess() directly which will call the methods on the currently logged in user, incase there's no user logged in, a BadMethodCallException will be thrown.

#### **Wildcard Checks**

Permissions can be checked based on wildcards using the * character to match any of a set of permissions.

```bash
$user = Auth::findById(1);

if ($user->hasAccess('user.*'))
{
    // Execute this code if the user has permission
}
else
{
    // Execute this code if the permission check failed
}
```

### **Controller Based Permissions**

You can easily implement permission checks based on controller methods, consider the following example implemented as a Laravel filter.

Permissions can be stored as action names on users and roles, then simply perform checks on the action before executing it and redirect on failure with an error message.

```bash
Route::filter('permissions', function($route, $request)
{
    $action = $route->getActionName();

    if (Auth::hasAccess($action))
    {
        return;
    }

    return Redirect::to('/')->withErrors('Permission denied.');
});
```

## **Activation**

Activation allows you to manage activations through Auth.

### **Activation::create()**

Creates a new activation record for the user.

Returns: *myGedung\Auth\Activations\EloquentActivation.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |  --------
$user |	true |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.

#### Example

```bash
$user = Auth::findById(1);

$activation = Activation::create($user);
```

#### Example Response

```bash
{
    code: "HNjOSGWoVHCNx70UAnbphnAJVIttFvot",
    user_id: "1",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37",
    id: 1
}
```

### **Activation::exists()**

Check if an activation record exists for the user.

Returns: *myGedung\Auth\Activations\EloquentActivation or false.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |  --------
$user |	true |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.

#### Example

```bash
$user = Auth::findById(1);

$activation = Activation::exists($user);
```
#### Example Response

```bash
{
    id: "1",
    user_id: "1",
    code: "HNjOSGWoVHCNx70UAnbphnAJVIttFvot",
    completed: false,
    completed_at: null,
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Activation::complete()**

Attempt to complete activation for the user using the code passed.

Returns: *bool*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |  --------
$user |	true |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.
$code |	true |	string |	null |	The activation code.

#### Example

```bash
$user = Auth::findById(1);

if (Activation::complete($user, 'activation_code_here'))
{
    // Activation was successfull
}
else
{
    // Activation not found or not completed.
}
```

#### Example Response

```bash
true
```

### **Activation::completed()**

Check if activation has been completed for the user.

Returns: *myGedung\Auth\Activations\EloquentActivation or false.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |  --------
$user |	true |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.

#### Example

```bash
$user = Auth::findById(1);

if ($activation = Activation::completed($user))
{
    // User has completed the activation process
}
else
{
    // Activation not found or not completed
}
```

#### Example Response

```bash
{
    id: "1",
    user_id: "1",
    code: "HiaVCzyLb6XFeZcVFpfUlCoLGZfhddHs",
    completed: true,
    completed_at: "2017-02-17 02:44:13",
    created_at: "2017-02-17 02:43:01",
    updated_at: "2017-02-17 02:43:37"
}
```

### **Activation::remove()**

Remove the activation for the user.

Returns: *true or null.*

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |  --------
$user |	true |	myGedung\Auth\Users\UserInterface |	null |	The Auth user object.

#### Example

```bash
$user = Auth::findById(1);

Activation::remove($user);
```

#### Example Response

```bash
true
```

### **Activation::removeExpired()**

Removes all the expired activations.

```bash
Activation::removeExpired();
```

### **Activation::createModel()**

Creates a new activation model instance.

```bash
$activation = Activation::createModel();
```

### **Activation::setModel()**

Sets the activation model.

#### Arguments

Key |	Required |	Type |	Default |	Description
-------- |	-------- |	-------- |	-------- |  --------
$model |	true |	string |	null |	The new activation model.

#### Example

```bash
Activation::setModel('Your\Activation\Model');
```

## **Reminder**

Reminder allows you to manage reminders through Auth.

### **Reminder::create($user)**

Creates a new reminder record for the user.

Returns the reminder object.

```bash
$user = Auth::findById(1);

Reminder::create($user);
```

### **Reminder::exists($user)**

Check if a reminder record exists for the user.

Returns the reminder object or bool.

```bash
$user = Auth::findById(1);

Reminder::exists($user);
```

### **Reminder::complete($user, $code, $password)**

Attempt to complete the password reset for the user using the code passed and the new password.

Returns bool.

```bash
$user = Auth::findById(1);

if ($reminder = Reminder::complete($user, 'reminder_code_here', 'new_password_here'))
{
    // Reminder was successfull
}
else
{
    // Reminder not found or not completed.
}
```

### **Reminder::removeExpired()**

Remove all expired reminders.

```bash
Reminder::removeExpired();
```

### **Reminder::createModel()**

Creates a new reminder model instance.

```bash
$reminder = Reminder::createModel();
```

### **Reminder::setModel($model)**

Sets the reminder model.

```bash
Reminder::setModel('Your\Reminder\Model');
```

## **Throttle**

There are three types of throttling.

* global throttling will monitor the overall failed login attempts across your site and can limit the affects of an attempted DDoS attack.
* ip throttling allows you to throttle the failed login attempts (across any account) of a given IP address.
* user throttling allows you to throttle the login attempts on an individual user account.

Each type of throttling has the same options. The first is the interval, this is the time (in seconds) for which we check for failed logins. Any logins outside this time are no longer assessed when throttling.

The second option is thresholds, this may be approached using one of two ways.

* The first way, is by providing a key/value array, the key is the number of failed login attempts, and the value is the delay in seconds before the next attempt can occur.
* The second way is by providing an integer, if the number of failed login attempts outweigh the thresholds integer, that throttle is locked until there are no more failed login attempts within the specified interval.

On this premise, we encourage you to use array thresholds for global throttling (and perhaps IP throttling as well), so as to not lock your whole site out for minutes on end because it's being DDoS'd. However, for user throttling, locking a single account out because somebody is attempting to breach it could be an appropriate response.

You may use any type of throttling for any scenario, and the specific configurations are designed to be customized as your site grows.

### **Exceptions**

* myGedung\Auth\Checkpoints\ThrottlingException

Methods |	Parameters |	Description
-------- |	-------- |	--------
setDelay |	myGedung\Auth\Users\UserInterface $user |	Sets a user object on the exception.
getDelay |	.. |	Retrieves the user object that caused the exception.
setType |	string | $type	Sets a user object on the exception.
getType |	.. |	Retrieves the user object that caused the exception.
getFree |	.. |	Retrieves time the throttle is lifted.

## **Checkpoints**

Checkpoints can be referred to as security gates, the authentication process has to successfully pass through every single gate defined in order to be granted access.

By default, when logging in, checks for existing sessions and failed logins occur, you may configure an indefinite number of "checkpoints".

These are classes which may respond to each event and handle accordingly. We ship with two, an activation checkpoint and a throttle checkpoint.

> Note Checkpoints must implement myGedung\Auth\Checkpoints\CheckpointInterface.
Feel free to add, remove or re-order these.

### **Activation**

The activation checkpoint is responsible for validating the login attempt against the activation checkpoint to make sure the user is activated prior to granting access to a specific area.

### **Throttle**

The throttle checkpoint is responsible for validating the login attempts against the defined throttling rules.

### **Usage**

#### **Functions**

##### **Auth::addCheckpoint($key, $checkpoint)**

Add a new checkpoint.

```bash
$checkpoint = new Your\Custom\Checkpoint;

Auth::addCheckpoint('your_checkpoint', $checkpoint);
```

##### **Auth::removeCheckpoint($key);**

```bash
Auth::removeCheckpoint('activation');
```

##### **Auth::enableCheckpoints()**

Enable checkpoints.

```bash
Auth::enableCheckpoints();
```

##### **Auth::disableCheckpoints()**

Disable checkpoints.

```bash
Auth::disableCheckpoints();
```

##### **Auth::checkpointsStatus()**

Check whether checkpoints are enabled or disabled.

```bash
$checkpoints = Auth::checkpointsStatus();
```

##### **Auth::bypassCheckpoints($callback, $checkpoints)**

Execute a closure that bypasses all checkpoints.

Bypass all checkpoints.

```bash
$callback = function($auth)
{
    return $auth->check();
};

return Auth::bypassCheckpoints($callback);
```

Bypass specific checkpoints.

```bash
$callback = function($auth)
{
    return $auth->check();
};

return Auth::bypassCheckpoints($callback, ['activation']);
```

## **Hashing**

By default, Auth encourages the sole use of the native PHP 5.5 hashing standard, password_hash(). Auth requires no configuration to use this method.

While it is not encouraged for security reasons, we provide functionality to override the hashing strategy used by Auth so as to accomodate for legacy applications moving forward.

There are 5 built in hashers:

* Native hasher
* Bcrypt hasher
* Callback hasher
* Whirlpool hasher
* SHA256 hasher

### **Native Hasher**

The encouraged hasher to use in Auth is the native hasher. It will use PHP 7.0's password_hash() function and is setup to use the most secure hashing strategy of the day (which is current bcrypt). There is no setup required for this hasher.

The native hasher can be used with PHP 7.0 by adding the ircmaxell/password-compat package to your composer.json file.

### **Bcrypt Hasher**

The Bcrypt hasher uses the Bcrypt hashing algorithm. It is a safe algorithm to use, however this hasher has been deprecated in favor of the native hasher as it provides a uniform API to whatever the chosen hashing strategy of the day is.

To use the Bcrypt hasher:

```bash
// Native PHP
$auth->setHasher(new myGedung\Auth\Hashing\BcryptHasher);

// In Laravel
Auth::setHasher(new myGedung\Auth\Hashing\BcryptHasher);
```

### **Callback Hasher**

The callback hasher is a strategy which allows you to define the methods used to hash a value and in-turn check the hashed value. This is particularly useful when upgrading from legacy systems, which may use one or more hashing strategies. It will allow you to write logic that accounts for old strategies and new strategies, as seen in the example below.

Be extremely careful that you don't expose vulnerabilities in your system by designing a hashing strategy that is unsafe to use.

To use the callback hasher:

```bash
$hasher = function($value)
{
    return password_hash($value, PASSWORD_DEFAULT);
};

$checker = function($value, $hashedValue)
{
    // Try use the safe password_hash() function first, as all newly hashed passwords will use this
    if (password_verify($value, $hashedValue))
    {
        return true;
    }

    // Because we're upgrading from a legacy system, we'll check if the hash is an old one and therefore allow us to log the person in anyway
    return some_method_to_check_a_hash($value, $hashedValue);
}

// Native PHP
$auth->setHasher(new myGedung\Auth\Hashing\CallbackHasher($hasher, $checker));

// In Laravel
Auth::setHasher(new myGedung\Auth\Hashing\CallbackHasher($hasher, $checker));
```

### **Other Hashers**

Other hashers, such as the whirlpool hasher and the SHA256 hasher are supported by Auth, however we do not encourage their use as these algorithms are open to vulnerabilities. We would encourage people to use the callback hasher and implement their own logic for moving away from such systems.

We understand that not every system needs to move away from these strategies however. Telling Auth to use these strategies is straight forward:

```bash
// Native PHP
$auth->setHasher(new myGedung\Auth\Hashing\WhirlpoolHasher);
$auth->setHasher(new myGedung\Auth\Hashing\Sha256Hasher);

// In Laravel
Auth::setHasher(new myGedung\Auth\Hashing\WhirlpoolHasher);
Auth::setHasher(new myGedung\Auth\Hashing\Sha256Hasher);
```
