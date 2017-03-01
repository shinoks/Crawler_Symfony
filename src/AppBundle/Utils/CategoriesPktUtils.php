<?php
namespace AppBundle\Utils;

use AppBundle\Entity\CategoriesPkt;
use AppBundle\Entity\Temp;
use AppBundle\Utils\TempUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Goutte\Client;


class CategoriesPktUtils  extends Controller
{
    public function getAddressToDownload($temp)
    {
            
        $client = new Client();
        $tempUtils = new TempUtils();
        $categories = new CategoriesPkt();
        $url = $temp->getUrl();
        $page = $temp->getPage();
        $letter = $temp->getLetter();
            echo 222;
            
        $address = 'http://www.biznesfinder.pl/mapa-branzy/'.$letter.'/'.$page;
        echo $address;
        
        $crawler = $client->request('GET', $address);
        $links = $crawler->filter('.text-bold')->extract(array('_text', 'href'));
        $count = count($links);
        
        if($count==0){
            $err == 1;
            $letter++;
            $page = 1;
        }else{
            foreach($links as $link){
                $categories->setName($link[0]);
                $categories->setAddress($link[1]);
                $categories->setDownloaded(0);
                //$em = $this->getDoctrine()->getManager();
                //$em->persist($categories);
                //$em->flush();
                //$em->clear();
                echo $link[0].'<- 0 '. $link[1].'<-1';
                echo '<br/>';
            }
            $page++;
        }
        $next = $tempUtils->setNextToDownload(NULL,$page,$letter);
        
        return $next;
    }
    
    
    
   
    
    
}