<?php

namespace Framework\Session;

/**
 * Class used for unit testing, as real $_SESSION is difficult to test
 * Class ArraySession
 * @package Framework\Session
 */
class ArraySession implements SessionInterface
{

    private $session = [];

    /**
     * Get session key
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->session)) {
            return $this->session[$key];
        }
        return $default;
    }

    /**
     * Add session key
     * @param string $key
     * @param $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->session[$key] = $value;
    }

    /**
     * Delete session key
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        unset($this->session[$key]);
    }
}
