<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    //gives us createdat and updatedat fields
    use TimestampableEntity;

    //setting created at and updated at on creation to current date & time...
    public function __construct() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Sorry, you must give your Contact Message a subject!")
     */
    private $subject;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Sorry, you must provide your email address!")
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Sorry, you must include a Message!")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hospital", inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $hospital;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getHospital(): ?Hospital
    {
        return $this->hospital;
    }

    public function setHospital(?Hospital $hospital): self
    {
        $this->hospital = $hospital;

        return $this;
    }


    //add new updated time when updated...
    public function setUpdatedAtValue() {
        $this->setUpdatedAt(new \DateTime());
    }

}
