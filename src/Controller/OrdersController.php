<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\OrdersDetailsRepository;
use App\Repository\ProductRepository;
use App\Services\StockServices;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/orders', name: 'app_orders_')]
final class OrdersController extends AbstractController
{

    public function __construct( private StockServices $stockServices,private OrdersDetailsRepository $ordersDetailsRepository)
    {
    }

    #[Route('/add', name: 'add')]
    public function add(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cart = $session->get('cart', []);

        //cart is not vide on cree le order
        $order = new Orders();

        //remplit la commande
        $order->setUsers($this->getUser());
        $order->setCreatedAt(new \DateTimeImmutable()); // ✅ Use DateTimeImmutable instead of DateTime


        //parcourt cart to create details for orders

        foreach ($cart as $id => $quantity) {
            $orderDetails = new OrdersDetails();

            //serch product
            $product = $productRepository->find($id);

            $price = $product->getPrice();

            //create details orders
            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setStock($quantity);

            $order->addOrdersDetail($orderDetails);

        }
        // Decrease stock
        try {
            $this->stockServices->decreaseStock($cart);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Stock error: ' . $e->getMessage());
            return $this->redirectToRoute('app_cart');
        }
        //o persist et flush

        $entityManager->persist($order);
        $entityManager->flush();

        $session->remove('cart');

        //$this->addFlash('success', 'Order added successfully');
        return $this->redirectToRoute('app_cart');


    }

    #[Route('/details', name: 'details')]
    public function details(): Response {
       // dd($this->ordersDetailsRepository->findAll());
        $orders = $this->ordersDetailsRepository->findAll();

        return $this->render('orders/index.html.twig', [ "orders" =>$orders ]);

        /*return $this->render('shop/list.html.twig', [
            'products' => $productRepository->findAll()
        ]);*/
    }
}
