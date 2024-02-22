<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface;
#use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
#use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /*
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();
    }
    */

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sortiraleni');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('Etat', 'fa fa-hourglass-half', Etat::class);
        yield MenuItem::linkToCrud('Lieu', 'fa fa-location-dot', Lieu::class);
        yield MenuItem::linkToCrud('Participant', 'fa fa-user', Participant::class);
        yield MenuItem::linkToCrud('Site', 'fa fa-location-crosshairs', Site::class);
        yield MenuItem::linkToCrud('Sortie', 'fa fa-person-walking-arrow-right', Sortie::class);
        yield MenuItem::linkToCrud('Ville', 'fa fa-city', Ville::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
