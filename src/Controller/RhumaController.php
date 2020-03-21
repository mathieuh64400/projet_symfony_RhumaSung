<?php

namespace App\Controller;


use App\Entity\AdresseInfo;
use App\Entity\Article;
use App\Entity\InformationPersonelle;
// use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RhumaController extends AbstractController
{
    /**
     * @Route("/rhuma", name="rhuma")
     */
    public function index()
    {
        return $this->render('rhuma/index.html.twig', [
            'controller_name' => 'RhumaController',
        ]);
    }
     /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil()
    {    $repo=$this->getDoctrine()->getRepository(Article::class);
         $articles= $repo->findAll();
        return $this->render('rhuma/accueil.html.twig', [
            'controller_name' => 'RhumaController',
            'articles'=> $articles
        ]);
    }
    /**
     * @Route("/new", name="article_creation")
     */
    public function creationarticle(Request $request){
        $article= new Article();
        $formulairebis=$this->createFormBuilder($article)
                   ->add('titre')
                    ->add('volume')
                    ->add('image')
                    ->add('prix')
                    ->getForm();

        $formulairebis->handleRequest($request);
        dump($article);
        if($formulairebis->isSubmitted() && $formulairebis-> isValid())
        {
            $manager= $this->get("doctrine")->getManager();
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('rhuma/creat.html.twig', [
            'formPersobis' => $formulairebis->createView(),
        ]);
    
    }
     /**
     * @Route("/nous", name="nous")
     */
    public function nous()
    {
        return $this->render('rhuma/nous.html.twig', [
            'controller_name' => 'RhumaController',
        ]);
    }
    // --------------------------------------------------------------------
    // route création compte avec route: compte, creation_compte, connexion_compte
    // ----------------------------------------------------------------------
     /**
     * @Route("/compte", name="compte")
     */
    public function compte()
    {
        return $this->render('rhuma/compte.html.twig', [
            'controller_name' => 'RhumaController',
        ]);
    }
// -------------------------------------------------------------------------
// zone panier = route panier +route paiement commande + bulletin
// ---------------------------------------------------------------------------
     /**
     * @Route("/panier", name="panier")
     */
    public function panier()
    {
        return $this->render('rhuma/panier.html.twig', [
            'controller_name' => 'RhumaController',
        ]);
    }

    /**
     * @Route("/paiement_commande", name="commande_payer")
     */
    public function formcommande(Request $request){
        $infocom = new PaieCommande();
        $infocom->setDate(new\DateTime('now'));
        $formulairetrois=$this->createFormBuilder($infocom)
                         ->add('mode',ChoiceType::class,array(
                             'choices'=> array(
                                'carteBancaire' =>1,
                                'paypal' =>0,
                             ),
                             'expanded' =>true,
                             'multiple' => false
                             
                         ))
                         ->add('Nom')
                         ->add('Numero_CB')
                         ->add('date_expiration',DateType::class,[
                             'widget' => 'choice',
                         ])
                         ->add('cryptogramme')
                         ->add('adresse')
                         
                         ->getForm();
        $formulairetrois->handleRequest($request);
        dump($infocom);
        if($formulairetrois->isSubmitted() && $formulairetrois-> isValid())
        {
            $manager= $this->get("doctrine")->getManager();
            $manager->persist($formulairetrois->getData());
            $manager->flush();
                 
            return $this->redirectToRoute('profil');
        }
        return $this->render('rhuma/paiementcommande.html.twig', [
            'formpaiecommande' => $formulairetrois->createView(),
        ]);
    }
    

// -------------------------------------------------------------------------
// zone profil = route profil +route paiements + route historiqueCommandes + route connectionprofil+ adresse + info_personnelle
// ---------------------------------------------------------------------------
     
    /**
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        return $this->render('rhuma/profil.html.twig', [
            'controller_name' => 'RhumaController',
        ]);
    }
   
    /**
     * @Route("/paiements", name="paiements")
     */
    public function histopayement()
    {
        return $this->render('rhuma/historiquepayements.html.twig', [
            'controller_name' => 'RhumaController',
        ]);
    }
    /**
     * @Route("/historiqueCommandes", name="historiqueCommandes")
     */
    public function histoCommande()
    {
        return $this->render('rhuma/historiquecommandes.html.twig', [
            'controller_name' => 'RhumaController',
        ]);
    }
    /**
     * @Route("/connectionprofil", name="connectionprofil")
     */
    public function connecprofil()
    {
        return $this->render('rhuma/connectionprofil.html.twig', [
            'controller_name' => 'RhumaController',
        ]);
    }

     /**
     * @Route("/adresse", name="adresse")
     */
    public function formadress(Request $request){
        $donnes = new AdresseInfo();
        $formulaire=$this->createFormBuilder($donnes)
                         
                         ->add('Nom')
                         ->add('prenom')
                         ->add('pays',CountryType::class)
                        
                         ->add('type_adresse1',ChoiceType::class,array(
                            'choices'=> array(
                               'Principale' =>1,
                               'secondaire' =>0,
                            ),
                            'expanded' =>true,
                            'multiple' => false
                            
                        ))
                        ->add('adresse2')
                        ->add('type_adresse2',ChoiceType::class,array(
                            'choices'=> array(
                               'Principale' =>1,
                               'secondaire' =>0,
                            ),
                            'expanded' =>true,
                            'multiple' => false
                            
                        ))
                         ->add('code_postal')
                         ->add('ville')
                         ->getForm();

        $formulaire->handleRequest($request);
        dump($donnes);
        if($formulaire->isSubmitted() && $formulaire-> isValid())
        {
            $manager= $this->get("doctrine")->getManager();
            $manager->persist($donnes);
            $manager->flush();
                 
            return $this->redirectToRoute('profil');
        }
        return $this->render('rhuma/adresse.html.twig', [
            'formadresse' => $formulaire->createView(),
        ]);
    }
     /**
     * @Route("/info_personelle", name="info_personnelle")
     */
    public function infopersonelle(Request $request)
    {  
        $info= new InformationPersonelle();
        $form=$this->createFormBuilder($info)
                   ->add('password',PasswordType::class)
                    ->add('identifiant')
                    ->add('Nom')
                    ->add('prenom')
                    ->add('pays',CountryType::class)
                    ->add('telephone')
                    ->getForm();

        $form->handleRequest($request);
        dump($info);
        if($form->isSubmitted() && $form-> isValid())
        {
            $manager= $this->get("doctrine")->getManager();
            $manager->persist($info);
            $manager->flush();

            return $this->redirectToRoute('profil');
        }

        return $this->render('rhuma/informationPersonelle.html.twig', [
            'formPerso' => $form->createView(),
        ]);
    }
    
}
