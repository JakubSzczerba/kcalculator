<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\UserPreferention;
use App\Entity\User;

class ProfileController extends AbstractController
{
    /**
   * @Route("/profile", name="profile")
   */
  public function showProfile()
  {
    return $this->render('User/profile.html.twig', []);
  }


























}
