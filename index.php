<?php
namespace Core;

error_reporting(E_ALL);
ini_set('display_errors', 'on');

$projectRoot = $_SERVER['DOCUMENT_ROOT'] . '/SweetLolly_new';
$baseUrl = '/SweetLolly_new';

$uri = $_SERVER['REQUEST_URI'];
if (strpos($uri, $baseUrl) === 0) {
    $uri = substr($uri, strlen($baseUrl));
}
if (empty($uri) || $uri === '/') {
    $uri = '/';
}

require_once $projectRoot . '/project/config/connection.php';

spl_autoload_register(function($class) use ($projectRoot) {
    preg_match('#(.+)\\\\(.+?)$#', $class, $match);
    
    $nameSpace = str_replace('\\', DIRECTORY_SEPARATOR, strtolower($match[1]));
    $className = $match[2];
    
    $path = $projectRoot . DIRECTORY_SEPARATOR . $nameSpace . DIRECTORY_SEPARATOR . $className . '.php';
    
    if (file_exists($path)) {
        require_once $path;
        
        if (class_exists($class, false)) {
            return true;
        } else {
            throw new \Exception("Class $class not found in file $path. Check the correctness of the class name.");
        }
    } else {
        throw new \Exception("File $path not found for class $class. 
            Check if the file exists at the specified path.");
    }
});

$routes = require $projectRoot . '/project/config/routes.php';

$track = ( new Router ) -> getTrack($routes, $uri);
$page  = ( new Dispatcher ) -> getPage($track);

echo (new View) -> render($page);