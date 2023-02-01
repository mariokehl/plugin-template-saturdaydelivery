<?php

namespace SaturdayDelivery\Migrations;

use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;
use Plenty\Modules\Order\Property\Contracts\OrderPropertyRepositoryContract;
use Plenty\Modules\Order\Property\Models\OrderPropertyType;
use Plenty\Plugin\Log\Loggable;
use SaturdayDelivery\Helpers\OrderPropertyHelper;

/**
 * SaturydayDeliveryOrderPropertyMigration_0_0_4
 * @package SaturdayDelivery\Migrations
 */
class SaturydayDeliveryOrderPropertyMigration_0_0_4
{
    use Loggable;

    public function run(Migrate $migrate)
    {
        /** @var OrderPropertyRepositoryContract $orderPropertyRepository */
        $orderPropertyRepository = pluginApp(OrderPropertyRepositoryContract::class);

        $orderPropertyTypes = $orderPropertyRepository->getTypes(['en']);
        $this->getLogger(__CLASS__)->debug('SaturdayDelivery::Debug.SaturydayDeliveryOrderPropertyMigration_OrderPropertyTypes', ['orderPropertyTypes' => $orderPropertyTypes]);

        /** @var OrderPropertyHelper $orderPropertyHelper */
        $orderPropertyHelper = pluginApp(OrderPropertyHelper::class);

        if (!$orderPropertyHelper->findByName(OrderPropertyHelper::NAME_SURCHARGE)) {
            /** @var OrderPropertyType $surchargeType */
            $surchargeType = $orderPropertyRepository->createType([
                'cast' => 'string',
                'names' => [
                    ['lang' => 'de', 'name' => 'Plugin Samstagszustellung: Aufpreis'],
                    ['lang' => 'en', 'name' => OrderPropertyHelper::NAME_SURCHARGE]
                ]
            ]);
            $this->getLogger(__CLASS__)->debug('SaturdayDelivery::Debug.SaturydayDeliveryOrderPropertyMigration_OrderPropertyType', ['surchargeType' => $surchargeType]);
        }

        if (!$orderPropertyHelper->findByName(OrderPropertyHelper::NAME_SELECTED_DELIVERY_DAY)) {
            /** @var OrderPropertyType $plannedDeliveryType */
            $plannedDeliveryType = $orderPropertyRepository->createType([
                'cast' => 'string',
                'names' => [
                    ['lang' => 'de', 'name' => 'Plugin Samstagszustellung: Ausgewählter Liefertag'],
                    ['lang' => 'en', 'name' => OrderPropertyHelper::NAME_SELECTED_DELIVERY_DAY]
                ]
            ]);
            $this->getLogger(__CLASS__)->debug('SaturdayDelivery::Debug.SaturydayDeliveryOrderPropertyMigration_OrderPropertyType', ['plannedDeliveryType' => $plannedDeliveryType]);
        }
    }
}
