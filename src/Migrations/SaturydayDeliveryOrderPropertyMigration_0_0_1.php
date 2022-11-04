<?php

namespace SaturdayDelivery\Migrations;

use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;
use Plenty\Modules\Order\Property\Contracts\OrderPropertyRepositoryContract;
use Plenty\Modules\Order\Property\Models\OrderPropertyType;
use Plenty\Plugin\Log\Loggable;
use SaturdayDelivery\Models\SaturdayDeliveryOrderPropertyType;

/**
 * SaturydayDeliveryOrderPropertyMigration_0_0_1
 * @package SaturdayDelivery\Migrations
 */
class SaturydayDeliveryOrderPropertyMigration_0_0_1
{
    use Loggable;

    public function run(Migrate $migrate)
    {
        /** @var OrderPropertyRepositoryContract orderPropertyRepository */
        $orderPropertyRepository = pluginApp(OrderPropertyRepositoryContract::class);

        if (!$orderPropertyRepository->getType(SaturdayDeliveryOrderPropertyType::SURCHARGE)) {
            /** @var OrderPropertyType $surchargeType */
            $surchargeType = $orderPropertyRepository->createType([
                'cast' => 'string',
                'names' => [
                    ['lang' => 'de', 'name' => 'Samstagszustellung - Aufpreis'],
                    ['lang' => 'en', 'name' => 'Saturday Delivery - Surcharge']
                ]
            ]);
            $this->getLogger(__CLASS__)->debug('SaturdayDelivery::Debug.SaturydayDeliveryOrderPropertyMigration_OrderPropertyType', ['surchargeType' => $surchargeType]);
        }

        if (!$orderPropertyRepository->getType(SaturdayDeliveryOrderPropertyType::SELECTED_DELIVERY_DAY)) {
            /** @var OrderPropertyType $plannedDeliveryType */
            $plannedDeliveryType = $orderPropertyRepository->createType([
                'cast' => 'string',
                'names' => [
                    ['lang' => 'de', 'name' => 'Samstagszustellung - Ausgewählter Liefertag'],
                    ['lang' => 'en', 'name' => 'Saturday Delivery - Selected Delivery Day']
                ]
            ]);
            $this->getLogger(__CLASS__)->debug('SaturdayDelivery::Debug.SaturydayDeliveryOrderPropertyMigration_OrderPropertyType', ['plannedDeliveryType' => $plannedDeliveryType]);
        }
    }
}
