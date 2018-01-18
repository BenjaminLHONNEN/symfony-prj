<?php
/**
 * Created by PhpStorm.
 * User: elesa
 * Date: 13/10/2017
 * Time: 09:47
 */


namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function admin()
    {
        return $this->render('GameBundle:Game:admin.html.twig');
    }
}