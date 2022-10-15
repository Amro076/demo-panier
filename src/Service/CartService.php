<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $repo;
    private $rs;

    // injection de dépendances hors d'un conttroller : constructeur
    public function __construct(ProductRepository $repo, RequestStack $rs)
    {
        $this->repo = $repo;
        $this->rs = $rs;
    }
    public function add($id)
    {
        //nous allons récupèrer ou crée une session grace à la class RequestStack
        $session = $this->rs->getSession();

        $cart = $session->get('cart', []);
        //je recupére l'attr de session 'cart' s'il existe ou un tableau vide
        
        if(!empty($cart[$id]))
        {
            $cart[$id]++;
            // équivaut $cart[$id] = $cart[$id] + 1;
        }
        else
        {
            $cart[$id] = 1;
        }
       
        // dans mon tableau $cart, à la case $id, j'insére la valeur 1
        $session->set('cart', $cart);
        // je sauvegarde l'état de mon panier en session à l'attr de session 'cart'
        //dd($session->get('cart'));
    }
    public function remove($id)
    {
        $session =$this->rs->getSession();
        $cart = $session->get('cart',[]);
        
        // si l'id existe dans  $cart, je le supprime du tableau
        if (!empty($cart[$id]))
        {
            unset($cart[$id]);
        }
        
        $session->set('cart', $cart);

    }
    public function getCartWithData()
    {
        $session = $this->rs->getSession();
        $cart =$session->get('cart', []);
        //nous allons créeé un nouveau tableua qui contiendra des objet product et le quintité de chaque produit
        $cartWithData =[];

        foreach($cart AS $id => $quantity)
        {
            $cartWithData[] = [
                'product' => $this->repo->find($id),
                'quantity' => $quantity
            ];
        }
        return $cartWithData;
    }
    public function getTotal()
    {
        $cartWithData = $this->getCartWithData();
        $total = 0;
        foreach($cartWithData as $item)
        {
            
            $totalUnitaire = $item['product']->getPrice() * $item['quantity'];
            $total = $total + $totalUnitaire;
            // équivaut à $total += $totalUnitaire
        } 
        return $total;
    }
}