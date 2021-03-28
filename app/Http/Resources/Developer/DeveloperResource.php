<?php

namespace App\Http\Resources\Developer;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class DeveloperResource extends JsonResource
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
            if ($translation)
                return [
                    'id' => $this->id,
                    'name' => $translation->data->name ?? $this->name,
                    'slug' => $this->slug,
                    'bio' => $translation->data->bio ?? $this->bio,
                    'poster' => $this->poster,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'website' => $this->website,
                    'apps' => $this->apps
                ];
            else
                return [
                    'id' => $this->id,
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'bio' => $this->bio,
                    'poster' => $this->poster,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'website' => $this->website,
                    'apps' => $this->apps
                ];
        }


        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'bio' => $this->bio,
            'poster' => $this->poster,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'country' => $this->country,
            'apps' => $this->apps
        ];
    }
}
