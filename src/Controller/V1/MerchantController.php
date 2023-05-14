<?php

namespace App\Controller\V1;

use App\RequestManager\Account\MerchantRequestManager;
use App\Security\Merchant;
use GuzzleHttp\Exception\GuzzleException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class MerchantController
 * @package App\Controller\V1
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class MerchantController extends BaseController
{
    /**
     * @var MerchantRequestManager
     */
    protected MerchantRequestManager $merchantService;

    /**
     * MerchantController constructor.
     * @param MerchantRequestManager $merchantService
     */
    public function __construct(MerchantRequestManager $merchantService)
    {
        $this->merchantService = $merchantService;
    }

    /**
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function show(): JsonResponse
    {
        /**
         * @var Merchant $merchant
         */
        $merchant = $this->merchantService->find($this->getUser()->getId());

        $merchant['info'] = [
            'bank' => $merchant['bank'] ?? '',
            'iban' => $merchant['bank_account'] ?? '',
        ];
        $merchant['enable_nuapay'] = !empty($merchant['acquirer_specific_data']['orgId']);
        $merchant['enable_pockyt'] = !empty($merchant['acquirer_specific_data']['merchantNo'])
            && !empty($merchant['acquirer_specific_data']['storeNo']);
        $merchant['payment_methods'] = null;
        if (!empty($merchant['acquirer_specific_data']['paymentMethods']))
            $merchant['payment_methods'] = $merchant['acquirer_specific_data']['paymentMethods'];

        return $this->success($merchant);
    }
}
