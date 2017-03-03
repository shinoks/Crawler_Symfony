<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Items;
use AppBundle\Entity\Categories;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Goutte\Client;
use AppBundle\Utils\ItemsUtils;
use AppBundle\Utils\CategoriesUtils;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\Response;

class ItemsController extends Controller
{
    /**
     * @Route("/items", name="items")
     */
    public function categoriesAction()
    {
        $html = ''; 
        
        $em = $this->getDoctrine()->getManager();
        $q =$em->createQuery('SELECT u from AppBundle\Entity\Items u ');
        $iterableResult = $q->iterate();
        
        foreach ($iterableResult as $row) {
            $file = 'items/bazafirm/baza-firm-'.$row[0]->getCategories()->getName().'.csv';
            $html = $row[0]->getName().';'.$row[0]->getTelephone().';'.$row[0]->getEmail().';'."\r\n";
            file_put_contents($file, $html, FILE_APPEND | LOCK_EX);
            $em->detach($row[0]);
            $cat =['name'=>$row[0]->getCategories()->getName(),'file'=>'items/bazafirm/baza-firm-'.$row[0]->getCategories()->getName().'.csv'];
        }
        
        return $this->render('default/items.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'cat'=>$cat,
        ]);
    }
    
    
    /**
     * @Route("/items_download",name="items_download")
     */
     public function itemsDownloadAction(Request $request)
     {
        $categoryUtils = CategoriesUtils::getCategoryToDownload();
        $categoryCount = CategoriesUtils::getCategoryCount();
        $category = $this->getDoctrine()
            ->getRepository('AppBundle:Categories')
            ->findOneByDownloaded(0);
        
        $items = new Items();
        $client = new Client();
        $craww = new Crawler();
        $data = '';
            $categoryId = $category->getId();
            $address = $category->getAddress();
            $page = $category->getPage();
            if($page != 0){
                $address .='strona-'.$page.'/';
            }else if($page == 0){
                $page = 1;
            }
            
            $crawler = $client->request('GET', $address);
            $wiz = $crawler->filter('.mwiz_std,.mwiz_wyr');
            if(count($wiz)==0){
                $category->setDownloaded(1);
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();
                $em->clear();
                
                
                return $this->redirect($this->generateUrl('items_download', array('categoryId' => $categoryId,'page' => $page,)));
                
            }else {
                $key = 0;
                $ile = count($wiz);
                $html = [];
                
                foreach ($wiz as $domElement) {
                    $html [] = $domElement->ownerDocument->saveHTML($domElement);
                }
                $crawler->clear();
                $em = $this->getDoctrine()->getManager();
                foreach($html as $key=>$wizyt){
                    $craww->addHtmlContent($wizyt);
                    $tele = $craww->filter('.divSMV_tel1.clearBoth > div')->extract(array('_text', 'value'));
                    
                    $name = $craww->filter('.przeppoz')->extract(array('_text', 'value'));
                    
                    foreach($tele as $telephone){
                        $category = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneByDownloaded(0);
                        //$catClone = clone $category;
                        $telefon = str_replace('tel.','',$telephone[0]);
                        $telefon = str_replace('tel','',$telefon);
                        $telefon = str_replace('tel./fax','',$telefon);
                        $telefon = str_replace('fax.','',$telefon);
                        $telefon = str_replace('fax','',$telefon);
                        $telefon = str_replace(' ','',$telefon);
                        $telefon = str_replace('kom.','',$telefon);
                        $telefon = str_replace('/','',$telefon);
                        $telefon = str_replace('infolinia','',$telefon);
                        $data .=  $name[0][0].' - '.$telefon.'<br/>';
                        if(!empty($telefon)){
                            $items->setTelephone($telefon);
                            $items->setName($name[0][0]);
                            $items->setCategories($category);
                            
                            try {
                                //$em->detach($category);
                                $em->persist($items);
                                $em->persist($category);
                            }
                            catch(Exception $e) {
                              echo 'Message: ' .$e->getMessage();
                            }
                        
                            try {
                                $em->flush();
                            }
                            
                            catch(Exception $e) {
                                          echo 'Message: ' .$e->getMessage();
                            }
                            $em->clear();
                            
                        }
                        $craww->clear();
                    }
                    $craww->clear();
                    
                }
            $pageNext = $page+1;
            $categoryPage = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneById($categoryId);
            $categoryPage->setPage($pageNext);
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoryPage);
            $em->flush();
            $em->clear();
            
            return $this->render('default/items_download.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'data'=>$data,
                'category'=>$category,
                'categoryCount'=>$categoryCount,
                'address'=>$address,
                'page'=>$page,
            ]);
         }
     }
     
}
