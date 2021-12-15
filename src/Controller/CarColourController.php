<?php

namespace App\Controller;

use App\Entity\CarColour;
use App\Transformer\CarColourTransformer;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CarColourController extends AbstractApiController
{

    private ObjectManager $em;
    private CarColourTransformer $transformer;
    private ValidatorInterface $v;

    public function __construct(
        ManagerRegistry $doctrine,
        CarColourTransformer $carColourTransformer,
        ValidatorInterface $validator
    ){
        $this->em = $doctrine->getManager();
        $this->v = $validator;
        $this->transformer = $carColourTransformer;
    }

    public function listAll(): Response
    {
        $colours = $this->em->getRepository(CarColour::class)->findBy([], ['id'=>'ASC']);
        return new JsonResponse($this->transformer->collectionToArray($colours));
    }

    public function listOne($id): Response
    {
        $colour = $this->em->find(CarColour::class, $id);
        if(!$colour){
            return $this->error('Unable to find the requested car colour.');
        }
        return new JsonResponse($this->transformer->transformToArray($colour));
    }

    public function add(Request $request): Response
    {
        $requestContent = $this->decodeRequest($request);
        if(!is_array($requestContent)) {
            //a error response
            return $requestContent;
        }

        $colour = new CarColour();
        $colour->setName($requestContent['name']);

        $validationErrors = $this->v->validate($colour);
        if(count($validationErrors) > 0){
            return $this->error((string) $validationErrors);
        }

        $this->em->persist($colour);
        $this->em->flush();

        return new JsonResponse($this->transformer->transformToArray($colour));
    }

    public function delete($id): Response
    {
        $colour = $this->em->find(CarColour::class, $id);
        if(!$colour){
            return $this->error('Unable to find the requested car colour.');
        }
        $this->em->remove($colour);
        $this->em->flush();
        return new Response(null, 200);
    }
}