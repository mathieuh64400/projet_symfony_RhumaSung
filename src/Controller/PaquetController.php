<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use PhpParser\Node\Expr\Empty_;
use Symfony\Component\Routing\Annotation\Route;

class PaquetController extends AbstractController
{
    /**
     * @Route("/paquet", name="paquet")
     */
    public function index(SessionInterface $session, ArticleRepository $articleRepository)
    {
        $panier=$session->get('panier',[]);
        $panieravecdonnes=[];
        foreach ($panier as $id => $quantity) {
            $panieravecdonnes[] = [
                'article'=> $articleRepository->find($id),
                'quantity' => $quantity
            ];
        } 
        dump($panieravecdonnes);
        $total=0;
        foreach ($panieravecdonnes as $item) {
            $totalItem= $item['article']->getPrix() * $item['quantity'];
            $total+=$totalItem;
        }
        return $this->render('rhuma/panier.html.twig', [
            'items' => $panieravecdonnes,
            'total'=>$total
        ]);
    }
    /**
     * @Route("/panier/add/{id}", name="panier_add")
     */
    public function add($id, SessionInterface $session)
    {
       
        $panier=$session->get('panier',[]);
        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
          $panier[$id]=1;  
        }
        
        $session->set('panier',$panier);
        return $this->redirectToRoute("paquet");
    }
    /**
     * @Route("/panier/remove/{id}", name="panier_remove")
     */
    public function remove($id, SessionInterface $session){
        $panier=$session->get('panier',[]);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute("paquet");
    }


}
