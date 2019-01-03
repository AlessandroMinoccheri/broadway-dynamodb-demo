<?php

namespace App\Infrastructure\Delivery\Http;


use App\Application\Service\Post\CreatePostService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PostController
{
    /**
     * @Route("/posts", name="post_new", methods={"POST"})
     */
    public function create(Request $request, CreatePostService $createPostService)
    {
        $title = $request->get('title');
        $description = $request->get('description');

        $post = $createPostService->execute($title, $description);

        return new JsonResponse(['post' => $post], 201, array(
            'Content-Type' => 'application/json',
        ));
    }
}
