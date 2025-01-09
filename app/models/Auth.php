<?php
namespace App\Models;

use App\Crud\crud;

class Auth extends crud
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login($email, $password)
    {
        return parent::login($email, $password);
    }

    public function isAuth()
    {
        return parent::isAuth();
    }

    public function setMessage($message)
    {
        return parent::setMessage($message);
    }

    public function getMessage()
    {
        return parent::getMessage();
    }

    public function hasMessage()
    {
        return parent::hasMessage();
    }

    public function logout()
    {
        return parent::logout();
    }

    public function getRole()
    {
        return parent::getRole();
    }
}
?>