<?php

namespace App\Transformer;

use App\Entity\CarColour;

class CarColourTransformer
{
    public function transformToArray(CarColour $colour): array
    {
        return [
            'id' => $colour->getId(),
            'name' => $colour->getName()
        ];
    }

    public function collectionToArray($colours): array
    {
        $outputArray = [];
        foreach($colours as $colour){
            $outputArray[] = $this->transformToArray($colour);
        }
        return $outputArray;
    }
}