<?php

namespace Framework\Session;

interface SessionInterface
{
    /**
     * Get session key
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Add session key
     * @param string $key
     * @param $value
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * Delete session key
     * @param string $key
     * @return void
     */
    public function delete(string $key): void;
}
