<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements
    UserInterface,
    PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $fullName;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=4096)
     * @var string The hashed password
     */
    private string $password;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserPreferention", mappedBy="users")
     */
    private UserPreferention $preferentions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UsersEntries", mappedBy="user")
     */
    private Collection $entry;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserWeightHistory", mappedBy="user")
     */
    private Collection $userWeightHistory;

    public function __construct(string $fullName, string $username, string $email)
    {
        $this->fullName = $fullName;
        $this->username = $username;
        $this->email = $email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPreferention(): ?UserPreferention
    {
        return $this->preferentions;
    }

    public function setPreferention($preferentions): void
    {
        $this->preferentions = $preferentions;
    }

    public function getEntries(): ?Collection
    {
        return $this->entry;
    }

    public function setEntries(Collection $entry): void
    {
        $this->entry = $entry;
    }

    public function getUerWeightHistory(): ?Collection
    {
        return $this->userWeightHistory;
    }

    public function setUerWeightHistory(Collection $userWeightHistory): void
    {
        $this->userWeightHistory = $userWeightHistory;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }
}
