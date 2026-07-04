<?php
	namespace Project\Controllers;
	use \Core\Controller;
	
	class ErrorController extends Controller
	{
		public function notFound() {
			$this->title = 'Страница не найдена | SweetLolly';
			
			return $this->render('error/notFound');
		}
	}
