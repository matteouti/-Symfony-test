<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use MongoDB\Driver\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
final class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);

        // on fabrique les données
        $dataCart = [];
        $total = 0;

        foreach ($cart as $di => $stock) {
            $product        = $productRepository->find($di);
           // $stockAvailable = $product->getStock();

            $dataCart[] = [
                'product' => $product,
                'quantity' => $stock,
            ];

            $total += $stock * $product->getPrice();
        }

        return $this->render('cart/index.html.twig', compact("dataCart", "total"));
    }
    /*
     * #[Route('/add/{id}', name: 'add')]
     * */
   // #[Route('/add/{id}', name: 'add')]
    #[Route('/add/{id}', name: 'cart_add')]
    public function  add(Product $product,$id, sessionInterface $session)
    {

        //recupère le panier actuel
        $cart = $session->get('cart',[]);
        $id =  $product->getId();

        $stockAvailable = $product->getStock();

        // Ensure `$cart` is an array
        if (!is_array($cart)) {
            $cart = [];
        }

       if(!empty($cart[$id])){
           $cart[$id]++;
       }else{
           $cart[$id] = 1;
       }

       //sauvegarde dans la session
        $session->set('cart', $cart);

       return $this->redirectToRoute('app_cart');
    }

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function  remove(Product $product,$id, sessionInterface $session)
    {

        //recupère le panier actuel
        $cart = $session->get('cart',[]);
        $id =  $product->getId();

        // Ensure `$cart` is an array
        if (!is_array($cart)) {
            $cart = [];
        }

        if(!empty($cart[$id])){
            if($cart[$id]>1)
            {
                $cart[$id]--;
            }
            else{
                unset($cart[$id]);
            }
        }

        //sauvegarde dans la session
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/delete/{id}', name: 'cart_delete')]
    public function  delete(Product $product,$id, sessionInterface $session)
    {

        //recupère le panier actuel
        $cart = $session->get('cart',[]);
        $id =  $product->getId();

        // Ensure `$cart` is an array
        if (!is_array($cart)) {
            $cart = [];
        }

        if(!empty($cart[$id])){
           unset($cart[$id]);
        }

        //sauvegarde dans la session
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/delete', name: 'cart_delete_all')]
    public function  deleteAll(sessionInterface $session)
    {
        $session->remove('cart');
        return $this->redirectToRoute('app_cart');
    }
}
