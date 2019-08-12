<?php
declare(strict_types=1);

namespace App\Controller;


use App\Repository\ParsedPageInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageInfoController extends AbstractController
{
    private $repository;

    public function __construct(ParsedPageInfoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $pageInfo = $this->repository->getAllOrderedByImages();
        return $this->render('pagesInfo.html.twig', [
            'data' => $pageInfo
        ]);
    }
}