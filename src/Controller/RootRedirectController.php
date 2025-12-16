<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RootRedirectController extends AbstractController
{
    public function __invoke(): RedirectResponse
    {
        return new RedirectResponse('/docs-ui');
    }
}
