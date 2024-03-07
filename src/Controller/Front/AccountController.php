<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Entity\User;
use App\Repository\MemberRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccountController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function userRegistration(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $member = new Member();

        if($request->isMethod('POST')){
            $allDatas = $request->getPayload()->all();
            $birthdayDate = new DateTimeImmutable($allDatas['birthday']); 
            $submittedToken = $request->getPayload()->get('_token');
            
            if($this->isCsrfTokenValid('registration', $submittedToken)){
                $plainPassword = $allDatas['password'];
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setEmail($allDatas['email'])
                        ->setPassword($hashedPassword)
                        ->addMember($member)
                        ->setRoles(['ROLE_USER']);
                $member->setFirstName($allDatas['firstname'])
                        ->setLastName($allDatas['lastname'])
                        ->setBirthday($birthdayDate)
                        ->setPinCode($allDatas['pincode'])
                        ->setGender($allDatas['gender']);

                $errorsMember = $validator->validate($member);
                $errorsUser = $validator->validate($user);
                
                if(count($errorsMember) > 0 || count($errorsUser) > 0 ){
                    $errorsMessagesUser = $this->manageError($errorsUser);
                    $errorsMessagesMember = $this->manageError($errorsMember);
                    $errors = array_merge($errorsMessagesUser, $errorsMessagesMember);
                    return $this->render('Front/register.html.twig', ['errors'=> $errors, 'last_username'=> '']);
                }

                // Password verification management
                if($plainPassword !== $allDatas['confirmPassword']){
                    return $this->render('Front/register.html.twig', ['errors' => ['Mot de passe' => 'Les mots de passe doivent correspondre'], 'last_username'=> '']);
                }

                if(!isset($allDatas['acceptCGU'])){
                    return $this->render('Front/register.html.twig', ['errors' => ['CGU'=>'Vous devez accepter les CGU'], 'last_username'=> '']);
                }
                $entityManager->persist($user);
                $entityManager->persist($member);
                $entityManager->flush();
                
                $this->addFlash('success', 'Création du compte réalisé avec succès');

                return $this->render('Front/home.html.twig', ['last_username'=> '']);
            }
        }
        return $this->render('Front/register.html.twig', ['last_username' => '','errors' => '']);
    }

    #[Route('/account', name: 'app_account', methods: 'GET')]
    public function userAccount(UserRepository $userRepository, MemberRepository $memberRepository, Request $request): Response
    {
        $userId =  $this->getUser()->getId();
        $currentUser = $userRepository->find($userId);
        $userMail = $currentUser->getEmail();
        $memberCollection = $memberRepository->findBy(['user'=>$userId]);
        foreach ($memberCollection as $member) {
            $member->getAge();
        }
        
        $this->addFlash('success', "Bienvenue, $userMail");

        return $this->render('Front/account.html.twig', ['members'=> $memberCollection, 'session'=>$request->getSession(), "dateNow" => new DateTimeImmutable()]);
    }

    /**
     * Error Management for multiple form who need multiple validations
     *
     * @param [array] $errorsList
     * @return Array
     */
    public function manageError($errorsList):Array
    {
        $errorsMessages = [];
        foreach ($errorsList as $error) {
            $errorsMessages[$error->getPropertyPath()] = $error->getMessage();
        }
        return $errorsMessages;
    }
}