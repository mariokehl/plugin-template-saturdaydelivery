<?php

namespace SaturdayDelivery\Helpers;

use Plenty\Modules\Order\Property\Contracts\OrderPropertyRepositoryContract;
use Plenty\Modules\Order\Property\Models\OrderPropertyType;

/**
 * Class SubscriptionInfoHelper
 * @package SaturdayDelivery\Helpers
 */
class OrderPropertyHelper
{
    const NAME_SURCHARGE = 'Plugin SaturdayDelivery: Surcharge';
    const NAME_SELECTED_DELIVERY_DAY = 'Plugin SaturdayDelivery: Selected Delivery Day';

     /**
      * @param string $name use lang en
      * @return OrderPropertyType|bool
      */
    public function findByName(string $name)
    {
        /** @var OrderPropertyRepositoryContract $orderPropertyRepository */
        $orderPropertyRepository = pluginApp(OrderPropertyRepositoryContract::class);

        /** @var OrderPropertyType $orderPropertyType */
        foreach ($orderPropertyRepository->getTypes(['en']) as $orderPropertyType) {
            if ($orderPropertyType->names[0]->name === $name) {
                return $orderPropertyType;
            }
        }

        return false;
    }
}
