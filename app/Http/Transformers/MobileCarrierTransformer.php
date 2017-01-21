<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\API\MobileCarrier;

class MobileCarrierTransformer extends TransformerAbstract
{
    
    /**
     * @param MobileCarrier $item
     * @return array
     */
    public function transform(MobileCarrier $item)
    {
        return [
            'id' => (int)$item->id,
            'label' => $item->label,
            'code' => $item->code,
            'created' => date('Y-m-d - H:i:s', strtotime($item->created_at)),
            'updated' => date('Y-m-d - H:i:s', strtotime($item->updated_at)),
        ];
    }

}