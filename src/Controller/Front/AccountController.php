<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Entity\User;
use App\Form\RegistrationType;
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
                $user->setEmail($allDatas['email'])
                        ->setPassword($plainPassword)
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

                // for some reason must use password hashing here, before he can't be verified by assert regex in entity, for now , if there is no error i can hash and save password
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
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
        $userId = $this->getUser();
        $currentUser = $userRepository->find($userId);
        $memberCollection = $memberRepository->findBy(['user'=>$userId]);
        foreach ($memberCollection as $member) {
            $member->getAge();
        }

        $sessionMember = $request->getSession()->get('member') !== null
            ? $memberRepository->find($request->getSession()->get('member')->getId())
            : null
        ;

        return $this->render('Front/account/account.html.twig', ['user'=>$currentUser->getUserIdentifier(), 'members'=> $memberCollection, 'currentMember'=>$sessionMember, "dateNow" => new DateTimeImmutable()]);
    }

    #[Route('/account/settings', name: 'app_settings', methods: ['GET', 'POST'])]
    public function userSettings(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager){
        $user = $userRepository->find($this->getUser());
        $form = $this->createForm(RegistrationType::class, $user);
        
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $plainPassword = $form->get('password')->getData();
                $confirmPassword = $form->get('confirmPassword')->getData();
                if( $plainPassword !== null && $confirmPassword !== null ){
                    if( $plainPassword !== $confirmPassword ){
                        return $this->render('/Front/settings.html.twig', ['form' => $form, 'errors' => 'Les mots de passe ne correspondent pas']);
                    }
                    $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
                }
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', "Modifications sur le compte Utilisateur, effectuées");
                return $this->redirectToRoute('app_account');
            }
        }
        return $this->render("/Front/account/settings.html.twig", ['form' => $form, 'errors' => '' ]);
    }

    #[Route('/account/delete', name: 'app_delete_user', methods: ['GET', 'POST'])]
    public function delete(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request)
    {
        // get current user
        $currentUser = $this->getUser();
        $user = $userRepository->find($currentUser);
        //get current member in session
        $currentMember = $request->getSession()->get('member');
        $members = $user->getMembers();
        
        if($currentMember->getUser()->getId() !== $user->getId() || $currentMember->getAge() < 18){
            throw $this->createAccessDeniedException("Une erreur est survenue, nous vous invitons à vous reconnecter");
        }
        
        if($request->isMethod('POST')){
            // invalidate token session
            $this->container->get("security.token_storage")->setToken(null);
            foreach($members as $member){
                $entityManager->remove($member);
            }
            $entityManager->remove($user);
            $entityManager->flush();
            // redirect to logout, which will redirect to app_main 
            return $this->redirectToRoute('app_logout');
        }
        
        return $this->render('/Front/account/confirmation.html.twig', ['user'=>$user, 'members'=>$members]);
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