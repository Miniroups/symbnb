<?php 

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService {
    private $entityClass;       // Représente l'entity qui est concerné par la pagination
    private $limit = 10;        // Limite d'éléments à afficher par pages
    private $currentPage = 1;   // Page courante par défaut à 1
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath) {
        $this->route        = $request->getCurrentRequest()->attributes->get('_route'); // Récupère le nom de la route
        $this->manager      = $manager;
        $this->twig         = $twig;
        $this->templatePath = $templatePath;
    }

    public function display() {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route,
            ]);
    }

    public function getPages() {
        if(empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle vous souhaitez paginer ! Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }
        // 1) Connaitre le total des enregistrements de la table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        // 2) Faire la division, l'arrondi et le renvoyer
        $pages = ceil($total / $this->limit);
        
        return $pages;
    }
    
    public function getData() {
        if(empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle vous souhaitez paginer ! Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }
        // 1) Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        // 2) Demander au repository de trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
        // 3) Renvoyer les éléments en question
        return $data;
    }
    
    ///////////////////////////////
    public function setPage($currentPage) {
        $this->currentPage = $currentPage;
        return $this;
    }
    public function getPage() {
        return $this->currentPage;
    }
    
    ///////////////////////////////
    public function setLimit($limit) {
        $this->limit = $limit;
        return $this;
    }
    public function getLimit() {
        return $this->limit;
    }
    
    ///////////////////////////////
    public function setEntityClass($entityClass) {
        $this->entityClass = $entityClass;
        return $this;
    }
    public function getEntityClass() {
        return $this->entityClass;
    }

    ///////////////////////////////
    public function setTemplatePath($templatePath) {
        $this->templatePath = $templatePath;
        return $this;
    }
    public function getTemplatePath() {
        return $this->templatePath;
    }
    
    ///////////////////////////////
    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }
    public function getRoute() {
        return $this->route;
    }
}