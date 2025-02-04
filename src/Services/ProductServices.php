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

    public function uploadCover(Product $product, $coverFile): ?string
    {
        $uploadDirectory = __DIR__ . '/../../public/uploads';

        if ($coverFile) {
            // Retrieve old cover before updating
            $oldCover = $product->getCover();

            // Generate new filename
            $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename     = $this->slugger->slug($originalFilename);
            $newFilename      = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();

            try {
                $coverFile->move($uploadDirectory, $newFilename);

                // Delete old cover if it exists
                if ($oldCover && file_exists($uploadDirectory . '/' . $oldCover)) {
                    unlink($uploadDirectory . '/' . $oldCover);
                }

                // Update product cover
                $product->setCover($newFilename);
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
        $uploadDirectory = __DIR__ . '/../../public/uploads';

        if ($product->getCover() !== null) {
            $imagePath = $uploadDirectory . '/' . $product->getCover();

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }
}