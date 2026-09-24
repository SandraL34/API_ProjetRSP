<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

// Point d'entrée Symfony qui active la configuration MicroKernel du projet.
class Kernel extends BaseKernel
{
    // Ajoute à la classe les méthodes de chargement automatique de Symfony.
    use MicroKernelTrait;
}
