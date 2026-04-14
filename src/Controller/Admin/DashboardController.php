<?php

namespace App\Controller\Admin;


use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
{
    return $this->render('@EasyAdmin/page/content.html.twig');
}

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mpsonorisation');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fas fa-users');
        yield MenuItem::linkTo(MaterielCrudController::class, 'Matériel', 'fas fa-box');
        yield MenuItem::linkTo(CategorieCrudController::class, 'Catégories', 'fas fa-folder');
        yield MenuItem::linkTo(SousCategorieCrudController::class, 'Sous-catégories', 'fas fa-folder-open');
    }
}
