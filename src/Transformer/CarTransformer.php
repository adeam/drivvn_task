<?php

namespace App\Transformer;

use App\Entity\Car;

class CarTransformer
{

    public function transformToArray(Car $car): array
    {
        return [
            'id' => $car->getId(),
            'make' => $car->getMake(),
            'model' => $car->getModel(),
            'build_date' => $car->getBuildDate()->format('d.m.Y'),
            'colour' => [
                'colour_id' => $car->getColour()->getId(),
                'colour_name' => $car->getColour()->getName()
            ]
        ];
    }

    public function collectionToArray($cars): array
    {
        $outputArray = [];
        foreach($cars as $car){
            $outputArray[] = $this->transformToArray($car);
        }
        return $outputArray;
    }
}