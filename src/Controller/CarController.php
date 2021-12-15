<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\CarColour;
use App\Transformer\CarTransformer;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CarController extends AbstractApiController
{

    private ObjectManager $em;
    private CarTransformer $transformer;
    private ValidatorInterface $v;

    public function __construct(
        ManagerRegistry $doctrine,
        CarTransformer $carTransformer,
        ValidatorInterface $validator
    ){
        $this->em = $doctrine->getManager();
        $this->transformer = $carTransformer;
        $this->v = $validator;
    }

    public function listAll(): Response
    {
        $cars = $this->em->getRepository(Car::class)->findBy([], ['id'=>'ASC']);
        return new JsonResponse($this->transformer->collectionToArray($cars));
    }

    public function listOne($id): Response
    {
        $car = $this->em->find(Car::class, $id);
        if(!$car){
            return $this->error('Unable to find the requested car.');
        }
        return new JsonResponse($this->transformer->transformToArray($car));
    }

    public function add(Request $request): Response
    {
        $requestContent = $this->decodeRequest($request);
        if(!is_array($requestContent)) {
            //a error response
            return $requestContent;
        }

        if(
            !isset($requestContent['make']) OR
            !isset($requestContent['model']) OR
            !isset($requestContent['colour_id']) OR
            !isset($requestContent['build_date'])
        ){
            return $this->error('Missing required fields.');
        }

        $car = new Car();
        $car->setMake($requestContent['make']);
        $car->setModel($requestContent['model']);
        try {
            $buildDate = new \DateTime($requestContent['build_date']);
            $car->setBuildDate($buildDate);
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $colour = $this->em->find(CarColour::class, $requestContent['colour_id']);
        if(!$colour){
            return $this->error('Unknow car colour id specified.');
        }
        $car->setColour($colour);

        $validationErrors = $this->v->validate($car);
        if(count($validationErrors) > 0){
            return $this->error((string) $validationErrors);
        }

        $this->em->persist($car);
        $this->em->flush();

        return new JsonResponse($this->transformer->transformToArray($car));
    }

    public function delete($id): Response
    {
        $car = $this->em->find(Car::class, $id);
        if(!$car){
            return $this->error('Unable to find the requested car.');
        }
        $this->em->remove($car);
        $this->em->flush();
        return new Response(null, 200);
    }
}