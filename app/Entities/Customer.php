<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "customers")]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\Column(type: "bigint", unique: true)]
    private $document;

    #[ORM\Column(type: "string")]
    private $name;

    #[ORM\Column(type: "string", unique: true)]
    private $email;

    #[ORM\Column(type: "string")]
    private $phone;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2, options: ["default" => 0])]
    private $balance = 0;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $created_at;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $updated_at;

    // Getters y setters...

    public function getId()
    {
        return $this->id;
    }

    public function setDocument($document)
    {
        $this->document = $document;
        return $this;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function addBalance($amount)
    {
        $this->balance += $amount;
        return $this;
    }
}
