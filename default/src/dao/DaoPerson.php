<?php

namespace simplon\dao;
use simplon\entities\Person;
use simplon\dao\Connect;
/**
 * Un Dao, pour Data Access Object, est une classe dont le but est de faire
 * le lien entre les tables SQL et les objets PHP (ou autre langage).
 * Le but est de centraliser dans la ou les classes DAO tous les appels
 * SQL pour ne pas avoir de SQL qui se balade partout dans note application
 * (comme ça, si on change de SGBD, ou de table, ou de database, on aura
 * juste le DAO à modifier et le reste de notre appli restera inchangé)
 */
class DaoPerson {
    
    
    /**
     * La méthode getAll renvoie toutes les persons stockées en bdd
     * @return Person[] la liste des person ou une liste vide
     */
    public function getAll():array {
        //On commence par créer un tableau vide dans lequel on stockera
        //les person s'il y en a  et qu'on returnera dans tous les cas
        $tab = [];
        /*On crée une connexion à notre base de données en utilisant 
        l'objet PDO qui attend en premier argument le nom de notre SGBD,
        l'hôte où est notre bdd (ici c'est mysql du fait qu'on soit sur un docker)
        et le nom de la bdd, en deuxième argument le nom d'utilisateur de notre bdd et en troisième argument son
        mot de passe.
        On récupère une connexion à la base sur laquelle on pourra
        faire des requêtes et autre.
        */
        try {
            // $pdo = new \PDO('mysql:host=mysql;dbname=db;','root','root');
            /*On utilise la méthode prepare() de notre connexion pour préparer
            une requête SQL (elle n'est pas envoyée tant qu'on ne lui dit pas)
            La méthode prepare attend en argument une string SQL
            */
            $query = Connect::getInstance()->prepare('SELECT * FROM person');
            //On dit à notre requête de s'exécuter, à ce moment là, le résultat
            //de la requête est disponible dans la variable $query
            $query->execute();
            /*On itère sur les différentes lignes de résultats retournées par
            notre requête en utilisant un $query->fetch qui renvoie une ligne
            de résultat sous forme de tableau associatif tant qu'il y a des
            résultat. On stock donc le retour de ce fetch dans une variable 
            $row et on boucle dessus
            */
            while($row = $query->fetch()) {
                /*
                A chaque tour de boucle, on se sert de notre ligne de résultat
                sous forme de tableau associatif pour créer une instance de 
                Person en lui donnant en argument les différentes valeurs des
                colonnes de la ligne de résultat.
                Les index de $row correspondent aux noms de colonnes dans notre
                SQL.
                */
                $pers = new Person($row['name'], 
                            new \DateTime($row['birth_date']), 
                            $row['gender'],
                            $row['id']);
                //On ajoute la person créée à notre tableau
                $tab[] = $pers;
            }
        }catch(\PDOException $e) {
            echo $e;
        }
        //On return le tableau
        return $tab;
    }
    /**
     * Méthode permettant de récupérer une Person en se basant sur
     * son Id
     * @return Person|null renvoie soit la Person correspondante soit null
     * si pas de match
     */
    public function getById(int $id) {
        
        try {
            /**
             * On prépare notre requête, mais cette fois ci, nous avons un
             * argument à insérer dans la requête : l'id.
             * La concaténation est absolument déconseillé dans les string
             * SQL car ça ouvrirait notre code aux injections SQL qui sont
             * un soucis très grave.
             * A la place, on met un placeholder dans la requête auquel on
             * donne un label précédé de :, par exemple :id
             */
            $query = Connect::getInstance()->prepare('SELECT * FROM person WHERE id=:id');
            /**
             * Chaque placeholder d'une requête doit être bindée, soit par
             * un bindValue, soit directement dans le execute via un 
             * tableau associatif.
             * Ici, on dit qu'on met la valeur de la variable $id, là où
             * on a mis le :id dans la requête, et on indique que la 
             * valeur en question doit être de type int
             */
            $query->bindValue(':id', $id, \PDO::PARAM_INT);
            //On exécute la requête
            $query->execute();
            //Si le fetch nous renvoie quelque chose
            if($row = $query->fetch()) {
                //On crée une instance de Person
                $pers = new Person($row['name'], 
                            new \DateTime($row['birth_date']), 
                            $row['gender'],
                            $row['id']);
                //On return cette Person
                return $pers;
            }
        }catch(\PDOException $e) {
            echo $e;
        }
        /**
         * Si jamais on est pas passé dans le if ou autre, on renvoie null
         * qui est une valeur inexistante. C'est quelque chose d'assez
         * utilisé dans beaucoup de langages. 
         */
        return null;
    }
    /**
     * Méthode permettant de faire persister en base de données une 
     * instance de Person passée en argument.
     */
    public function add(Person $pers) {
        
        try {
            //On prépare notre requête, avec les divers placeholders
            $query = Connect::getInstance()->prepare('INSERT INTO person (name,birth_date,gender) VALUES (:name, :birth_date, :gender)');
            /**
             * On bind les différentes values qu'on récupère de l'instance
             * de Person qui nous est passée en argument, via ses
             * accesseurs get*()
             */
            $query->bindValue(':name',$pers->getName(),\PDO::PARAM_STR);
            /**
             * Pour la date, PDO attend une date en string au format 
             * aaaa-mm-dd, hors, la birthdate de notre Person est une
             * instance de DateTime, on utilise donc la méthode format()
             * de DateTime pour la convertir au format textuel souhaité.
             */
            $query->bindValue(':birth_date',$pers->getBirthdate()->format('Y-m-d'),\PDO::PARAM_STR);
            $query->bindValue(':gender',$pers->getGender(),\PDO::PARAM_INT);

            $query->execute();
            /**
             * On fait en sorte de récupérer le dernier id généré par SQL 
             * afin de l'assigner à l'id de notre instance de Person
             */
            $pers->setId(Connect::getInstance()->lastInsertId());
            
        }catch(\PDOException $e) {
            echo $e;
        }
    }
    /**
     * Une méthode pour mettre à jour les informations d'une Person 
     * déjà existante dans la base de donnée.
     * L'argument $pers doit être une instance de Person complète, avec
     * un id existant en base.
     */
    public function update(Person $pers) {
        
        try {
            //toujours pareil, on prépare la requête
            $query = Connect::getInstance()->prepare('UPDATE person SET name = :name, birth_date = :birth_date, gender = :gender WHERE id = :id');
            //on bind les value des placeholders
            $query->bindValue(':name',$pers->getName(),\PDO::PARAM_STR);
            
            $query->bindValue(':birth_date',$pers->getBirthdate()->format('Y-m-d'),\PDO::PARAM_STR);
            $query->bindValue(':gender',$pers->getGender(),
            \PDO::PARAM_INT);
            $query->bindValue(':id',$pers->getId(),
            \PDO::PARAM_INT);

            //on exécute la requête
            $query->execute();
            
            
        }catch(\PDOException $e) {
            echo $e;
        }
    }

    /**
     * La méthode delete supprimera une Person de la base de données en
     * se basant sur son id
     */
    public function delete(int $id) {
        
        try {
            //On prépare...
            $query = Connect::getInstance()->prepare('DELETE FROM person WHERE id = :id');
            //on bind...
            $query->bindValue(':id',$id,\PDO::PARAM_INT);

            //on exécute
            $query->execute();
            
            
        }catch(\PDOException $e) {
            echo $e;
        }
    }


}