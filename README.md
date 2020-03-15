
## Fleet-Management (Bus booking system)

Fleet-Management is a bus booking system api For Egyptian Cities developed with Laravel Framework 7.0

#### - Installation.
It's very easy.

First, run composer install

	composer install

Then migrate the database

	php artisan migrate
	
Then seed the database

	php artisan db:seed

You can add cities as much as you can from config/constants.php

	<?php
    
    return [
        'cities' => [
          'Matrouh',
          'Alexandria',
          'Kafr el-Seikh',
          'Damietta',
          'Dakahlia',
          'Gharbia',
          'Sharqia',
          'Port Said',
          'Ismailia',
          'suez',
          'Monufia',
          'Beheira',
          'Qalyubia',
          'Giza',
          'Cairo',
          'Faiyum',
          'Beni Suef',
          'Minya',
          'Asyut',
          'Suhag',
          'Qena',
          'Luxor',
          'Aswan',
        ],
    ];

## Admin Dashboard

#### Register

Using route /admin/register

#### Login 

Using route /admin/login

#### Dashboard

/admin/dashboard

#### You Can book a seat on a trip by visiting an upcoming trip

/admin/trips/{id}/book

## API Endpoints

assigning a user for the book and choose a source and destination to book a seat if available.

Create your own users with endpoint /api/auth/register

        {
            "email": string,
            "password": string,
            "name": string
        } 

#### Listing available trips by choosing source and destination stations endpoint

Using OAuthApi authentication: 
    
        {"headers": {
            "Authorization": "Bearer TOKEN"
            },
          "body": {
            "start_station": integer,
            "end_station": integer
          }
        }
Returning array of Trips with available seats count:

         {
                "id": 7,
                "source_id": 4,
                "destination_id": 8,
                "date_to_book": "2020-03-12",
                "created_at": "2020-03-12T23:34:12.000000Z",
                "updated_at": "2020-03-12T23:34:12.000000Z",
                "available_seats": 11,
                "source": {
                                "id": 4,
                                "name": "Damietta",
                                "order": 4
                            },
                            "destination": {
                                "id": 8,
                                "name": "Port Said",
                                "order": 8
                            }
            },
            {
                "id": 8,
                "source_id": 4,
                "destination_id": 8,
                "date_to_book": "2020-03-13",
                "created_at": "2020-03-12T23:34:12.000000Z",
                "updated_at": "2020-03-12T23:34:12.000000Z",
                "available_seats": 5,
                "source": {
                                "id": 4,
                                "name": "Damietta",
                                "order": 4
                            },
                            "destination": {
                                "id": 8,
                                "name": "Port Said",
                                "order": 8
                            }
            }
        }
        
## Booking a seat on a trip from source to destination stations

Using OAuthApi authentication: 
    
        {"headers": {
            "Authorization": "Bearer TOKEN"
            },
          "body": {
            "start_station": integer,
            "end_station": integer,
            "trip_id": integer
          }
        }
Returning Booking object with unique seat number

        {
             "id": 43
             "source_id": 4,
             "destination_id": 5,
             "trip_id": 4,
             "user_id": 1,
             "seat_no": "kEEUQcPT",
             "updated_at": "2020-03-13T00:10:43.000000Z",
             "created_at": "2020-03-13T00:10:43.000000Z",
             "source": {
                         "id": 4,
                         "name": "Damietta",
                         "order": 4
                     },
                     "destination": {
                         "id": 5,
                         "name": "Dakahlia",
                         "order": 5
                     }
        },


## Testing

Create your test database with its configuration in .env file (DB_DATABASE_TEST), default driver is "sqlite" - you can change it from ./phpunit.xml

Add DB_DATABASE_TEST=dbname in your .env file

Migrate the database

	php artisan migrate --database=sqlite
	
Seed the database

	php artisan db:seed --database=sqlite
	
Run phpunit test cases

    php artisan test
    


# Docker

#### Installing using docker-compose

Installing dependencies

        composer install

Copying .env.docker contents to .env

        cp .env.docker .env
        
Build images and run the Docker containers

        sudo docker-compose build && sudo docker-compose up -d
        
Migrate database and seed
        
        sudo docker-compose exec php php artisan migrate
        
        sudo docker-compose exec php php artisan db:seed
        
Visit [http://localhost:8080](http://localhost:8080) and enjooooy!

# !!!----     Thank You ----!!!