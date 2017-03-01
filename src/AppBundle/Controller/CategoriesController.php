<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categories;
use AppBundle\Utils\CategoriesUtils;
use AppBundle\Form\CategoriesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Goutte\Client;

class CategoriesController extends Controller
{
    /**
     * @Route("/categories", name="categories")
     */
    public function categoriesAction()
    {
        $categories = $this->getDoctrine()
            ->getRepository('AppBundle:Categories')
            ->findAll();

        if (!$categories) {
            throw $this->createNotFoundException(
                'No categories were found '
            );
        }
        
        
        return $this->render('default/categories.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'categories'=>$categories,
        ]);
    }
    
    /**
     * @Route("/categories_add", name="categories_add")
     */
    public function categoriesAddAction(Request $request)
    {
        $categories = new Categories();
        
        $info = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();
            $info = true;
        }
        
        return $this->render('default/categories_add.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'=>$form->createView(),
        ]);
    }
    
    /**
     * @Route("/categories_download",name="categories_download")
     */
     public function categoriesDownloadAction(Request $request)
     {
        $categories = new Categories();
         
        $client = new Client();
        $crawler = $client->request('GET', 'http://baza-firm.com.pl/katalog-branz-pl');
        $links = $crawler->filter('.ver12')->extract(array('_text', 'href'));
        
        foreach($links as $link){
            $categories->setName($link[0]);
            $categories->setAddress($link[1]);
            $categories->setDownloaded(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();
            $em->clear();
            
            echo $link[0].'<- 0 '. $link[1].'<-1';
            echo '<br/>';
        }
        
        return $this->render('default/categories_download.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
     }
    
    
}
