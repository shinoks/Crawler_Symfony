<?php
namespace AppBundle\Utils;

use AppBundle\Entity\Temp;
use AppBundle\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TempUtils  extends Controller
{
    public function setNextToDownload($url=NULL,$page=1,$letter=NULL)
    {
        $em = $this->container->get('doctrine')->getManager();
        echo '1111';
            $category = $this->getDoctrine()
            ->getRepository('AppBundle:Categories')
            ->findOneById($id);
            echo '2222';
            
        $temp = $this->getDoctrine()
            ->getRepository('AppBundle:Temp')
            ->findOneById(1);
        echo '33333';
        $temp->setUrl($url);
        $temp->setPage($page);
        $temp->setLetter($letter);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($temp);
        $em->flush();
        $em->clear();
            
        return true;
    }
    
}
