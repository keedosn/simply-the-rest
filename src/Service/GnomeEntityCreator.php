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
        $gnome = new Gnome();
        $gnome
            ->setName($request->get('name'))
            ->setAge((int)$request->get('age'))
            ->setStrength((int)$request->get('strength'))
            ->setAvatar($request->get('avatar'))
        ;

        return $gnome;
    }

    /**
     * @param Request $request
     * @param Gnome $gnome
     * @return Gnome
     */
    public function createFromPutRequest(Request $request, Gnome $gnome)
    {
        $gnome
            ->setName($request->request->get('name'))
            ->setAge((int)$request->request->get('age'))
            ->setStrength((int)$request->request->get('strength'))
            ->setAvatar($request->request->get('avatar'))
        ;

        return $gnome;
    }
}