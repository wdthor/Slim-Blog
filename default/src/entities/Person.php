<?php

namespace simplon\entities;

class Person {
    private $id;
    private $name;
    private $birthdate;
    private $gender;

    public function __construct(string $name,
                                \Datetime $birthdate,
                                int $gender,
                                int $id=null) {
        $this->id = $id;
        $this->name = $name;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
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
     * Get the value of name
     */ 
    public function getName():string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of birthdate
     */ 
    public function getBirthdate(): \Datetime
    {
        return $this->birthdate;
    }

    /**
     * Set the value of birthdate
     *
     * @return  self
     */ 
    public function setBirthdate(\Datetime $birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get the value of gender
     */ 
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     *
     * @return  self
     */ 
    public function setGender(int $gender)
    {
        $this->gender = $gender;

        return $this;
    }
}
