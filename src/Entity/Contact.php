<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $birthday = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $job_title = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $organization = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $department = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(type: 'array')]
    private array $phone_numbers = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private array $emails = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private array $social_networks = [];

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'contacts')]
    private Collection $groups;

    #[ORM\Column(type: 'boolean', options: [
        'default' => false,
    ])]
    private bool $is_favorite = false;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeImmutable $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->job_title;
    }

    public function setJobTitle(?string $job_title): self
    {
        $this->job_title = $job_title;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(?string $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhoneNumbers(): ?array
    {
        return $this->phone_numbers;
    }

    public function setPhoneNumbers(array $phone_numbers): self
    {
        $this->phone_numbers = $phone_numbers;

        return $this;
    }

    public function getEmails(): ?array
    {
        return $this->emails;
    }

    public function setEmails(?array $emails): self
    {
        $this->emails = $emails;

        return $this;
    }

    public function getSocialNetworks(): ?array
    {
        return $this->social_networks;
    }

    public function setSocialNetworks(?array $social_networks): self
    {
        $this->social_networks = $social_networks;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (! $this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addContact($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeContact($this);
        }

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->is_favorite;
    }

    public function setIsFavorite(bool $is_favorite): self
    {
        $this->is_favorite = $is_favorite;

        return $this;
    }
}
