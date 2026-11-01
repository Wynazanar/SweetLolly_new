<?php
namespace Project\Controllers;

use Core\Controller;
use Project\Models\Subscription;

class DonatsController extends Controller
{
    public function index()
    {
        $this->title = 'Донат | SweetLolly';

        return $this->render('donats/index', [
            'subs' => (new Subscription())->all(),
        ]);
    }
}