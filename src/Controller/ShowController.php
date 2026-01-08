<?php

namespace App\Controller;

use App\Domain\Media\ShowRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/show', name: 'app_show')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ShowController.php',
        ]);
    }

    #[Route('/show/{id}', name: 'app_show_show')]
    public function show(
        int $id,
        ShowRepositoryInterface $showRepository,
    ): JsonResponse {
        return $this->json($showRepository->findById($id), headers: [
            'Access-Control-Allow-Origin' => '*', // TODO: this is a temp fix. we need a better solution
        ]);
    }
}
