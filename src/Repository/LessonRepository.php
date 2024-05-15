<?php 

namespace App\Repository;

use App\Entity\Lesson;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

class LessonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent:: __construct($registry, Lesson::class);
    }

    // And then, can do what i want with DQL or QB , even raw SQL ->

    /**
     * Get next lessons timeslots
     */
    public function getNextLesson()
    {
        $closestWeekStartDate = (new \DateTime());
        $closestWeekEndDate = (new \DateTime())->modify('sunday this week');
        $qb = $this->createQueryBuilder('l');
        return $qb->select('l', 's')
            ->join('l.slots', 's')
            ->where('s.startAt >= :weekStartDate')
            ->andWhere('s.endAt <= :weekEndDate')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('l.gender', ':homme'),
                    $qb->expr()->eq('l.gender', ':femme'),
                    $qb->expr()->isNull('l.gender')
                )       
            )
            ->orderBy('s.startAt', 'ASC')
            ->setParameter('weekStartDate', $closestWeekStartDate->format('Y-m-d'))
            ->setParameter('weekEndDate', $closestWeekEndDate->format('Y-m-d'))
            ->setParameter('homme', false)
            ->setParameter('femme', true)
            ->getQuery()
            ->getResult()
        ;
    }
}