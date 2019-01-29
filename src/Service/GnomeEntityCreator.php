<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Gnome;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class that I don't like
 *
 * @package App\Service
 */
class GnomeEntityCreator
{
    /**
     * @param Request $request
     * @return Gnome
     */
    public function createFromPostRequest(Request $request)
    {
        $data = $request->request;

        return (new Gnome())
            ->setName($data->get('name'))
            ->setAge((int)$data->get('age'))
            ->setStrength((int)$data->get('strength'))
            ->setAvatar($data->get('avatar'))
        ;
    }

    /**
     * @param Request $request
     * @param Gnome $gnome
     * @return Gnome
     */
    public function createFromPutRequest(Request $request, Gnome $gnome)
    {
        $data = $request->request;

        $gnome
            ->setName($data->get('name'))
            ->setAge((int)$data->get('age'))
            ->setStrength((int)$data->get('strength'))
            ->setAvatar($data->get('avatar'))
        ;

        return $gnome;
    }
}