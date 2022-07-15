<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function add(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findFavoritesForOwner(User $user): Collection
    {
        /** @var Contact[] $result */
        $result = $this->createQueryBuilder('c')
            ->where('c.owner = :owner')
            ->andWhere('c.is_favorite = :favorite')
            ->orderBy('c.created_at', 'DESC')
            ->setParameter('owner', $user)
            ->setParameter('favorite', true)
            ->getQuery()
            ->getResult();

        return new ArrayCollection($result);
    }

    public function search(User $user, string $query): array
    {
        /** @var Contact[] $result */
        $result = $this->createQueryBuilder('c')
            ->where('c.name LIKE :query')
            ->orWhere('c.surname LIKE :query')
            ->andWhere('c.owner = :owner')
            ->setParameter('query', "%${query}%")
            ->setParameter('owner', $user)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
