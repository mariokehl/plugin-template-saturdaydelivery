<?php

namespace SaturdayDelivery\Helpers;

use Plenty\Modules\PlentyMarketplace\Contracts\SubscriptionInformationServiceContract;
use Plenty\Modules\PlentyMarketplace\Models\SubscriptionOrderInformation;
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

        /** @var SubscriptionOrderInformation $subscriptionInfo */
        $subscriptionInfo = $subscriptionInfoService->getSubscriptionInfo('SaturdayDelivery');
        $this->getLogger(__METHOD__)->debug('SaturdayDelivery::Debug.SubscriptionInfoHelper_Subscription', ['subscriptionInfo' => $subscriptionInfo]);

        // Exception for my development system
        $pid = $this->plentyID();
        if ($pid === 58289) {
            $this->getLogger(__METHOD__)->info('SaturdayDelivery::Debug.SubscriptionInfoHelper_SubscriptionDev');
            return true;
        }

        // Check if user has paid and show warning in log if he hasn't
        $isPaid = $subscriptionInfoService->isPaid('SaturdayDelivery');
        if (!$isPaid) {
            $this->getLogger(__METHOD__)->warning('SaturdayDelivery::Debug.SubscriptionInfoHelper_Subscription', ['isPaid' => false]);
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
