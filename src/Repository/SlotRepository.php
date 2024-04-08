<?php

namespace App\Repository;

use App\Entity\Slot;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Slot>
 *
 * @method Slot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slot[]    findAll()
 * @method Slot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Slot::class);
    }

    public function findSlotsFromToday()
    {
        $dateOfTheDay = new DateTimeImmutable();
        $currentYear = $dateOfTheDay->format('Y');
        $currentMonth = $dateOfTheDay->format('m');
        $currentDay = $dateOfTheDay->format('d');
        $searchDate = new DateTimeImmutable("{$currentYear}-{$currentMonth}-{$currentDay} 07:00:00");
        return $this->createQueryBuilder('s')
            ->andWhere('s.startAt > :val')
            ->setParameter('val', $searchDate)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findWeeklySlots($memberId){
        $dateOfTheDay = new DateTimeImmutable();
        $weekStartAt = $dateOfTheDay->modify('monday this week');
        $weekEndAt = $dateOfTheDay->modify('sunday this week');
        $weekEndAt = $weekEndAt->modify('+1 day');
        $searchDates = [$weekStartAt, $weekEndAt];
        return $this->createQueryBuilder('s')
            ->andWhere('s.startAt BETWEEN :startAt AND :endAt')
            ->andWhere('s.memb IN (:member)')
            ->setParameters([
                    'startAt' => $searchDates[0],
                    'endAt' => $searchDates[1],
                    'member' => $memberId
                ])
            ->getQuery()
            ->getResult()
            ;
    }
//    /**
//     * @return Slot[] Returns an array of Slot objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Slot
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
