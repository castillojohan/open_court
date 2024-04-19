<?php

namespace App\Repository;

use App\Entity\Member;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Member>
 *
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[]    findAll()
 * @method Member[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function add(Member $member, bool $flush=false)
    {
        $this->getEntityManager()->persist($member);
        if($flush){
            $this->getEntityManager()->flush();
        }
    }

    /**
     * return only members who have + 18 years (for minors protection)
     *
     * @return void
     */
    public function getMemberForMessaging()
    {
        $dateNow = new DateTimeImmutable();
        $dateToCompare = $dateNow->modify('-18 years');
        return $this->createQueryBuilder('m')
            ->where('m.birthday < :birthdayDate')
            ->setParameter('birthdayDate', $dateToCompare)
            ->getQuery()
            ->getResult()
        ;
    }
//    /**
//     * @return Member[] Returns an array of Member objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Member
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
