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
composer update
```
3- create .env file 
```
cp .env.example .env
php artisan key:generate
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
![image](https://user-images.githubusercontent.com/63995557/198702392-2a29652e-4531-4e7b-82db-9b6807c2b3ca.png)

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
    - Use ```LOCK``` for bids table while bid action .<br>
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

<hr>

## Test Cases

1- Listing Bids for pet's owner <br>
2- Listing Bids for pet's non owner <br>
3- Add Bid <br>
4- Add Bid with invalid pet's status <br>
5- Add Bid with empty cost <br>
6- Add Bid with invalid cost <br>
7- Listing winners <br>


<hr>

## Test On Deployment

1- Allow Users to Bid on certain Pet certain amount of money : <br>
```POST``` ```http://170.187.200.225/api/{pet}/bids``` <br>
2- Allow pet owner to list all bids : <br>
```GET``` ```http://170.187.200.225/api/{pet}/bids``` <br>
3- List the winners :<br>
```GET``` ```http://170.187.200.225/api/{pet}/winners``` <br>


-- Database tables for test cases (live) ---
![image](https://user-images.githubusercontent.com/63995557/198702561-b1d46734-dbe3-401d-a643-d45d548dd8f1.png)
![image](https://user-images.githubusercontent.com/63995557/198702827-68ce9b8c-4517-44ed-8ba7-3bf393060137.png)
![image](https://user-images.githubusercontent.com/63995557/198702935-213034d5-1c26-486f-9d31-6499c385b820.png)


