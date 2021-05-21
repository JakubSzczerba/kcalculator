<?php
namespace App\DataFixtures;
use App\Entity\UserDaily;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserDailyFixtures extends Fixture 

{

    public function load(ObjectManager $manager): void
    {
        $this->loadDaily($manager);
       
    }

    public function loadDaily(ObjectManager $manager): void
    {

        foreach ($this->getDailyData() as [$sniadanie, $obiad, $kolacja, $przekaski]) {
            $userdaily = new UserDaily();
            $userdaily->setSniadanie($sniadanie);
            $userdaily->setObiad($obiad);
            $userdaily->setKolacja($kolacja);
            $userdaily->setPrzekaski($przekaski);

            $manager->persist($userdaily);
            // $this->addReference($username, $user);
        }

        $manager->flush();

    }

    public function getDailyData(): array
    {
        return [
            // $DailyData = [$sniadanie, $obiad, $kolacja, $przekaski];
            ['Awokado', 'Baranina, mięso bez kości', 'Bułka maślana', 'Czekoladki Raffaello'],
            ['Grejfrut', 'Indyk, piersi bez skóry', 'Pędy bambusa', 'Popcorn bez tłuszczu'],
            ['Ptyś z bitą śmietaną, ciastko', 'KFC, Twister', 'Pizza z pieczarkami i cebulą', 'Śledź w oleju, konserwa'],
        ];
    }



















}