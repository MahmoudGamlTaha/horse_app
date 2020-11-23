<?php
//sprint 3
use Illuminate\Http\Request;
Route::post('/prodcutReviewOrLike', ['uses'=>'ShopProductController@likeOrReviewProduct'])->middleware('App\Http\Middleware\TokenAuth');

Route::get('/userorders', ['uses'=>'ShopOrderController@getUserOrder'])->middleware('App\Http\Middleware\TokenAuth');;;

//Route::post('/createOrder',['uses' => 'ShopOrderController@postCreateOrder' ]);

//dublicate routes for mobile 

Route::get('orderDetail/{id}', 'ShopOrderController@detailOrderMobile')->name('order_edit_api_get');
Route::post('/addOrderItem', 'ShopOrderController@addOrderItemMobile')->name('order_add_item_api');
Route::post('/editOrderItem', 'ShopOrderController@editOrderItem')->name('order_edit_item_api');
Route::post('/deleteOrderItem', 'ShopOrderController@deleteOrderItemMobile')->name('order_delete_item_api');
Route::put('/updateOrder', 'ShopOrderController@orderUpdateMobile')->name('order_update_api');
//Route::post('/addCoupon', 'ShopOrderController@SetOrderCoupon');

Route::post('/storeOrderWithItems', 'ShopOrderController@storeOrderMobile')->middleware('App\Http\Middleware\TokenAuth');
//coupon
Route::get('/check-coupon/{code}/{factory}', 'ShopOrderController@applyCoupon');
Route::get('/apply-coupon/{code}/{factory}/{order_id}', 'ShopOrderController@applyCoupon');
//create address
Route::post('addAddress', 'UserAddressController@createNewAddress');
Route::get('getAddress', 'UserAddressController@getAddress');

Route::post('/edit-user', 'UserManagerController@mobileEditProfile')->middleware('App\Http\Middleware\TokenAuth');;;


Route::post('/like-article/{id}', 'MagazineTopicController@LikeArticle');

Route::post('/createPayment', 'PaymentController@proceedPayment')->middleware('App\Http\Middleware\TokenAuth');;
