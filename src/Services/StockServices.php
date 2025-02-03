<?php

namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StockServices
{
    private ProductRepository $productRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Decreases the stock of products in the cart.
     *
     * @param array $cart The cart array containing product IDs and their quantities.
     * @throws \Exception If any product has insufficient stock.
     */
    public function decreaseStock(array $cart): void
    {
        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepository->find($productId);

            if (!$product) {
                throw new \Exception("Product with ID $productId not found.");
            }

            if ($product->getStock() < $quantity) {
                throw new \Exception("Insufficient stock for product: " . $product->getName());
            }

            $product->setStock($product->getStock() - $quantity);
        }

        // Persist changes
        $this->entityManager->flush();
    }
}
