<?php

namespace SaturdayDelivery\Containers;

use Carbon\Carbon;
use IO\Extensions\Filters\NumberFormatFilter;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Webshop\Contracts\SessionStorageRepositoryContract;
use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Log\Loggable;
use Plenty\Plugin\Templates\Twig;
use SaturdayDelivery\Helpers\SubscriptionInfoHelper;
use SaturdayDelivery\Session\SessionKey;

/**
 * Class SaturdayDeliveryDatePickerContainer
 * @package SaturdayDelivery\Containers
 */
class SaturdayDeliveryDatePickerContainer
{
    use Loggable;

    /**
     * The maximum number of dates that can be put out
     */
    const MAX_POSSIBLE_DATES = 7;

    /**
     * Renders the template.
     * 
     * @param Twig $twig The twig instance
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function call(Twig $twig): string
    {
        /** @var ConfigRepository $configRepo */
        $configRepo = pluginApp(ConfigRepository::class);

        /** @var SubscriptionInfoHelper $subscription */
        $subscription = pluginApp(SubscriptionInfoHelper::class);
        if (!$subscription->isPaid()) {
            return '';
        }

        $lead = $configRepo->get('SaturdayDelivery.global.leadDays');
        $lead = intval($lead);

        $max = $configRepo->get('SaturdayDelivery.global.numberOfChoices');
        $max = intval($max);

        $this->getLogger(__CLASS__)->debug('SaturdayDelivery::Debug.SaturdayDeliveryDatePickerContainer_Config', ['lead' => $lead, 'max' => $max]);

        $possibleDates = $this->getPossibleDates($lead, $max);

        /** @var SessionStorageRepositoryContract $sessionRepo */
        $sessionRepo = pluginApp(SessionStorageRepositoryContract::class);
        $sessionRepo->setSessionValue(SessionKey::POSSIBLE_SELECTED_DATES, array_keys($possibleDates));

        /** @var BasketRepositoryContract $basketRepo */
        $basketRepo = pluginApp(BasketRepositoryContract::class);

        /** @var Basket $basket */
        $basket = $basketRepo->load();

        /** @var NumberFormatFilter */
        $numberFormatFilter = pluginApp(NumberFormatFilter::class);
        $surcharge = $numberFormatFilter->formatMonetary($configRepo->get('SaturdayDelivery.global.surcharge'), $basket->currency);

        $this->getLogger(__CLASS__)->debug('SaturdayDelivery::Debug.SaturdayDeliveryDatePickerContainer_Config', ['surcharge' => $surcharge]);

        $shippingProfiles = [];
        $shippingProfilesAsString = $configRepo->get('SaturdayDelivery.global.shippingProfiles', '');
        if (strlen($shippingProfilesAsString)) {
            $shippingProfiles = explode(',', $shippingProfilesAsString);
            $shippingProfiles = array_map(fn($num) => (int)$num, $shippingProfiles);
        }

        $hideContainer = false;
        if (count($shippingProfiles) && !in_array($basket->shippingProfileId, $shippingProfiles)) {
            $hideContainer = true;
        }

        return $twig->render('SaturdayDelivery::content.Containers.DatePicker', [
            'sessionValue' => [
                'selectedDate' => $sessionRepo->getSessionValue(SessionKey::SELECTED_DATE, null),
                'additionalFee' => $sessionRepo->getSessionValue(SessionKey::ADDITIONAL_FEE, 0.0)
            ],
            'possibleDates' => $possibleDates,
            'surcharge' => $surcharge,
            'shippingProfiles' => json_encode($shippingProfiles),
            'hidden' => $hideContainer
        ]);
    }

    /**
     * @param integer $lead
     * @param integer $max
     * @return array
     */
    private function getPossibleDates(int $lead, int $max): array
    {
        $start = Carbon::now()->addDays($lead);
        $count = 0;
        $possibleDates = [];

        if ($max > self::MAX_POSSIBLE_DATES) $max = self::MAX_POSSIBLE_DATES;

        while ($count < $max) {
            $curr = $start->addDay();
            if ($curr->isSaturday()) {
                $start = $curr;
                $possibleDates[$curr->format('Y-m-d')] = ['value' => $curr->format('Y-m-d'), 'label' => ('Sa, ' . $curr->format('d.m.'))];
                $count++;
            }
        }
        $this->getLogger(__CLASS__)->debug('SaturdayDelivery::Debug.SaturdayDeliveryDatePickerContainer_Carbon', ['possibleDates' => $possibleDates]);

        return $possibleDates;
    }
}
