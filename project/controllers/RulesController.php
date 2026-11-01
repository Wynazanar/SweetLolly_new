<?php
namespace Project\Controllers;

use Core\Controller;
use Project\Models\Rule;

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