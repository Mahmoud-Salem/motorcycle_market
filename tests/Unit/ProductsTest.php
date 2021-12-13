<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Motorcycle;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductsTest extends TestCase
{

    private $user ;
    private $product;
    protected function setUp() :void
    {
        parent::setUp();
        $this->user = User::find(5);
        $this->actingAs($this->user);
        $this->product = Motorcycle::find(1);
    }
  
    protected function tearDown() :void
    {
        parent::tearDown();
        $this->user = null ;
    }


    public function test_create_product()
    {
        // test setup
        $product = [
            'make'     =>  'honda',
            'model'  =>  'ono',
            'year' => 2020,
            'description'=> 'Used for 3 years, 150km',
            'user_id'=>$this->user->id,
        ];
        $table ='motorcycles';

        // request
        $request = Request::create('api/products', 'POST',$product);
        $controller = new MotorcycleController();
        $response = $controller->store($request);

        // asserting values

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseHas($table, $product);
    }


    public function test_products_from_cache()
    {
        // request
        $request = Request::create('api/products', 'GET');
        $controller = new MotorcycleController();
        $responseDatabase = $controller->index($request);
        $responseCache = $controller->index($request);

        // asserting values
        $this->assertEquals(200, $responseDatabase->getStatusCode());
        $this->assertEquals(200, $responseCache->getStatusCode());

        $resDatabase = (array)json_decode($responseDatabase->content());
        $resCached = (array)json_decode($responseCache->content());
        $this->assertEquals($resDatabase['message'], 'Fetched from Database');
        $this->assertEquals($resCached['message'], 'Fetched from Redis');
    }

    public function test_mark_product_sold()
    {
        // test setup
        $table ='motorcycles';

        $product = json_decode($this->product,true);
        // request
        $request = Request::create('api/products/'.$this->product->id, 'delete',$product);
        $controller = new MotorcycleController();
        $response = $controller->soldItem($request,$this->product);

        // asserting values
        $this->product->sold = 1 ;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas($table, $product);
    }

    // public function test_image_uploading()
    // {
    //     // test setup
    //     $table ='images';
    //     $image = ['product_id'=>5];
    //     Storage::fake('public');

    //     $file = UploadedFile::fake()->image('avatar.jpg');
    //     $file2 = UploadedFile::fake()->image('avatar2.jpg');
    //     $images = [$file,$file2];
    //     $product = json_decode($this->product,true);
    //     // request
    //     $request = Request::create('api/products/'.$this->product->id .'/image', 'POST',
    //     [
    //         'images' => $images,
    //     ]);
    //     $controller = new ImageController();
    //     $response = $controller->addImage($request,$this->product);

    //     // asserting values
    //     $this->assertEquals(200, $response->getStatusCode());
    //     $this->assertDatabaseHas($table, $image);
    // }

}
