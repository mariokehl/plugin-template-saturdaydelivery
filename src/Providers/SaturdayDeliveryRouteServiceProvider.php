<?php

namespace SaturdayDelivery\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

/**
 * Class SaturdayDeliveryRouteServiceProvider
 * @package SaturdayDelivery\Providers
 */
class SaturdayDeliveryRouteServiceProvider extends RouteServiceProvider
{
    /**
     * @param Router $router
     */
    public function map(Router $router)
    {
        $router->post('plugin/saturday-delivery/select','SaturdayDelivery\Controllers\SaturdayDeliveryController@toggleSaturdayDelivery');
    }
}