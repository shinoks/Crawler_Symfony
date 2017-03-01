<?php
namespace AppBundle\Utils;

use AppBundle\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoriesUtils  extends Controller
{
    public function getCategoryToDownload($id = NULL)
    {
        if(!empty($id)){
            $category = $this->getDoctrine()
            ->getRepository('AppBundle:Categories')
            ->findOneById($id);
        }else {
            $category = $this->getDoctrine()
            ->getRepository('AppBundle:Categories')
            ->findOneByDownloaded(0);
        }
        
        if (!$category) {
            throw $this->createNotFoundException(
                'No category were found '
            );
        }
        
        return $category;
    }
    public function getCategoryCount()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT COUNT(u) FROM AppBundle\Entity\Categories u');
        $count = $query->getSingleScalarResult();
        
        return $count;
        
    }
    
    
}