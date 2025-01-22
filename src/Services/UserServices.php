<?php
namespace App\Services;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServices
{

    public function __construct(private UserRepository $userRepository,private LoggerInterface $logger, private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher )
    {

    }

    public function handleUser(User $user, ?string $plainPassword)
    {
        try{
            if($user->getAge() < 18)
            {
                $user->setIsYoung(true);
            }
            else{
                $user->setIsYoung(false);
            }

            if($plainPassword)
            {
                $user->setPassword($this->passwordHasher->hashPassword($user,$plainPassword));
            }
    
            $this->entityManager->persist($user); // prepare
            $this->entityManager->flush();  //executre query

            return true;
        }
        catch(\Exception $exception)
        {
            $this->logger->log("error",$exception->getMessage());
            return false;
        }
       
    }

    public function getAllUsers():array
    {
        return $this->userRepository->findAll();
    }
    public function delete(User $user): void
    {
  
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }
}