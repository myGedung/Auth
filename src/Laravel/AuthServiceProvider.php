<?php

/**
 * Part of the Auth package.
 *
*/

namespace myGedung\Auth\Laravel;

use myGedung\Auth\Activations\IlluminateActivationRepository;
use myGedung\Auth\Checkpoints\ActivationCheckpoint;
use myGedung\Auth\Checkpoints\ThrottleCheckpoint;
use myGedung\Auth\Cookies\IlluminateCookie;
use myGedung\Auth\Hashing\NativeHasher;
use myGedung\Auth\Persistences\IlluminatePersistenceRepository;
use myGedung\Auth\Reminders\IlluminateReminderRepository;
use myGedung\Auth\Roles\IlluminateRoleRepository;
use myGedung\Auth\Auth;
use myGedung\Auth\Sessions\IlluminateSession;
use myGedung\Auth\Throttling\IlluminateThrottleRepository;
use myGedung\Auth\Users\IlluminateUserRepository;
use Exception;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->garbageCollect();
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->prepareResources();
        $this->setOverrides();
        $this->registerPersistences();
        $this->registerUsers();
        $this->registerRoles();
        $this->registerCheckpoints();
        $this->registerReminders();
        $this->registerAuth();
        $this->setUserResolver();
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        // Publish config
        $config = realpath(__DIR__.'/../config/config.php');

        $this->mergeConfigFrom($config, 'mygedung.auth');

        $this->publishes([
            $config => config_path('mygedung.auth.php'),
        ], 'config');

        // Publish migrations
        $migrations = realpath(__DIR__.'/../migrations');

        $this->publishes([
            $migrations => $this->app->databasePath().'/migrations',
        ], 'migrations');
    }

    /**
     * Registers the persistences.
     *
     * @return void
     */
    protected function registerPersistences()
    {
        $this->registerSession();
        $this->registerCookie();

        $this->app->singleton('auth.persistence', function ($app) {
            $config = $app['config']->get('mygedung.auth.persistences');

            return new IlluminatePersistenceRepository(
                $app['auth.session'], $app['auth.cookie'], $config['model'], $config['single']
            );
        });
    }

    /**
     * Registers the session.
     *
     * @return void
     */
    protected function registerSession()
    {
        $this->app->singleton('auth.session', function ($app) {
            return new IlluminateSession(
                $app['session.store'], $app['config']->get('mygedung.auth.session')
            );
        });
    }

    /**
     * Registers the cookie.
     *
     * @return void
     */
    protected function registerCookie()
    {
        $this->app->singleton('auth.cookie', function ($app) {
            return new IlluminateCookie(
                $app['request'], $app['cookie'], $app['config']->get('mygedung.auth.cookie')
            );
        });
    }

    /**
     * Registers the users.
     *
     * @return void
     */
    protected function registerUsers()
    {
        $this->registerHasher();

        $this->app->singleton('auth.users', function ($app) {
            $config = $app['config']->get('mygedung.auth.users');

            return new IlluminateUserRepository(
                $app['auth.hasher'], $app['events'], $config['model']
            );
        });
    }

    /**
     * Registers the hahser.
     *
     * @return void
     */
    protected function registerHasher()
    {
        $this->app->singleton('auth.hasher', function () {
            return new NativeHasher;
        });
    }

    /**
     * Registers the roles.
     *
     * @return void
     */
    protected function registerRoles()
    {
        $this->app->singleton('auth.roles', function ($app) {
            $config = $app['config']->get('mygedung.auth.roles');

            return new IlluminateRoleRepository($config['model']);
        });
    }

    /**
     * Registers the checkpoints.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function registerCheckpoints()
    {
        $this->registerActivationCheckpoint();

        $this->registerThrottleCheckpoint();

        $this->app->singleton('auth.checkpoints', function ($app) {
            $activeCheckpoints = $app['config']->get('mygedung.auth.checkpoints');

            $checkpoints = [];

            foreach ($activeCheckpoints as $checkpoint) {
                if (! $app->offsetExists("auth.checkpoint.{$checkpoint}")) {
                    throw new InvalidArgumentException("Invalid checkpoint [$checkpoint] given.");
                }

                $checkpoints[$checkpoint] = $app["auth.checkpoint.{$checkpoint}"];
            }

            return $checkpoints;
        });
    }

    /**
     * Registers the activation checkpoint.
     *
     * @return void
     */
    protected function registerActivationCheckpoint()
    {
        $this->registerActivations();

        $this->app->singleton('auth.checkpoint.activation', function ($app) {
            return new ActivationCheckpoint($app['auth.activations']);
        });
    }

    /**
     * Registers the activations.
     *
     * @return void
     */
    protected function registerActivations()
    {
        $this->app->singleton('auth.activations', function ($app) {
            $config = $app['config']->get('mygedung.auth.activations');

            return new IlluminateActivationRepository($config['model'], $config['expires']);
        });
    }

    /**
     * Registers the throttle checkpoint.
     *
     * @return void
     */
    protected function registerThrottleCheckpoint()
    {
        $this->registerThrottling();

        $this->app->singleton('auth.checkpoint.throttle', function ($app) {
            return new ThrottleCheckpoint(
                $app['auth.throttling'], $app['request']->getClientIp()
            );
        });
    }

    /**
     * Registers the throttle.
     *
     * @return void
     */
    protected function registerThrottling()
    {
        $this->app->singleton('auth.throttling', function ($app) {
            $model = $app['config']->get('mygedung.auth.throttling.model');

            $throttling = $app['config']->get('mygedung.auth.throttling');

            foreach ([ 'global', 'ip', 'user' ] as $type) {
                ${"{$type}Interval"} = $throttling[$type]['interval'];
                ${"{$type}Thresholds"} = $throttling[$type]['thresholds'];
            }

            return new IlluminateThrottleRepository(
                $model,
                $globalInterval,
                $globalThresholds,
                $ipInterval,
                $ipThresholds,
                $userInterval,
                $userThresholds
            );
        });
    }

    /**
     * Registers the reminders.
     *
     * @return void
     */
    protected function registerReminders()
    {
        $this->app->singleton('auth.reminders', function ($app) {
            $config = $app['config']->get('mygedung.auth.reminders');

            return new IlluminateReminderRepository(
                $app['auth.users'], $config['model'], $config['expires']
            );
        });
    }

    /**
     * Registers auth.
     *
     * @return void
     */
    protected function registerAuth()
    {
        $this->app->singleton('auth', function ($app) {
            $auth = new Auth(
                $app['auth.persistence'],
                $app['auth.users'],
                $app['auth.roles'],
                $app['auth.activations'],
                $app['events']
            );

            if (isset($app['auth.checkpoints'])) {
                foreach ($app['auth.checkpoints'] as $key => $checkpoint) {
                    $auth->addCheckpoint($key, $checkpoint);
                }
            }

            $auth->setActivationRepository($app['auth.activations']);
            $auth->setReminderRepository($app['auth.reminders']);

            $auth->setRequestCredentials(function () use ($app) {
                $request = $app['request'];

                $login = $request->getUser();
                $password = $request->getPassword();

                if ($login === null && $password === null) {
                    return;
                }

                return compact('login', 'password');
            });

            $auth->creatingBasicResponse(function () {
                $headers = ['WWW-Authenticate' => 'Basic'];

                return new Response('Invalid credentials.', 401, $headers);
            });

            return $auth;
        });

        $this->app->alias('auth', 'mygedung\Auth\Auth');
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'auth.session',
            'auth.cookie',
            'auth.persistence',
            'auth.hasher',
            'auth.users',
            'auth.roles',
            'auth.activations',
            'auth.checkpoint.activation',
            'auth.throttling',
            'auth.checkpoint.throttle',
            'auth.checkpoints',
            'auth.reminders',
            'auth',
        ];
    }

    /**
     * Garbage collect activations and reminders.
     *
     * @return void
     */
    protected function garbageCollect()
    {
        $config = $this->app['config']->get('mygedung.auth');

        $this->sweep(
            $this->app['auth.activations'], $config['activations']['lottery']
        );

        $this->sweep(
            $this->app['auth.reminders'], $config['reminders']['lottery']
        );
    }

    /**
     * Sweep expired codes.
     *
     * @param  mixed  $repository
     * @param  array  $lottery
     * @return void
     */
    protected function sweep($repository, array $lottery)
    {
        if ($this->configHitsLottery($lottery)) {
            try {
                $repository->removeExpired();
            } catch (Exception $e) {
            }
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param  array  $lottery
     * @return bool
     */
    protected function configHitsLottery(array $lottery)
    {
        return mt_rand(1, $lottery[1]) <= $lottery[0];
    }

    /**
     * Sets the user resolver on the request class.
     *
     * @return void
     */
    protected function setUserResolver()
    {
        $this->app->rebinding('request', function ($app, $request) {
            $request->setUserResolver(function () use ($app) {
                return $app['auth']->getUser();
            });
        });
    }

    /**
     * Performs the necessary overrides.
     *
     * @return void
     */
    protected function setOverrides()
    {
        $config = $this->app['config']->get('mygedung.auth');

        $users = $config['users']['model'];

        $roles = $config['roles']['model'];

        $persistences = $config['persistences']['model'];

        if (class_exists($users)) {
            if (method_exists($users, 'setRolesModel')) {
                forward_static_call_array([ $users, 'setRolesModel' ], [ $roles ]);
            }

            if (method_exists($users, 'setPersistencesModel')) {
                forward_static_call_array([ $users, 'setPersistencesModel' ], [ $persistences ]);
            }

            if (method_exists($users, 'setPermissionsClass')) {
                forward_static_call_array([ $users, 'setPermissionsClass' ], [ $config['permissions']['class'] ]);
            }
        }

        if (class_exists($roles) && method_exists($roles, 'setUsersModel')) {
            forward_static_call_array([ $roles, 'setUsersModel' ], [ $users ]);
        }

        if (class_exists($persistences) && method_exists($persistences, 'setUsersModel')) {
            forward_static_call_array([ $persistences, 'setUsersModel' ], [ $users ]);
        }
    }
}
