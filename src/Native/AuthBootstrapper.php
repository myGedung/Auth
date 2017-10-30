<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Native;

use myGedung\Auth\Activations\IlluminateActivationRepository;
use myGedung\Auth\Checkpoints\ActivationCheckpoint;
use myGedung\Auth\Checkpoints\ThrottleCheckpoint;
use myGedung\Auth\Cookies\NativeCookie;
use myGedung\Auth\Hashing\NativeHasher;
use myGedung\Auth\Persistences\IlluminatePersistenceRepository;
use myGedung\Auth\Reminders\IlluminateReminderRepository;
use myGedung\Auth\Roles\IlluminateRoleRepository;
use myGedung\Auth\Auth;
use myGedung\Auth\Sessions\NativeSession;
use myGedung\Auth\Throttling\IlluminateThrottleRepository;
use myGedung\Auth\Users\IlluminateUserRepository;
use Illuminate\Events\Dispatcher;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class AuthBootstrapper
{
    /**
     * Configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param  array  $config
     * @return void
     */
    public function __construct($config = null)
    {
        if (is_string($config)) {
            $this->config = new ConfigRepository($config);
        } else {
            $this->config = $config ?: new ConfigRepository;
        }
    }

    /**
     * Creates a auth instance.
     *
     * @return \myGedung\Auth\Auth
     */
    public function createAuth()
    {
        $persistence = $this->createPersistence();
        $users       = $this->createUsers();
        $roles       = $this->createRoles();
        $activations = $this->createActivations();
        $dispatcher  = $this->getEventDispatcher();

        $auth = new Auth(
            $persistence,
            $users,
            $roles,
            $activations,
            $dispatcher
        );

        $throttle = $this->createThrottling();

        $ipAddress = $this->getIpAddress();

        $checkpoints = $this->createCheckpoints($activations, $throttle, $ipAddress);

        foreach ($checkpoints as $key => $checkpoint) {
            $auth->addCheckpoint($key, $checkpoint);
        }

        $reminders = $this->createReminders($users);

        $auth->setActivationRepository($activations);

        $auth->setReminderRepository($reminders);

        $auth->setThrottleRepository($throttle);

        return $auth;
    }

    /**
     * Creates a persistences repository.
     *
     * @return \myGedung\Auth\Persistences\IlluminatePersistenceRepository
     */
    protected function createPersistence()
    {
        $session = $this->createSession();

        $cookie = $this->createCookie();

        $model = $this->config['persistences']['model'];

        $single = $this->config['persistences']['single'];

        return new IlluminatePersistenceRepository($session, $cookie, $model, $single);
    }

    /**
     * Creates a session.
     *
     * @return \myGedung\Auth\Sessions\NativeSession
     */
    protected function createSession()
    {
        return new NativeSession($this->config['session']);
    }

    /**
     * Creates a cookie.
     *
     * @return \myGedung\Auth\Cookies\NativeCookie
     */
    protected function createCookie()
    {
        return new NativeCookie($this->config['cookie']);
    }

    /**
     * Creates a user repository.
     *
     * @return \myGedung\Auth\Users\IlluminateUserRepository
     */
    protected function createUsers()
    {
        $hasher = $this->createHasher();

        $model = $this->config['users']['model'];

        $roles = $this->config['roles']['model'];

        $persistences = $this->config['persistences']['model'];

        if (class_exists($roles) && method_exists($roles, 'setUsersModel')) {
            forward_static_call_array([$roles, 'setUsersModel'], [$model]);
        }

        if (class_exists($persistences) && method_exists($persistences, 'setUsersModel')) {
            forward_static_call_array([$persistences, 'setUsersModel'], [$model]);
        }

        return new IlluminateUserRepository($hasher, $this->getEventDispatcher(), $model);
    }

    /**
     * Creates a hasher.
     *
     * @return \myGedung\Auth\Hashing\NativeHasher
     */
    protected function createHasher()
    {
        return new NativeHasher;
    }

    /**
     * Creates a role repository.
     *
     * @return \myGedung\Auth\Roles\IlluminateRoleRepository
     */
    protected function createRoles()
    {
        $model = $this->config['roles']['model'];

        $users = $this->config['users']['model'];

        if (class_exists($users) && method_exists($users, 'setRolesModel')) {
            forward_static_call_array([$users, 'setRolesModel'], [$model]);
        }

        return new IlluminateRoleRepository($model);
    }

    /**
     * Creates an activation repository.
     *
     * @return \myGedung\Auth\Activations\IlluminateActivationRepository
     */
    protected function createActivations()
    {
        $model = $this->config['activations']['model'];

        $expires = $this->config['activations']['expires'];

        return new IlluminateActivationRepository($model, $expires);
    }

    /**
     * Returns the client's ip address.
     *
     * @return string
     */
    protected function getIpAddress()
    {
        $request = Request::createFromGlobals();

        return $request->getClientIp();
    }

    /**
     * Create an activation checkpoint.
     *
     * @param  \myGedung\Auth\Activations\IlluminateActivationRepository  $activations
     * @return \myGedung\Auth\Checkpoints\ActivationCheckpoint
     */
    protected function createActivationCheckpoint(IlluminateActivationRepository $activations)
    {
        return new ActivationCheckpoint($activations);
    }

    /**
     * Create activation and throttling checkpoints.
     *
     * @param  \myGedung\Auth\Activations\IlluminateActivationRepository  $activations
     * @param  \myGedung\Auth\Throttling\IlluminateThrottleRepository  $throttle
     * @param  string  $ipAddress
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function createCheckpoints(IlluminateActivationRepository $activations, IlluminateThrottleRepository $throttle, $ipAddress)
    {
        $activeCheckpoints = $this->config['checkpoints'];

        $activation = $this->createActivationCheckpoint($activations);

        $throttle = $this->createThrottleCheckpoint($throttle, $ipAddress);

        $checkpoints = [];

        foreach ($activeCheckpoints as $checkpoint) {
            if (! isset($$checkpoint)) {
                throw new InvalidArgumentException("Invalid checkpoint [{$checkpoint}] given.");
            }

            $checkpoints[$checkpoint] = $$checkpoint;
        }

        return $checkpoints;
    }

    /**
     * Create a throttle checkpoint.
     *
     * @param  \myGedung\Auth\Throttling\IlluminateThrottleRepository  $throttle
     * @param  string  $ipAddress
     * @return \myGedung\Auth\Checkpoints\ThrottleCheckpoint
     */
    protected function createThrottleCheckpoint(IlluminateThrottleRepository $throtte, $ipAddress)
    {
        return new ThrottleCheckpoint($throtte, $ipAddress);
    }

    /**
     * Create a throttling repository.
     *
     * @return \myGedung\Auth\Throttling\IlluminateThrottleRepository
     */
    protected function createThrottling()
    {
        $model = $this->config['throttling']['model'];

        foreach (['global', 'ip', 'user'] as $type) {
            ${"{$type}Interval"} = $this->config['throttling'][$type]['interval'];

            ${"{$type}Thresholds"} = $this->config['throttling'][$type]['thresholds'];
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
    }

    /**
     * Returns the event dispatcher.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    protected function getEventDispatcher()
    {
        if (! $this->dispatcher) {
            $this->dispatcher = new Dispatcher;
        }

        return $this->dispatcher;
    }

    /**
     * Create a reminder repository.
     *
     * @param  \myGedung\Auth\Users\IlluminateUserRepository  $users
     * @return \myGedung\Auth\Reminders\IlluminateReminderRepository
     */
    protected function createReminders(IlluminateUserRepository $users)
    {
        $model = $this->config['reminders']['model'];

        $expires = $this->config['reminders']['expires'];

        return new IlluminateReminderRepository($users, $model, $expires);
    }
}
