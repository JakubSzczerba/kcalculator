# Kcalculator
> Fitness app prototype

## Table of contents
* [General info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)
* [Features](#features)
* [Status](#status)
* [Inspiration](#inspiration)
* [Contact](#contact)

## General info
My project of application for helthy lifestyle and calorie counting. Web aplication for everyone who wants to take care of health.

## Technologies
* PHP - version 7.3.2
* Symfony - version 5.2.6
* MySQL/Doctrine 

## Setup
```
docker compose up -d
```
```
docker compose exec php composer install
```
```
docker compose run --rm encore yarn build
```
```
docker compose exec php bin/console doctrine:migrations:migrate
```
```
docker compose exec php bin/console doctrine:fixtures:load --append
```
```
docker compose exec php bin/console csv:import
```

Products database -> https://github.com/MK-PL/Tabele-kaloryczne-i-zawartosci-bialka-tluszczu-weglowodanow-w-produktach-spozywczych

## Features
List of features ready and TODOs for future development
* Uploading food from table of polish products 
* Algorithm for calculating the caloric demand
* Total calories of consumed products for the day

To-do list:
* Add small blog options -> Posts, comments, likes and sharing progress
* Think about API of training

## Status
Project is: _in progress_

## Inspiration
Project inspired by competing applications and my experience of helthy lifestyle

## Contact
Created by @JakubSzczerba
