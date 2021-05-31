<?php
namespace App\DataFixtures;
use App\Entity\UserPreferention;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserPreferentionFixtures extends Fixture 

{

    public function load(ObjectManager $manager): void
    {
        $this->loadPreferention($manager);
       
    }

  
    public function loadPreferention(ObjectManager $manager): void
    {

        foreach ($this->getPreferentionData() as [$gender, $weight, $height, $age, $activity, $caloric_requirement, $intentions, $kcal_day]) {
            $userpref = new UserPreferention();
            $userpref->setGender($gender);
            $userpref->setWeight($weight);
            $userpref->setHeight($height);
            $userpref->setAge($age);
            $userpref->setActivity($activity);
            $userpref->setKcal($caloric_requirement);
            $userpref->setIntentions($intentions);
            $userpref->setKcalDay($kcal_day);
            
            

            $manager->persist($userpref);
            // $this->addReference($username, $user);
        }

        $manager->flush();

    }


    public function getPreferentionData(): array
    {
        return [
            // $preferentionData = [$cel, $waga, $kcal];
            ['man', '85.6', '185', '25', 'activity_3', '2500', 'burn', '2300'],
            ['woman', '55.3', '162', '20', 'activity_1', '1800', 'keep', '1800'],
            ['man', '60', '182', '30', 'activity_1', '2250', 'gain', '3000'],
        ];
    }


    











}