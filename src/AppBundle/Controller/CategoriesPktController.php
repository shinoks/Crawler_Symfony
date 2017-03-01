<?php

namespace AppBundle\Controller;

use AppBundle\Utils\CategoriesPktUtils;
use AppBundle\Entity\CategoriesPkt;
use AppBundle\Entity\Temp;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Goutte\Client;

class CategoriesPktController extends Controller
{
    /**
     * @Route("/categoriespkt", name="categoriespkt")
     */
    public function categoriesAction()
    {
        $categories = $this->getDoctrine()
            ->getRepository('AppBundle:CategoriesPkt')
            ->findAll();

        if (!$categories) {
            throw $this->createNotFoundException(
                'No categories were found '
            );
        }
        
        
        return $this->render('default/categoriespkt.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'categories'=>$categories,
        ]);
    }
    
    /**
     * @Route("/categoriespkt_download",name="categoriespkt_download")
     */
     public function categoriesDownloadAction(Request $request)
     {
        $temp = $this->getDoctrine()
            ->getRepository('AppBundle:Temp')
            ->findOneById(1);
                    
        $client = new Client();
        $categories = new CategoriesPkt();
        $url = $temp->getUrl();
        $page = $temp->getPage();
        $letter = $temp->getLetter();
        $data = '';
        $address = 'http://www.biznesfinder.pl/mapa-branzy/'.$letter.'/'.$page;
        
        $crawler = $client->request('GET', $address);
        $links = $crawler->filter('.text-bold')->extract(array('_text', 'href'));
        $count = count($links);
        if($count==0 && $letter =='aa'){
            return $this->render('default/categoriespkt.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'url'=>$url,
            'page'=>$page,
            'letter'=>$letter,
            'address'=>$address
            ]);
        }else {
            if($count==0){
                $err = 1;
                $letter++;
                $page =1;
                echo 'FAIL KONIEC LITERY'.$letter-- ;
            }else{
                foreach($links as $link){
                    $categories->setName($link[0]);
                    $categories->setAddress($link[1]);
                    $categories->setDownloaded(0);
                    $categories->setPage(0);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($categories);
                    $em->flush();
                    $em->clear();
                    $data .= $link[0].' '. $link[1].'<br/>'; 
                }
                $page++;
            }
            
            $this->updateTempAction(1,$url,$page,$letter);
                    
            
            $page--;
            
            return $this->render('default/categoriespkt_download.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'url'=>$url,
                'page'=>$page,
                'letter'=>$letter,
                'address'=>$address,
                'data'=>$data,
            ]);
        }
     }
     
    private function updateTempAction($tempId,$url=NULL,$page=1,$letter=NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $temp = $em->getRepository('AppBundle:Temp')->find($tempId);

        if (!$temp) {
            throw $this->createNotFoundException(
                'No temp found for id '.$tempId
            );
        }

        $temp->setUrl($url);
        $temp->setPage($page);
        $temp->setLetter($letter);
        $em->flush();
        $em->clear();
        
        return $this->redirectToRoute('homepage');
    }
    
}
