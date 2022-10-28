<?php

namespace App\Http\Services;

use App\Models\Bid;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BidService{

    /**
     * create or update bid if user already have bid on same pet
     * 
     * @param BidRequest $request
     * @param Pet $pet
     * @return object
    */
    function addBid( Pet $pet , User $user , $cost ) : object
    {
        abort_if( $pet->status != Pet::STATUS_AVAILABLE , 400 , "The Pet status is not available !") ;
        abort_if( is_null($cost) , 400 , "The cost field is missing !") ;
        abort_if( $cost <= $this->maxBid($pet), 400 , "The Bid amount is less then the maximum bid !") ;

        $bid = Cache::lock('bid_'.$pet->id, 10)->block(3, function () use ( $pet , $user , $cost) {

            if ($bid = Bid::whereUserId($user->id)->wherePetId($pet->id)->first()){
                $bid->update([
                    'cost' => $cost
                ]);
                return $bid;
            }

            return Bid::create([

                'user_id' => $user->id ,
                'pet_id' => $pet->id ,
                'cost' => $cost

            ]);
            
        });

        return response()->json($bid) ;

    }
    

    /**
     * listing bids for owner
     *
     * @param BidRequest $request
     * @param Pet $pet
     * @return object
    */
    function listBids(Pet $pet){
        return response()->json($pet->bids);
    }

    /**
     * get max bid on pet
     *
     * @param Pet $pet
     * @return int
    */
    private function maxBid(Pet $pet) : int
    {
        return Bid::select( DB::raw('max(cost) as cost') )->wherePetId($pet->id)->first()->cost ?? 0;
    }

    /**
     * list auction winners on pet
     * 
     * check for bids count
     * if no bids then no winners
     * if one bid then one winner with only one cost (his own cost)
     * 
     * $numberOfWinners equals the available_count attribute for pet 
     * if count of bids is less then or equal the available_count then $numberOfWinners equals count of bids
     *
     * @param Pet $pet
     * @return object
     */
    function listWinners(Pet $pet) : object
    {

        $numberOfWinners = $pet->available_count  ;

        if (count($pet->bids) == 0) 
            return response()->json("no winners !") ;
        
        if (count($pet->bids) == 1) 
            return response()->json([$pet->bids[0]->user->username => $pet->bids[0]->cost]) ;
        
        if (count($pet->bids) <= $pet->available_count ) 
            $numberOfWinners = count($pet->bids) - 1 ;
        
        return $this->winnersWithSecondPriceAuction( $this->sortBidsByCostAndUsername($pet) , $numberOfWinners );

    }

    /**
     * Undocumented function
     *
     * @param Collection $sortedBids
     * @param int $numberOfWinners
     * @return object
     */
    private function winnersWithSecondPriceAuction($sortedBids , $numberOfWinners) : object
    {

        $winners = [] ;        
        $sortedBids = $this->castingSortedBidsFromJsonToArray($sortedBids) ;

        for( $i = 0 ; $i < sizeof($sortedBids) ; $i++ ){

            if ($numberOfWinners == 0) {
                $winners[$sortedBids[$i]['user']['username']] = 'Lost The Auction' ;
                continue;
            }

            $winners[$sortedBids[$i]['user']['username']] = $sortedBids[$i+1]['cost'] ;
            $numberOfWinners-- ;

        }

        return response()->json($winners);

    }

    /**
     * sort bids according to cost 
     * if tie-breaker then sort it in alphabetical order
     *
     * @param Pet $pet
     * @return Collection
     */
    private function sortBidsByCostAndUsername(Pet $pet) : Collection
    {
        return $pet->bids->sortBy(function($bid) { 
            return $bid->user->username;
        })->sortByDesc('cost');
    }

    /**
     * casting sorted bids from json to array
     *
     * @param [json] $sortedBids
     * @return array
     */
    private function castingSortedBidsFromJsonToArray($sortedBids) : array 
    {
        return array_values( json_decode($sortedBids  , true) ) ;
    }

}