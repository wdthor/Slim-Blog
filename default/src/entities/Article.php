<?php

namespace simplon\entities;

class Article {
    private $id;
    private $title;
    private $description;
    private $image;
    private $dateCreated;
    private $dateUpdated;


    public function __construct(string $title,
                                string $description,
                                int $idUser,
                                \Datetime $dateCreated,
                                string $image = null,
                                \Datetime $dateUpdated = null,
                                int $id=null) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->idUser = $idUser;
        $this->dateCreated = $dateCreated;
        $this->image = $image;
        $this->dateUpdated = $dateUpdated;
    }
    

    /**
     * Get the value of id
     */ 
    public function getId():int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle():string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription():string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of image
     */ 
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of dateCreated
     */ 
    public function getDateCreated(): \Datetime
    {
        return $this->dateCreated;
    }

    /**
     * Set the value of dateCreated
     *
     * @return  self
     */ 
    public function setDateCreated(\Datetime $dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }


    /**
     * Get the value of dateUpdated
     */ 
    public function getDateUpdated(): \Datetime
    {
        return $this->dateUpdated;
    }

    /**
     * Set the value of dateUpdated
     *
     * @return  self
     */ 
    public function setDateUpdated(\Datetime $dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getIdUser():int
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */ 
    public function setIdUser(int $idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

}
