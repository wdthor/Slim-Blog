<?php

use Slim\Http\Request;
use Slim\Http\Response;
use simplon\entities\Person;
use simplon\dao\DaoPerson;

// Routes


$app->get('/', function (Request $request, Response $response, array $args) {
    //On instancie le dao
    $dao = new DaoPerson();
    //On récupère les Persons via la méthode getAll
    $persons = $dao->getAll();
    //On passe les persons à la vue index.twig
    return $this->view->render($response, 'index.twig', [
        'persons' => $persons
    ]);
})->setName('index');

$app->get('/addperson', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'addperson.twig');
})->setName('addperson');



$app->post('/addperson', function (Request $request, Response $response, array $args) {
    //On récupère les données du formulaire
    $form = $request->getParsedBody();
    //On crée une Person à partir de ces données
    $newPerson = new Person($form['name'], new DateTime($form['birthdate']), $form['gender']);
    //On instancie le DAO
    $dao = new DaoPerson();
    //On utilise la méthode add du DAO en lui donnant la Person qu'on vient de créer
    $dao->add($newPerson);
    //On affiche la même vue que la route en get
    return $this->view->render($response, 'addperson.twig', [
        'newId' => $newPerson->getId()
    ]);
})->setName('addperson');

$app->get('/updateperson/{id}', function (Request $request, Response $response, array $args) {
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