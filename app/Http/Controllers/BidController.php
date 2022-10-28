<?php

namespace App\Http\Controllers;

use App\Http\Requests\BidRequest;
use App\Http\Services\BidService;
use App\Models\Pet;
use App\Models\User;

class BidController extends Controller
{
    private $bidService ;
    
    /**
    * @param BidService $bidService
    */
    public function __construct( BidService $bidService ){
    
        $this->bidService = $bidService;
    
    }

    protected function addBid( BidRequest $request , Pet $pet ){

        return $this->bidService->addBid($pet , User::find($request['user_id']) , $request['cost'] ?? null ) ;

    }

    protected function listBids( BidRequest $request , Pet $pet){
    
        abort_if( $pet->user_id != $request['user_id'] , 403 , "The Pet is not belongs to you !") ;

        return $this->bidService->listBids($pet) ;
        
    }

    protected function listWinners(Pet $pet)
    {
        return $this->bidService->listWinners($pet);
    }



}
