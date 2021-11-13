<?php

namespace Session;


class Session
{
    /**
     *
     */
    public function startSession() : void
    {
        session_start();
    }

    /**
     * @param string $key
     * @param $data
     */
    public function setData(string $key, $data) : void{
        $_SESSION[$key] = $data;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getData(string $key) {
        return !empty($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     *
     */
    public function saveSession(): void
    {
        session_write_close();
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function flush(string $key)
    {
        $result = !empty($_SESSION[$key]) ? $_SESSION[$key] : null;
        $this->unset($key);
        return $result;
    }

    /**
     * @param string $key
     */
    public function unset(string $key){
        unset($_SESSION[$key]);
    }

}