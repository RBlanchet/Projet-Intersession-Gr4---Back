<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/07/2018
 * Time: 10:11
 */

namespace AppBundle\Entity;

class Credentials
{
    protected $login;

    protected $password;

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}