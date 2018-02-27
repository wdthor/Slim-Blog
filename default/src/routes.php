<?php

use Slim\Http\Request;
use Slim\Http\Response;
use simplon\entities\User;
use simplon\entities\Article;
use simplon\dao\DaoUser;
use simplon\dao\DaoArticle;

// Routes


$app->get('/', function (Request $request, Response $response, array $args) {
    //On instancie le dao
    $daoUser = new DaoUser();
    $daoArticle = new DaoArticle();
    //On récupère les Persons via la méthode getAll
    $users = $daoUser->getAll();
    $articles = $daoArticle->getAll();
    //On passe les persons à la vue index.twig
    return $this->view->render($response, 'index.twig', [
        'users' => $users,
        'articles' => $articles
    ]);
})->setName('index');

$app->get('/adduser', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'adduser.twig');
})->setName('adduser');


$app->post('/adduser', function (Request $request, Response $response, array $args) {
    //On récupère les données du formulaire
    $form = $request->getParsedBody();
    //On crée une Person à partir de ces données
    $newUser = new User($form['username'], $form['email'], $form['password'], $form['firstName'], $form['lastName'], $form['image']);
    //On instancie le DAO
    $dao = new DaoUser();
    //On utilise la méthode add du DAO en lui donnant la Person qu'on vient de créer
    $dao->add($newUser);
    //On affiche la même vue que la route en get
    $redirectUrl = $this->router->pathFor('index');
    //On redirige l'utilisateur sur la page d'accueil
    return $response->withRedirect($redirectUrl);
    
})->setName('adduser');

$app->get('/connection', function (Request $request, Response $response, array $args) {
    $dao = new DaoUser();
    $users = $dao->getAll();
    return $this->view->render($response, 'connection.twig', [
        'users' => $users
    ]);
})->setName('connection');

$app->post('/connection', function (Request $request, Response $response, array $args) {
    
    $daoUser = new daoUser();
    $form = $request->getParsedBody();

    $blogUser = $daoUser->getByUsername($form['username']);

    $redirectUrl = $this->router->pathFor('bloguser');

    if($form['username'] == $blogUser->getUsername($form['username']) && $form['password'] == $blogUser->getPassword($form['password'])) {
        $_SESSION['curUser'] = $blogUser;
        return $response->withRedirect($redirectUrl);
    }

    return $this->view->render($response, 'connection.twig');
})->setName('connection');

$app->get('/bloguser', function (Request $request, Response $response, array $args) {
    $daoUser = new DaoUser();
    $daoArticle = new DaoArticle();

    $curUser = $_SESSION['curUser'];
    $userId = $curUser->getId();
    $articles = $daoArticle->getAllByUserId($userId);
     
     return $this->view->render($response, 'bloguser.twig', [
         "curUser" => $curUser,
         "articles" => $articles
     ]);
     
 })->setName('bloguser');

 $app->get('/addarticle', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'addarticle.twig');
})->setName('addarticle');

 $app->post('/addarticle', function (Request $request, Response $response, array $args) {
    //On récupère les données du formulaire
    $form = $request->getParsedBody();
    $curUser = $_SESSION['curUser'];
    //On crée une Person à partir de ces données
    $daoArticle = new DaoArticle();

    $newArticle = new Article($form['title'], $form['description'], $curUser->getId(), new DateTime(), $form['image']);
    //On instancie le DAO
    //On utilise la méthode add du DAO en lui donnant la Person qu'on vient de créer
    $daoArticle->add($newArticle);
    //On affiche la même vue que la route en get
    $redirectUrl = $this->router->pathFor('bloguser');
    //On redirige l'utilisateur sur la page d'accueil
    return $response->withRedirect($redirectUrl);
    
})->setName('addarticle');

$app->get('/updateperson/{id}', function (Request $request, Response $response, array $args) {
   
   $curUser = $_SESSION['curUser'];
   $curUser->getId();
    //On instancie le DAO
    $dao = new DaoPerson;
    //On récupère la Person à partir de l'id
    $person = $dao->getById($args['id']);
    // On affiche la vue du formulaire d'update d'une peronne
    return $this->view->render($response, 'updateperson.twig', [
        'person' => $person
    ]);
    
})->setName('updateperson');

$app->post('/updateperson/{id}', function (Request $request, Response $response, array $args) {
    //On instancie le DAO
    $dao = new DaoPerson;
    //On récupère les données du formulaire
    $postData = $request->getParsedBody();
    //On récupère la Person à partir de l'id
    $person = $dao->getById($args['id']);
    //On met à jour son nom, sa date de naissance et son genre
    $person->setName($postData['name']);
    $person->setBirthdate(new \DateTime($postData['birthdate']));
    $person->setGender($postData['gender']);
    //On update la personne
    $dao->update($person);
    //On récupère l'URL da la route index (page d'accueil)
    $redirectUrl = $this->router->pathFor('index');
    //On redirige l'utilisateur sur la page d'accueil
    return $response->withRedirect($redirectUrl);
})->setName('updateperson');

$app->get('/deleteperson/{id}', function (Request $request, Response $response, array $args) {
    //On instancie le DAO
    $dao = new DaoPerson;
    //On delete la personne
    $dao->delete($args['id']);
    //On récupère l'URL da la route index (page d'accueil)
    $redirectUrl = $this->router->pathFor('index');
    //On redirige l'utilisateur sur la page d'accueil
    return $response->withRedirect($redirectUrl);
})->setName('deleteperson');