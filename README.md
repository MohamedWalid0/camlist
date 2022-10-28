<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Installation

1- clone the repository
```
git clone https://github.com/MohamedWalid0/camlist
```
2- install the dependancies 
```
composer install
```
3- create .env file 
```
cp .env.example .env
```
4- create new mysql database in local machine <br>
5- open ```.env``` file and modify the following variables with creadintials
```
DB_DATABASE=camlist
DB_USERNAME=
DB_PASSWORD=
```
6- Migrate the databases
```
php artisan migrate --seed
```
7- run tests
```
php artisan test
```

<hr>
## Usage

1- Allow Users to Bid on certain Pet certain amount of money. <br>
    - send ```POST``` request to the following endpoint with pet <br>
    - body : ```
        {
            "user_id" : 2  ,
            "cost" : "4000"
        } ```
    - ``` http://127.0.0.1:8001/api/{pet}/bids ```


2- Allow pet owner to list all bids. <br>
    - send ```GET``` request to the following endpoint with pet <br>
    - body : ```
        {
            "user_id" : 2  
        } ```
    - ``` http://127.0.0.1:8001/api/{pet}/bids ```


3- List the winners
    - send ```GET``` request to the following endpoint with pet <br>
    - ``` http://127.0.0.1:8001/api/{pet}/winners ```



<hr>
## Documentaion


1- Allow Users to Bid on certain Pet certain amount of money : <br>
    -  First of all check pet's status is available and the cost is valid and much more the maximum bid on the pet's auction . <br>
    - Use ```LOCK``` for bids table while bid action .
    - check if suer already have bid on the same pet before , then update it , else create a new one .<br>

2- Allow pet owner to list all bids : <br>
    - Check if the pet's owner is the same user who made the request .<br>
    - then list the pet's bids . <br>

3- List the winners :<br>
    - Frist of all check ge pet's available count and the bidders count. <br>
    - if no bids then ```no winners!``` .  <br>
    - if only one bid , then return the only cost for the bid .  <br>
    - if 2 or more , using ```Generalized second-price auction```  mechanism . <br>
    - if Tie-breaker: Who comes first in alphabetical order . <br>





