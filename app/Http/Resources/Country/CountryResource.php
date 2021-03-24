<?php

namespace App\Http\Resources\Country;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (App::getLocale() != "en") {
            $translation = $this->translations()->whereLanguageId(Language::whereIsoCode(App::getLocale())->first()->id)->first();
            return [
                'id' => $this->id,
                'name' => $translation->data->name,
                'iso_code' => $this->iso_code,
            ];
        }


        return [
            'id' => $this->id,
            'name' => $this->name,
            'iso_code' => $this->iso_code,
        ];
    }
}
