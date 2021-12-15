#Installation

install project with composer, in the project root run:

```composer install```

to build and run the project execute: (in the project root directory)

```docker-compose up -d ```

then attach to the php container and execute initial migration, only needed on first run to insert default colours into db

```php bin/console doctrine:migrations:migrate```

API should be now exposed on port :80

To run the unit tests (in the project root):
`php bin/phpunit`

***
###Used ports:
80 - web and 3306, 3316 - databases

***
#Endpoints
```
POST /car -> create new car and return its details
GET /cars -> returns list of all cars
GET /car/{id} -> returns details of card with specified id
DELETE /car/{id} -> delete specified car

GET /colours -> list of available colours
POST /colour -> add new colour and return its details
GET /colour/{id} -> return specified colour
DELETE /colour{id} -> delete specified colour
```

`POST /car` example
```
{
    "model": "car_model",
    "make": "car_make",	
    "build_date": "15.12.2017",
    "colour_id": 1
}
```

`POST /colour` example
```
{
    "name": "black"
}
```



#Optional
***
To document the API: swagger.io with OpenAPI annotations or .yaml definitions