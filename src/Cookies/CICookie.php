<?php

/**
 * Part of the Auth package.
*/

namespace myGedung\Auth\Cookies;

use CI_Input as Input;

class CICookie implements CookieInterface
{
    /**
     * The CodeIgniter input object.
     *
     * @var \CI_Input
     */
    protected $input;

    /**
     * The cookie options.
     *
     * @var array
     */
    protected $options = [
        'name'   => 'mygedung_auth',
        'domain' => '',
        'path'   => '/',
        'prefix' => '',
        'secure' => false,
    ];

    /**
     * Create a new CodeIgniter cookie driver.
     *
     * @param  \CI_Input  $input
     * @param  string|array  $options
     * @return void
     */
    public function __construct(Input $input, $options = [])
    {
        $this->input = $input;

        if (is_array($options)) {
            $this->options = array_merge($this->options, $options);
        } else {
            $this->options['name'] = $options;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function put($value)
    {
        $options = array_merge($this->options, [
            'value'  => json_encode($value),
            'expire' => 2628000,
        ]);

        $this->input->set_cookie($options);
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        $value = $this->input->cookie($this->options['name']);

        if ($value) {
            return json_decode($value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function forget()
    {
        $this->input->set_cookie([
            'name'   => $this->options['name'],
            'value'  => '',
            'expiry' => '',
        ]);
    }
}
