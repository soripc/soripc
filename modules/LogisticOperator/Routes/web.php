<?php

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    $hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

    if ($hostname) {
        Route::domain($hostname->fqdn)->group(function () {

            Route::middleware(['auth', 'locked.tenant'])->group(function () {

                Route::get('configurations/yobel', 'YobelController@SetAdvanceConfiguration')->name('tenant.yobel.configuration');
                Route::post('configurations/yobel', 'YobelController@SaveSetAdvanceConfiguration');


                Route::prefix('logistic_operator')
                    ->group(function () {
                        Route::prefix('yobel')
                            ->group(function () {
                                Route::get('/', 'LogisticOperatorController@yobel_index')->name('tenant.logistic_operator.yobel.index');
                                Route::get('/tables', 'LogisticOperatorController@tables');
                                Route::get('/records', 'YobelController@records');
                                Route::post('/items/import', 'YobelController@import');

                                Route::prefix('yobelscm')->group(function () {
                                    Route::post('/pedido', 'YobelController@makeYobelPedido');
                                });


                                Route::prefix('testing')->group(function () {
                                    Route::get('CrearEmbarque/{id?}', 'YobelController@crearEmbarque')->name('test.yobel.embarque');
                                    Route::get('CrearCliente/{id?}', 'YobelController@crearCliente')->name('test.yobel.cliente');
                                    Route::get('crearPedido/{id?}', 'YobelController@crearPedido')->name('test.yobel.pedido');
                                });

                            });
                        //orders
                        Route::get('/columns', 'LogisticOperatorController@columns');
                    });
            });
            Route::prefix('yobel/webservice')->group(function () {
                Route::post('confEmbarque', 'YobelController@webServiceConfEmarque');
                Route::post('confPedido', 'YobelController@webServiceConfPedido');
            });
        });
    }
