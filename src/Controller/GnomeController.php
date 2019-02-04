<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gnome;
use App\Service\GnomeEntityCreator;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class GnomeController extends AbstractRESTController
{
    /**
     * @Rest\Get(name="get_gnomes", path="/gnomes")
     */
    public function getAllAction(): JsonResponse
    {
        $gnomes = $this
            ->getRepository(Gnome::class)
            ->findAll()
        ;

        if (!$gnomes) {
            return JsonResponse::create(null, JsonResponse::HTTP_NOT_FOUND);
        }

        $gnomes = $this->normalize($gnomes);

        return JsonResponse::create($gnomes);
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @Rest\Get(name="get_gnome", path="/gnomes/{id}")
     */
    public function getAction(int $id): JsonResponse
    {
        $gnome = $this
            ->getRepository(Gnome::class)
            ->find($id)
        ;

        if (!$gnome) {
            return JsonResponse::create(null, JsonResponse::HTTP_NOT_FOUND);
        }

        $gnome = $this->normalize($gnome);

        return JsonResponse::create($gnome);
    }

    /**
     * @param Request $request
     * @param GnomeEntityCreator $gnomeEntityCreator
     * @return JsonResponse
     *
     * @Rest\Post(name="post_gnomes", path="/gnomes")
     */
    public function createAction(Request $request, GnomeEntityCreator $gnomeEntityCreator): JsonResponse
    {
        $gnome = $gnomeEntityCreator->createFromPostRequest($request);
        $hasErrors = $this->validateEntity($gnome);
        if ($hasErrors) {
            return JsonResponse::create(null, JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->persistEntity($gnome);

        $headers = [
            'Location' => '/api/v1/gnomes/' . $gnome->getId(),
            'X-Resource-ID' => $gnome->getId(),
        ];

        return new JsonResponse($gnome, JsonResponse::HTTP_CREATED, $headers);
    }

    /**
     * @param Request $request
     * @param GnomeEntityCreator $gnomeEntityCreator
     * @param int $id
     * @return JsonResponse
     *
     * @Rest\Put(name="put_gnome", path="/gnomes/{id}")
     */
    public function updateAction(Request $request, GnomeEntityCreator $gnomeEntityCreator, int $id): JsonResponse
    {
        $gnome = $this->getRepository(Gnome::class)->find($id);
        if (!$gnome) {
            return JsonResponse::create(null, JsonResponse::HTTP_NOT_FOUND);
        }

        $gnome = $gnomeEntityCreator->createFromPutRequest($request, $gnome);
        $hasErrors = $this->validateEntity($gnome);
        if ($hasErrors) {
            return JsonResponse::create(null, JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->persistEntity($gnome);
        $gnome = $this->normalize($gnome);

        return JsonResponse::create($gnome);
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @Rest\Delete(name="delete_gnome", path="/gnomes/{id}")
     */
    public function deleteAction($id): JsonResponse
    {
        $gnome = $this->getRepository(Gnome::class)->find($id);
        if (!$gnome) {
            return JsonResponse::create(null, JsonResponse::HTTP_NOT_FOUND);
        }

        $this->removeEntity($gnome);

        return JsonResponse::create(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
