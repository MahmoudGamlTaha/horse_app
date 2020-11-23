<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::get('/factoriesList/{activityId}', ['uses'=>'CompanyController@getAllShopWithActivityId']);
Route::get('/product/search/{search}', ['uses' => 'ShopProductController@searchProduct']);
Route::get('/factoriesCategory', ['uses'=>'ActivityController@getAllshopActiviy']);
Route::get('/factorySlider', ['uses' => 'BannerController@getFactoryBanner']);
Route::get('/factoriesProfile/{factoryId}', ['uses'=>'CompanyController@getCompanyProfile']);
Route::get('/factorySubCategory/{id}', ['uses' => 'ActivityController@getSubCategoryShop']);

//polices sprint 3
Route::get('/polices', ['uses'=>'ConfigGlobalController@getPolices']);

Route::get('/configs', ['uses'=>'ConfigGlobalController@config']);

Route::get('/aboutUs', ['uses'=>'ConfigGlobalController@aboutUs']);
Route::get('/productDetail/{factoryId}/{productId}', ['uses'=>'ShopProductController@getProductDetails']);
// product review
Route::get('/productReview/{factoryId}/{productId}', ['uses'=>'ShopProductController@getTotalProductReview']);
Route::get('/allProductReview/{factoryId}/{productId}', ['uses'=>'ShopProductController@getAllProductReview']);

// magazine
Route::get('/get-magazine', ['uses'=> 'MagazineController@getMagazine']);
Route::get('/get-magazine-topic/{id}', ['uses'=> 'MagazineTopicController@MagazineTopic']);
Route::get('/get-events', ['uses'=> 'EventsController@getEvents']);
Route::get('/get-banner-magazine/{magazine_id}', ['uses'=> 'BannerMagazineController@getMagazineImages']);

//video
Route::get('/get-video', ['uses'=> 'VideoController@getVideo']);

//
Route::get('/get-most-product', ['uses'=> 'ShopProductController@getMostProduct']);

Route::get('/factoriesProducts/{factoryId}', ['uses'=>'CompanyController@getAllCompanyProduct']);

Route::post('/login',['uses' => 'AuthController@postapiLogin']);
Route::post('/logout', ['uses' => 'AuthController@logout']);
Route::post('/register',['uses'=>'AuthController@postapiRegister']);

Route::get('/slider/banners/{company}',['uses' => 'BannerController@getBannerSlideShow']);

Route::get('/vendor/categories/{company}',['uses' => 'ShopCategoryController@getCategories']);

Route::post('/edit-user', 'UserController@mobileEditProfile')->middleware('TokenAuth');
Route::post('/addToCart', 'ShopCart@addToMobileCart')->name('addToCart');
Route::get('/cart/{user_id}', 'ShopCart@getMobileCart')->name('cart');
Route::post('/updateCart', 'ShopCart@updateMobileCart')->name('updateCart');


Route::post('/addToFav', 'FavCart@addFavMobileCart')->name('addFav');
Route::get('/Fav/{user_id}', 'FavCart@getMobileFav')->name('Fav');
Route::post('/removeFav', 'FavCart@removeCartFav')->name('removeFav');



Route::post('/storeOrder', 'ShopCart@storeOrderMobile')->name('storeOrder');
Route::post('/checkOut', 'ShopCart@MobileCheckout')->name('checkOut');
Route::get('/champion', ['uses' => 'ChampionController@getChampiones']);

