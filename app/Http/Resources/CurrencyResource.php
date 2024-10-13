<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'id' => $this->id,
                'cross_order' => $this->cross_order,
                'code' => $this->code,
                'unit' => $this->unit,
                'name' => $this->name,
                'forex' => [
                    'buying' => $this->forex_buying,
                    'selling' => $this->forex_selling
                ],
                'banknote' => [
                    'buying' => $this->banknote_buying,
                    'selling' => $this->banknote_selling,
                ],
                'cross_rate' => [
                    'usd' => $this->cross_rate_usd,
                    'other' => $this->cross_rate_other
                ],
                'date' => $this->date->format('Y-m-d'),
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            ];
    }
}
