<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('digemid')->group(function () {
                Route::get('/', 'DigemidController@index')->name('tenant.digemid.index');
                Route::post('/update_exportable/{item?}', 'DigemidController@updateExportableItem');
            });
        });
    });
}
