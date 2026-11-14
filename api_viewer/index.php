<?php
header('Access-Control-Allow-Origin: *');
// Autorise les méthodes et les headers personnalisés
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/config/config.php';

spl_autoload_register(function($class) {
    $directories = ['Entity','Controller','Repository', 'Mapper','Kernel','ErrorHandler'];
    foreach($directories as $directory){
        $file = __DIR__ . "/appli/$directory/$class.php";
        if(file_exists($file)){
            require_once $file ;
        }
    }
});

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") { // si c'est une requete OPTIONS
    http_response_code(200);
    exit;
}

$parts =  explode("/", $_SERVER["REQUEST_URI"]);
// $parts = ['', 'orthophotos', 'api_viewer', 'photos'];
// $parts = ['otopography.ddns.net','api','users']; en prod $parts comporte un segment en moins
if ($parts[1] === API_NAME) {
    $index = 3;
} else {
    $index = 2;
}

if ( !in_array( $parts[$index], COLLECTIONS) ) { // si pas dans le tableau des collections
    http_response_code(404);
    exit;
}

$table = convertToPascalCase(substr( $parts[$index],0,-1 ));
// if ($table == 'LessonCategorie') { // cas particulier
//     $table = 'LessonCategory';
// }
$itemRepository = $table .'Repository';
$Repository = new $itemRepository(); // ancienne methode // $Repository = new $itemRepository($database);
$itemMapper = $table.'Mapper';
$Mapper = new $itemMapper();
$id = null;
$email = null;
$idUser = null;
if(isset($parts[$index+1]) && filter_var($parts[$index+1], FILTER_VALIDATE_INT) !== false) { // vérifier que c'est un int
    $id = (int)$parts[$index+1];
}elseif(isset($parts[$index+1]) &&  filter_var($parts[$index+1], FILTER_VALIDATE_EMAIL) !== false) { // si que c'est un email
    $email = $parts[$index+1];
}

// todo  possible qu'on elimine cette partie
$parts5 = isset($parts[$index+2]) ? $parts[$index+2] : null;
//var_dump($parts5);
// if($parts5 === 'site'){
//     if(isset($parts[$index+3]) && filter_var($parts[$index+3], FILTER_VALIDATE_INT) !== false) { 
//         $idUser =(int)$parts[$index+3]; // verification int iduser dans reservation est int
//     }
// }

$itemController = $table.'Controller'; // string 

$Controller = new $itemController($Repository, $Mapper) ;  // todo automatiser en utilisant $parts[3]
$Controller->processRequest($_SERVER["REQUEST_METHOD"], $id, $parts5, $idUser);
// $Controller->processRequest($_SERVER["REQUEST_METHOD"], $id, $email , $parts5, $idUser);
// var_dump($Controller);

function convertToPascalCase(string $url): string
{
   return str_replace('-', '', ucwords($url, '-'));
}
