<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\User\Entity;

use Doctrine\Common\Collections\Collection;
use Kcalculator\Domain\Preference\Entity\Preference;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements
    UserInterface,
    PasswordAuthenticatedUserInterface
{
    private $id;

    private string $fullName;

    private string $username;

    private string $email;

    private string $password;

    private array $roles = [];

    private Preference $preference;

    private Collection $entry;

    private Collection $weightHistory;

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

    public function getPreference(): ?Preference
    {
        return $this->preference;
    }

    public function setPreference($preference): void
    {
        $this->preference = $preference;
    }

    public function getEntries(): ?Collection
    {
        return $this->entry;
    }

    public function setEntries(Collection $entry): void
    {
        $this->entry = $entry;
    }

    public function getWeightHistory(): ?Collection
    {
        return $this->weightHistory;
    }

    public function setUserWeightHistory(Collection $weightHistory): void
    {
        $this->weightHistory = $weightHistory;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
