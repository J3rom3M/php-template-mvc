<?php

// On incluant ce fichier, je vais faire un
// "require" automatique de tous les fichiers PHP
// téléchargés par Composer dans le dossier vendor.
require __DIR__ . '/../vendor/autoload.php';

// J'inclus les fichiers contenant mes Controllers
// Je n'ai plus besoin d'inclure mes classes une par une vu que j'ai configuré l'autoload avec Composer

// Pour utiliser AltoRouter, il faut instancier la classe AltoRouter
// C'est à dire, créer un variable qui va contenir un objet créé à partir
// de la classe AltoRouter
$router = new AltoRouter();

// On défini le chemin vers notre site
// C'est à dire les sous-dossiers entre le réertoire
// "html" et l'endroit ou est situé index.php
// On utilise l'entree BASE_URI du tableau $_SERVER
// pour récuperer dynamiquement le chemin vers notre site.
$router->setBasePath($_SERVER['BASE_URI']);


// Définir nos routes
// à chaque fois que l'on appelle la fonction map() de notre router
// on l'informe de l'existance d'une route sur notre site.
// On fait correspondre une URL avec une methode d'un controller.
$router->map(
    // Le type de requete HTTP
    'GET',
    // L'URL, ou le schema d'URL, que AltoRouter va surveiller pour activer cette
    // route
    '/',
    // cible, ou target, c'est à dire la "destination de la route
    [
        'method'     => 'home',
        'controller' => 'JM\Controllers\MainController'
    ],
    // optionelement, on peut nommer notre route
    // on s'en servira un peu plus tard pour retrouver les infos de notre route
    'accueil'
);
$router->map(
    'GET',
    '/categorie/[i:id]',
    [
        'method'     => 'category',
        'controller' => 'JM\Controllers\CatalogController'
    ],
    'categorie'
);

$router->map(
    'GET',
    '/type/[i:id]',
    [
        'method'     => 'type',
        'controller' => 'JM\Controllers\CatalogController'
    ],
    'type'
);

$router->map(
    'GET',
    '/legal-mentions',
    [
        'method'     => 'legalMentions',
        'controller' => 'JM\Controllers\MainController'
    ],
    'legalMentions'
);

$router->map(
    'GET',
    '/marque/[i:id]',
    [
        'method'     => 'brand',
        'controller' => 'JM\Controllers\CatalogController'
    ],
    'brand'
);

$router->map(
    'GET',
    '/produit/[i:id]',
    [
        'method'     => 'product',
        'controller' => 'JM\Controllers\CatalogController'
    ],
    'product'
);

/*
// Reverse routing
dump(
    $router->generate('accueil'),
    $router->generate('legalMentions'),
    $router->generate('product', ['id' => 1]),
    $router->generate('product', ['id' => 2]),
    $router->generate('product', ['id' => 3]),
    $router->generate('product', ['id' => 4]),
);
*/

// Récupérer les informations de la route utilisée.
// -> controller + méthode
$match = $router->match();
// dump($match);

if ($match !== false) {
    // $match est une variable (qu'on aurait nommer comme on le souhaite)
    // qui contient l'intégralité des information liées à la route utilisée.
    // si l'internaute visite la page '/' de notre site, c'est donc la route
    // nommée 'accueil' qui va être activée et $match va donc contenir TOUTES
    // les infos de la route 'acceuil'
    //  [
    // "target" => array:2 [
    //     "method" => "home"
    //     "controller" => "MainController"
    // ]
    // "params" => []
    // "name" => "accueil"
    // ]
    // Les clefs (ou indexes) "target", "params" et "name" sont créés et imposées
    // AltoRouter.
    // La clef "target" contient les informations que nous nous donnée précédement
    // lors de la création de la route via la fonction map() de l'objet de type
    // AltoRouter ($router)

    $controllerToUse = $match['target']['controller'];
    $methodToCall    = $match['target']['method'];

    // Instancier le controller correspondant
    $controller = new $controllerToUse($router);

    // Appeler la bonne méthode
    $controller->$methodToCall($match['params']);
}
else {
    // TODO : gestion 404 (page non trouvée)
}





// // Récupérer le parametre GET, ou parametre d'URL
// if (isset($_GET['_url'])) {
//     $page = $_GET['_url'];
// }
// else {
//     $page = '/';
// }

// // Réécriture d'URL
// // / -> index.php?_url=/

// // Définir les différents pages qui existent dans mon site
// // et associer une page avec la methode du MainController
// // correspondante.

// // Ce tableau permet d'associer une page avec le controller
// // et sa méthode qui va afficher cette page.
// $pagesList = [
//     // On appelle ça des routes
//     '/' => [
//         'method'     => 'home',
//         'controller' => 'MainController'
//     ],
//     // TODO : à faire fonctionner
//     '/categorie/$id' => [
//         'method'     => 'category',
//         'controller' => 'CatalogController'
//     ],
// ];

// // On récupère le nom du controller à utiliser
// // pour affciher la page demandée
// $controllerToUse = $pagesList[$page]['controller'];
// // var_dump($controllerToUse);

// // Instancier le controller utilisé pour afficher
// // la page demandée
// $controller = new $controllerToUse();
// // var_dump($controller);

// // appeler la bonne méthode du MainController
// $methodToCall = $pagesList[$page]['method'];

// // Ici on apppelle "dynamiquement" la bonne méthode du controller
// // Si $methodToCall contient 'store'
// // Alors, c'est comme si on avait écrit  $controller->store()
// // Si $methodToCall contient 'products'
// // Alors, c'est comme si on avait écrit  $controller->store()
// $controller->$methodToCall();