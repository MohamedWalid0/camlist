<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\Pet;
use App\Models\User;
use Tests\TestCase;

class BidTest extends TestCase
{
    /**
     * @test
    */
    function itTestsListingBidsForPetOwner(){

        $owner = User::factory()->create();
        $pet = Pet::factory()->for($owner)->create() ;
        $bids = Bid::factory(3)->for($pet)->create() ;  

        $response = $this->json( 'GET' , '/api/'.$pet->id.'/bids' , [
            'user_id' => $owner->id
        ]);

        $response -> assertOk() ;
        $this->assertCount(3, $response->json());      

    }

    /**
     * @test
    */
    function itTestsListingBidsForNonPetOwner(){

        $pet = Pet::factory()->create() ;
        $user = User::factory()->create();        

        $response = $this->json( 'GET' , '/api/'.$pet->id.'/bids' , [
            'user_id' => $user->id
        ]);

        $response -> assertStatus(403) ;

    }

    /**
     * @test
    */
    function itTestsAddBid(){

        $owner = User::factory()->create();
        $pet = Pet::factory()->for($owner)->create([
            'status' => Pet::STATUS_AVAILABLE
        ]) ;

        $user = User::factory()->create();
        
        $response = $this->postJson('/api/'.$pet->id.'/bids' , [
            'user_id' => $user->id , 
            'cost' => "1000"
        ]);

        $response->assertJsonStructure()->assertJsonFragment([
            'user_id' => $user->id , 
            'pet_id' => $pet->id
        ]);        

    }

    /**
     * @test
    */
    function itTestsAddBidWithInvalidPetStatus(){

        $owner = User::factory()->create();
        $pet = Pet::factory()->for($owner)->create([
            'status' => Pet::STATUS_PENDING
        ]) ;

        $user = User::factory()->create();
        
        $response = $this->postJson('/api/'.$pet->id.'/bids' , [
            'user_id' => $user->id , 
            'cost' => "1000"
        ]);

        $response->assertStatus(400)->assertJson(['message'=> 'The Pet status is not available !']);        

    }

    /**
     * @test
    */
    function itTestsAddBidWithEmptyCost(){

        $owner = User::factory()->create();
        $pet = Pet::factory()->for($owner)->create([
            'status' => Pet::STATUS_AVAILABLE
        ]) ;

        $user = User::factory()->create();
        
        $response = $this->postJson('/api/'.$pet->id.'/bids' , [
            'user_id' => $user->id , 
        ]);

        $response->assertStatus(400)->assertJson(['message'=> 'The cost field is missing !']);     

    }

    /**
     * @test
    */
    function itTestsAddBidWithInvalidCost(){

        $owner = User::factory()->create();
        $pet = Pet::factory()->for($owner)->create([
            'status' => Pet::STATUS_AVAILABLE
        ]) ;

        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $this->post('/api/'.$pet->id.'/bids' , [
            'user_id' => $firstUser->id , 
            'cost' => "2000"
        ]);
        
        $response = $this->postJson('/api/'.$pet->id.'/bids' , [
            'user_id' => $secondUser->id , 
            'cost' => "1000"
        ]);

        $response->assertStatus(400)->assertJson(['message'=> 'The Bid amount is less then the maximum bid !']);     

    }

    /**
     * @test
    */
    function itTestsListWinners(){
        $owner = User::factory()->create();
        $pet = Pet::factory()->for($owner)->create([
            'status' => Pet::STATUS_AVAILABLE , 
            'available_count' => 3
        ]) ;

        $user1 = User::factory()->create([
            'username' => 'mohamed' . rand(0,10000) .'_' . rand(0,10000)
        ]);
        $user2 = User::factory()->create([
            'username' => 'ahmed' . rand(0,10000) . '&' . rand(0,10000)
        ]);
        $user3 = User::factory()->create();
        $user4 = User::factory()->create();
        $user5 = User::factory()->create();

        $bid1 = Bid::factory()->for($user1)->for($pet)->create([
            'cost' => 5000
        ]);
        $bid2 = Bid::factory()->for($user2)->for($pet)->create([
            'cost' => 5000
        ]);
        $bid3 = Bid::factory()->for($user3)->for($pet)->create();
        $bid4 = Bid::factory()->for($user4)->for($pet)->create();
        $bid5 = Bid::factory()->for($user5)->for($pet)->create();


        $response = $this->getJson('/api/'.$pet->id.'/winners');
        $response->assertJsonStructure();        
        
        $winnersCount = $pet->available_count ;

        foreach ( array_values($response->json()) as $value) {

            if ($winnersCount > 0 ) {
                abort_if(! is_int($value)  , 400 ) ;                
                $winnersCount -- ;
            }
            else{
                abort_if(! is_string($value)  , 404 ) ;
            }

        }
        

    }







}
