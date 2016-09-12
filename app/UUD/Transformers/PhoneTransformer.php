<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 11/12/15
 * Time: 4:47 PM
 */
namespace App\UUD\Transformers;

use App\Model\MobileCarrier;


class PhoneTransformer extends Transformer
{

    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $carrier = null;

        if ((bool)$item['is_cell'] && !empty($item['mobile_carrier_id'])) {
            $mobileCarrierTransformer = new MobileCarrierTransformer;
            $carrier = $mobileCarrierTransformer->transform($item->carrier);
        }

        return [
            'id' => (int)$item['id'],
            'user_id' => (int)$item['user_id'],
            'number' => (int)$item['number'],
            'ext' => (int)$item['ext'],
            'is_cell' => (bool)$item['is_cell'],
            'carrier' => $carrier
        ];
    }

}