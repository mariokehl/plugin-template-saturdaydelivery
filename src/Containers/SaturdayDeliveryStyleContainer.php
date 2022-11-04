<?php

namespace SaturdayDelivery\Containers;

use Plenty\Plugin\Templates\Twig;

/**
 * Class SaturdayDeliveryStyleContainer
 * @package SaturdayDelivery\Containers
 */
class SaturdayDeliveryStyleContainer
{
    public function call(Twig $twig): string
    {
        return $twig->render('SaturdayDelivery::content.Style');
    }
}
