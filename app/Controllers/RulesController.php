<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Rule;

class RulesController extends Controller
{
    public function index()
    {
        $this->title = 'Правила | SweetLolly';

        $ruleModel = new Rule();

        return $this->render('rules/index', [
            'rules'          => $ruleModel->all(),
            'ruleCategories' => $ruleModel->allCategories(),
        ]);
    }
}