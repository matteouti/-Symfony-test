<?php
namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductServices
{
   
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger
        
    ) {
    }

    public function uploadCover($coverFile): ?string
    {
        if ($coverFile) {

            $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename     = $this->slugger->slug($originalFilename);
            $newFilename      = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();

            try {
                $coverFile->move(
                    __DIR__ . '/../../public/uploads',
                    $newFilename
                );
            } catch (FileException $e) {
                return null;
            }

            return $newFilename;
        }

        return null;
    }

    public function save(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function delete(Product $product): void
    {
  
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }
}