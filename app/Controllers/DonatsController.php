<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Subscription;

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