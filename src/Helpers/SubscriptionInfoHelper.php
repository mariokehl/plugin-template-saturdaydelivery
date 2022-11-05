<?php

namespace SaturdayDelivery\Helpers;

use Plenty\Modules\PlentyMarketplace\Contracts\SubscriptionInformationServiceContract;
use Plenty\Plugin\Application;
use Plenty\Plugin\Log\Loggable;

/**
 * Class SubscriptionInfoHelper
 * @package SaturdayDelivery\Helpers
 */
class SubscriptionInfoHelper
{
    use Loggable;

    /**
     * @return boolean
     */
    public function isPaid(): bool
    {
        /** @var SubscriptionInformationServiceContract $subscriptionInfoService */
        $subscriptionInfoService = pluginApp(SubscriptionInformationServiceContract::class);
        $this->getLogger(__METHOD__)->debug('SaturdayDelivery::Plenty.Subscription', [
            'subscriptionInfo' => $subscriptionInfoService->getSubscriptionInfo('SaturdayDelivery')
        ]);

        // Exception for my development system
        $pid = $this->plentyID();
        if ($pid === 58289) {
            $this->getLogger(__METHOD__)->info('SaturdayDelivery::Plenty.SubscriptionDev');
            return true;
        }

        // Check if user has paid and show warning in log if he hasn't
        $isPaid = $subscriptionInfoService->isPaid('SaturdayDelivery');
        if (!$isPaid) {
            $this->getLogger(__METHOD__)->warning('SaturdayDelivery::Plenty.Subscription', ['isPaid' => false]);
        }

        return $isPaid;
    }

    /**
     * @return integer
     */
    public function plentyID(): int
    {
        /** @var Application $application */
        $application = pluginApp(Application::class);

        return $application->getPlentyId();
    }
}
