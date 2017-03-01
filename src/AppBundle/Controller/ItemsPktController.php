<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ItemsPkt;
use AppBundle\Entity\CategoriesPkt;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class ItemsPktController extends Controller
{
    /**
     * @Route("/itemspkt", name="itemspkt")
     */
    public function itemspktAction()
    {
        $items = $this->getDoctrine()
            ->getRepository('AppBundle:items')
            ->findAll();

        if (!$items) {
            throw $this->createNotFoundException(
                'No items were found '
            );
        }
        
        
        return $this->render('default/items.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'items'=>$items,
        ]);
    }
    
    
    /**
     * @Route("/itemspkt_download",name="itemspkt_download")
     */
     public function itemspktDownloadAction(Request $request)
     {
         $categorypkt = $this->getDoctrine()
            ->getRepository('AppBundle:CategoriesPkt')
            ->findOneByDownloaded(0);
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT COUNT(u) FROM AppBundle\Entity\CategoriesPkt u');
        $categoryCount = $query->getSingleScalarResult();
        
            //var_dump($categorypkt);
         $items = new ItemsPkt();
        $client = new Client();
        $craww = new Crawler();
        $client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1");
        
        $data = '';
        
        $categoryId = $categorypkt->getId();
        $address = 'http://www.biznesfinder.pl';
        $address .= $categorypkt->getAddress();
        $page = $categorypkt->getPage();
         
        
        if($page != 0){
            $address .=',p,'.$page.'/';
        }else if($page == 0){
            $page = 1;
        }
         echo $categoryId.' - '.$address.' - '.$page.'<br/>';
        $crawler = $client->request('GET', $address);
        
        $status_code = $client->getResponse()->getStatus();
        
        $wiz = $crawler->filter('.company highlight, .company');
            if(count($wiz)==0){
                $categorypkt->setDownloaded(1);
                $em = $this->getDoctrine()->getManager();
                $em->persist($categorypkt);
                $em->flush();
                $em->clear();
                
                echo 'Koniec tej litery';
                return $this->redirect($this->generateUrl('itemspkt_download', array('categoryId' => $categoryId,'page' => $page,)));
            }
             
            $ile = count($wiz);
            $html = [];
            
            foreach ($wiz as $domElement) {
                $html [] = $domElement->ownerDocument->saveHTML($domElement);
            }
            
            $crawler->clear();
            $em = $this->getDoctrine()->getManager();
            $it = [];
            foreach($html as $key=>$wizyt){
                $categorypkt = $this->getDoctrine()->getRepository('AppBundle:CategoriesPkt')->findOneByDownloaded(0);
                $craww = new Crawler();
                $craww->addHtmlContent($wizyt);
                $tele = $craww->filter('.trkname_shownumber')->extract(array('data-expanded'));
                $name = $craww->filter('.company-name')->extract(array('_text'));
                $email = $craww->filter('.trkname_clickemail')->extract(array('data-expanded'));
                
                if(isset($tele[0])){
                    $items->setName($name[0]);
                    $items->setTelephone($tele[0]);
                    (isset($email[0]))?$items->setEmail($email[0]):'';
                    
                    $items->setCategories($categorypkt);
                    $n = $name[0];
                    $t = $tele[0];
                    (isset($email[0]))?$it [] = ['name'=>$n,'tele'=>$t,'email'=>$email[0]]:$it [] = ['name'=>$n,'tele'=>$t,'email'=>''];
                    
                    $em->persist($items);
                    $em->persist($categorypkt);
                    $em->flush();
                    $em->clear();
                }
                $crawler->clear();
            }
            $pageNext = $page+1;
            $categoryPage = $this->getDoctrine()->getRepository('AppBundle:CategoriesPkt')->findOneById($categoryId);
            $categoryPage->setPage($pageNext);
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoryPage);
            $em->flush();
            $em->clear();
            $crawler->clear();
            
            
            return $this->render('default/itemspkt_download.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                    'data'=>$data,
                    'category'=>$categorypkt,
                    'address'=>$address,
                    'page'=>$page,
                    'categoryCount'=>$categoryCount,
                    'items'=>$it,
                ]);
        
     }
    
    
}
