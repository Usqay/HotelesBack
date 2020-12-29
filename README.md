# Hoteles Frontend

This project was made with Laravel 8, is a backend project for the Hoteles Project of Usqay.

## Developer

This project was made with ♥ by [Víctor Moreno](https://www.facebook.com/vmorenoz/).
When i made this project, only God and me could understand the code, now only God can, please don't hate me, remember i love you. :D

## How to deploy and build

First, download the project repository.
After, run the next command:
`composer install`.

Once you have downloaded the dependencies, you need create a blank database with the name you have configured in .env file.

After of this, run the next commands:

`php artisan migrate:fresh --seed`
`php artisan passport:install`

## What do you need to run this project

You need Composer, some local server like Xampp or Laragon.

## Don't forget

This project is only backend, you need deploy the frontend project made in angular.
If you install this project on Laragon, the default route is `hoteles.test/api`.
If you install this project on Xampp, te default route is `localhost/hotelesapi/public/api`
You can change all this settings in the .env file.