<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\User\Entity;

use Doctrine\Common\Collections\Collection;
use Kcalculator\Domain\Preference\Entity\UserPreference;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User implements
    UserInterface,
    PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;

    #[ORM\Column(type: "string", nullable: false)]
    private string $fullName;

    #[ORM\Column(type: "string", length: 128, unique: true, nullable: false)]
    private string $username;

    #[ORM\Column(type: "string", length: 128, unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(type: "string", length: 4096, unique: true, nullable: false)]
    private string $password;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\OneToOne(mappedBy: "users", targetEntity: "Kcalculator\Domain\Preference\Entity\UserPreference")]
    private UserPreference $preferentions;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: "Kcalculator\Domain\Entry\Entity\UserEntry")]
    private Collection $entry;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: "Kcalculator\Domain\WeightHistory\Entity\UserWeightHistory")]
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

    public function getPreferention(): ?UserPreference
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
        return $this->username;
    }
}
