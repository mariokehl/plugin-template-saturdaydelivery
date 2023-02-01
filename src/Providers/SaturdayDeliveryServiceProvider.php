<?php

namespace SaturdayDelivery\Providers;

use Illuminate\Database\Eloquent\Collection;
use IO\Helper\ResourceContainer;
use Plenty\Modules\Authorization\Services\AuthHelper;
use Plenty\Modules\Order\Contracts\OrderRepositoryContract;
use Plenty\Modules\Order\Events\OrderCreated;
use Plenty\Modules\Order\Models\Order;
use Plenty\Modules\Order\Property\Contracts\OrderPropertyRepositoryContract;
use Plenty\Modules\Order\Property\Models\OrderPropertyType;
use Plenty\Modules\Order\Shipping\Events\AfterShippingCostCalculated;
use Plenty\Modules\Webshop\Contracts\SessionStorageRepositoryContract;
use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\Log\Loggable;
use SaturdayDelivery\Helpers\OrderPropertyHelper;
use SaturdayDelivery\Helpers\SubscriptionInfoHelper;
use SaturdayDelivery\Session\SessionKey;

/**
 * Class SaturdayDeliveryServiceProvider
 * @package SaturdayDelivery\Providers
 */
class SaturdayDeliveryServiceProvider extends ServiceProvider
{
    use Loggable;

    /**
     * Original plentyShop LTS templates have a priority of 100. Any number less than 100 will indicate a higher priority.
     */
    const PRIORITY = 0;

    /**
     * Register the route service provider
     */
    public function register()
    {
        $this->getApplication()->register(SaturdayDeliveryRouteServiceProvider::class);
    }

    /**
     * @param Dispatcher $eventDispatcher
     * @return void
     */
    public function boot(Dispatcher $eventDispatcher)
    {
        $logger = $this->getLogger(__CLASS__);

        /** @var SessionStorageRepositoryContract $sessionRepo */
        $sessionRepo = pluginApp(SessionStorageRepositoryContract::class);

        // Session Handling
        $eventDispatcher->listen(AfterShippingCostCalculated::class, function (AfterShippingCostCalculated $event) use ($logger, $sessionRepo) {
            $additionalFee = $sessionRepo->getSessionValue(SessionKey::ADDITIONAL_FEE, 0.0);
            $event->addAdditionalFee($additionalFee);
            $logger->debug('SaturdayDelivery::Debug.SaturdayDeliveryServiceProvider_Event', ['additionalFee' => $additionalFee]);
        });

        // Order Properties
        $eventDispatcher->listen(OrderCreated::class, function (OrderCreated $event) use ($logger, $sessionRepo) {
            if ($sessionRepo->getSessionValue(SessionKey::ADDITIONAL_FEE, 0.0)) {
                /** @var Order $order */
                $order = $event->getOrder();

                /** @var OrderRepositoryContract $orderRepo */
                $orderRepo = pluginApp(OrderRepositoryContract::class);

                /** @var OrderPropertyRepositoryContract $orderPropertyRepository */
                $orderPropertyRepository = pluginApp(OrderPropertyRepositoryContract::class);

                /** @var AuthHelper $authHelper */
                $authHelper = pluginApp(AuthHelper::class);
                $orderPropertyTypes = $authHelper->processUnguarded(function () use ($orderPropertyRepository) {
                    return $orderPropertyRepository->getTypes(['en']);
                });
                $logger->debug('SaturdayDelivery::Debug.SaturdayDeliveryServiceProvider_OrderPropertyTypes', ['orderPropertyTypes' => $orderPropertyTypes]);

                $updatedOrder = $orderRepo->updateOrder([
                    'properties' => [
                        [
                            'typeId' => $this->getTypeIdByOrderPropertyName($orderPropertyTypes, OrderPropertyHelper::NAME_SURCHARGE), 'value' => (string)$sessionRepo->getSessionValue(SessionKey::ADDITIONAL_FEE, 0.0)
                        ],
                        [
                            'typeId' => $this->getTypeIdByOrderPropertyName($orderPropertyTypes, OrderPropertyHelper::NAME_SELECTED_DELIVERY_DAY), 'value' => (string)$sessionRepo->getSessionValue(SessionKey::SELECTED_DATE, null)
                        ]
                    ]
                ], $order->id);
                $logger->debug('SaturdayDelivery::Debug.SaturdayDeliveryServiceProvider_OrderCreated', ['updatedOrder' => $updatedOrder]);
            }

            // Cleanup
            $sessionRepo->setSessionValue(SessionKey::ADDITIONAL_FEE, 0.0);
            $sessionRepo->setSessionValue(SessionKey::SELECTED_DATE, null);
        });

        /** @var SubscriptionInfoHelper $subscription */
        $subscription = pluginApp(SubscriptionInfoHelper::class);
        if ($subscription->isPaid()) {
            // JS
            $eventDispatcher->listen('IO.Resources.Import', function (ResourceContainer $container) {
                $container->addScriptTemplate('SaturdayDelivery::content.Containers.Template.Script');
            }, self::PRIORITY);
        }
    }

    /**
     * @param Collection $orderPropertyTypes
     * @param string $name
     * @return int
     */
    private function getTypeIdByOrderPropertyName(Collection $orderPropertyTypes, string $name): int
    {
        /** @var OrderPropertyType $orderPropertyType */
        foreach ($orderPropertyTypes as $orderPropertyType) {
            if ($orderPropertyType->names[0]->name === $name) {
                return $orderPropertyType->id;
            }
        }
        return 0;
    }
}
