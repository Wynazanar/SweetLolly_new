<?php
namespace App\Controllers;

use Core\Controller;

class HelpController extends Controller
{
    public function index()
    {
        $this->title = 'Помощь | SweetLolly';
        return $this->render('help/index');
    }

    public function team()
    {
        $this->title = 'Команда проекта | SweetLolly';
        return $this->render('help/index'); // пока тот же шаблон
    }
}