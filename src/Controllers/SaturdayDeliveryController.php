<?php

namespace SaturdayDelivery\Controllers;

use Carbon\Carbon;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Events\Basket\AfterBasketChanged;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Webshop\Contracts\SessionStorageRepositoryContract;
use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Log\Loggable;
use SaturdayDelivery\Session\SessionKey;

/**
 * Class SaturdayDeliveryController
 * @package SaturdayDelivery\Controllers
 */
class SaturdayDeliveryController extends Controller
{
    use Loggable;

    /**
     * @param Request $request
     * @param BasketRepositoryContract $basketRepo
     * @param SessionStorageRepositoryContract $sessionRepo
     * @param ConfigRepository $configRepo
     * @return string
     */
    public function toggleSaturdayDelivery(
        Request $request,
        BasketRepositoryContract $basketRepo,
        SessionStorageRepositoryContract $sessionRepo,
        ConfigRepository $configRepo
    ): string {
        $selectedSaturday = $request->get('selectedSaturday', null);
        $isActive = $request->get('active', false);
        $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
        $this->getLogger(__CLASS__)->debug('SaturdayDelivery::Debug.SaturdayDeliveryController_Request', ['selectedSaturday' => $selectedSaturday, 'isActive' => $isActive]);

        // Validation rules
        if (!Carbon::createFromFormat('Y-m-d', $selectedSaturday)) {
            $selectedSaturday = null;
        }
        $possibleDates = $sessionRepo->getSessionValue(SessionKey::POSSIBLE_SELECTED_DATES, []);
        if (is_null($selectedSaturday) || !in_array($selectedSaturday, $possibleDates)) {
            $selectedSaturday = null;
            $isActive = false;
        }

        /** @var Basket $basket */
        $basket = $basketRepo->load();

        if ($isActive) {
            $sessionRepo->setSessionValue(SessionKey::SELECTED_DATE, $selectedSaturday);
            $sessionRepo->setSessionValue(SessionKey::ADDITIONAL_FEE, $configRepo->get('SaturdayDelivery.global.surcharge'));
        } else {
            $sessionRepo->setSessionValue(SessionKey::SELECTED_DATE, null);
            $sessionRepo->setSessionValue(SessionKey::ADDITIONAL_FEE, 0.0);
        }

        $basketRepo->save($basket->toArray());

        /** @var Dispatcher $dispatcher */
        $dispatcher = pluginApp(Dispatcher::class);
        $dispatcher->fire(pluginApp(AfterBasketChanged::class));

        return json_encode(['AfterBasketChanged' => $basket]);
    }
}
