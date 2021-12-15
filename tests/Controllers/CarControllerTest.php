<?php

namespace App\Controller;

use App\Entity\Car;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CarControllerTest extends WebTestCase
{

    public function testCarApi()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/colour', [],[],[],
            '{
                        "name":"red"
                    }        
        ');

        $currentDate = new \DateTime();
        $crawler = $client->request('POST', '/car', [],[],[],
            '{
                        "model": "car_model",
                        "make": "car_make",	
                        "build_date": "'.$currentDate->format('d.m.Y').'",
                        "colour_id": 1
                    }        
        ');
        self::assertResponseIsSuccessful();
        $responseJson = $client->getResponse()->getContent();
        self::assertJson($responseJson);
        $response = json_decode($responseJson, true);
        self::assertArrayHasKey('id', $response);

        $oldDate = new \DateTime('01.12.2005');
        $crawler = $client->request('POST', '/car', [],[],[],
            '{
                        "model": "car_model",
                        "make": "car_make",	
                        "build_date": "'.$oldDate->format('d.m.Y').'",
                        "colour_id": 1
                    }        
        ');
        self::assertResponseStatusCodeSame(400);
        self::assertJson($client->getResponse()->getContent());

        $crawler = $client->request('GET', '/car/'.$response['id']);
        self::assertResponseIsSuccessful();
        self::assertJson($client->getResponse()->getContent());

        $crawler = $client->request('DELETE', '/car/'.$response['id']);
        self::assertResponseStatusCodeSame(200);

        $crawler = $client->request('DELETE', '/car/'.$response['id']);
        self::assertResponseStatusCodeSame(400);
    }
}
