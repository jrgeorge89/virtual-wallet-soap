<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "transactions")]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entities\Customer")]
    #[ORM\JoinColumn(name: "customer_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private $customer;

    #[ORM\Column(type: "string")]
    private $type;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private $amount;

    #[ORM\Column(type: "string")]
    private $status = 'pendiente';

    #[ORM\Column(type: "string", nullable: true)]
    private $session_id;

    #[ORM\Column(type: "string", nullable: true)]
    private $confirmation_token;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $created_at;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $updated_at;

    // Getters y setters...

    public function getId()
    {
        return $this->id;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setSessionId($sessionId)
    {
        $this->session_id = $sessionId;
        return $this;
    }

    public function getSessionId()
    {
        return $this->session_id;
    }

    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmation_token = $confirmationToken;
        return $this;
    }

    public function getConfirmationToken()
    {
        return $this->confirmation_token;
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
}
