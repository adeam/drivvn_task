<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController extends AbstractController
{

    protected function decodeRequest(Request $request)
    {
        try{
            $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        }catch (\JsonException $e){
            return $this->error('Invalid JSON data', 400);
        }
        return $content;
    }

    public function listAll() :Response{
        return new JsonResponse(
            ['message'=>'Not yet implemented.'],
            404
        );
    }

    public function listOne($id) :Response{
        return new JsonResponse(
            ['message'=>'Not yet implemented.'],
            404
        );
    }

    public function delete($id) :Response{
        return new JsonResponse(
            ['message'=>'Not yet implemented.'],
            404
        );
    }
    public function add(Request $request) :Response{
        return new JsonResponse(
            ['message'=>'Not yet implemented.'],
            404
        );
    }
    public function update(Request $request, $id) :Response{
        return new JsonResponse(
            ['message'=>'Not yet implemented.'],
            404
        );
    }

    public function error($message, $status = 400): Response
    {
        return new JsonResponse(
            ['message'=>$message],
            $status
        );
    }
}