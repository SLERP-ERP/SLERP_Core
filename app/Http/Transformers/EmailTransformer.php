<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 10/26/16
 * Time: 2:42 PM
 */

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\API\Email;

class EmailTransformer extends TransformerAbstract
{
    /**
     * @param Email $item
     * @return array
     */
    public function transform(Email $item)
    {
        return [
            'id' => $item->id,
            'account_id' => $item->account_id,
            'address' => $item->address,
            'verified' => (bool)$item->verified,
            'verification_token' => $item->verification_token,
            'verification_callback' => $item->verification_callback,
            'created' => date('Y-m-d - H:i:s', strtotime($item->created_at)),
            'updated' => date('Y-m-d - H:i:s', strtotime($item->updated_at)),
        ];
    }

}