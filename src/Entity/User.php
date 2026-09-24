<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Identifiant technique généré par Doctrine.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Identifiant utilisé pour la connexion de l'utilisateur.
    #[ORM\Column(length: 256, nullable: true)]
    private ?string $username = null;

    // Mot de passe stocké sous forme hachée par le système d'authentification.
    #[ORM\Column(length: 256, nullable: true)]
    private ?string $password = null;

    // Rôle Symfony appliqué lorsque l'utilisateur est authentifié.
    #[ORM\Column(length: 256, nullable: true)]
    private ?string $role = null;

    // Numéro de téléphone associé au compte, disponible pour les notifications.
    #[ORM\Column(length: 256, nullable: true)]
    private ?string $phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRoles(): array
    {
        // Garantit un rôle utilisateur par défaut si aucun rôle n'est enregistré.
        return [$this->role ?? 'ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // Aucun secret temporaire n'est conservé par cette entité.
    }

    public function getUserIdentifier(): string
    {
        // Symfony utilise le nom d'utilisateur comme identifiant de sécurité.
        return (string) $this->username;
    }
}
