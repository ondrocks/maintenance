<?php



/*
|--------------------------------------------------------------------------
| Maintenance Routes
|--------------------------------------------------------------------------
*/
Route::group(array('prefix'=>Config::get('maintenance::prefix')), function(){
	/*
        |--------------------------------------------------------------------------
        | Maintenance Public Routes
        |--------------------------------------------------------------------------
        */
        Route::group(array('prefix'=>'register', 'namespace'=>'Stevebauman\Maintenance\Http\Controllers'), function(){
            Route::get('', array(
                'as' => 'maintenance.register',
                'uses' => 'AuthController@getRegister',
            ));
            
            Route::get('why', array(
                'as' => 'maintenance.register.why',
                'uses' => 'AuthController@getWhyRegister',
            ));
            
            Route::post('', array(
                'as' => 'maintenance.register',
                'uses' => 'AuthController@postRegister',
            ));
        });
    
	Route::group(array('prefix'=>'login', 'namespace'=>'Stevebauman\Maintenance\Http\Controllers', 'before'=>'maintenance.notauth'), function(){
	
		Route::get('', array(
			'as' => 'maintenance.login',
			'uses'=>'AuthController@getLogin',
		));
		
		Route::post('', array(
			'as' => 'maintenance.login',
			'uses'=>'AuthController@postLogin',
		));
	
	}); /* End Maintenance Authentication Routes */
	
	
	/*
	|--------------------------------------------------------------------------
	| Maintenance Controller Routes
	|--------------------------------------------------------------------------
	*/	
	Route::group(array('namespace'=>'Stevebauman\Maintenance\Http\Controllers', 'before'=>'maintenance.auth|maintenance.permission'), function(){
		
		Route::get('/', array(
			'as' => 'maintenance.dashboard.index',
			'uses'=>'MaintenanceController@getIndex',
		));
		
		Route::get('logout', array(
			'as' => 'maintenance.logout',
			'uses'=>'AuthController@getLogout',
		));
		
		Route::get('work-orders/settings', array(
			'as' => 'maintenance.work-orders.settings.index',
			'uses' => 'WorkOrderSettingController@index',
		));
		
		/*
		|--------------------------------------------------------------------------
		| Maintenance Work Order Category Routes
		|--------------------------------------------------------------------------
		*/
		Route::get('work-orders/categories/json', array(
				'as' => 'maintenance.work-orders.categories.json',
				'uses' => 'WorkOrderCategoryController@getJson',
			)
		);
		
		Route::get('work-orders/categories/create/{categories?}', array(
				'as' => 'maintenance.work-orders.categories.nodes.create',
				'uses' => 'WorkOrderCategoryController@create',
			)
		);
		
		Route::post('work-orders/categories/move/{categories?}', array(
			'as' => 'maintenance.work-orders.categories.nodes.move',
			'uses'=> 'WorkOrderCategoryController@postMoveCategory'
		));
		
		Route::post('work-orders/categories/create/{categories?}', array(
				'as' => 'maintenance.work-orders.categories.nodes.store',
				'uses' => 'WorkOrderCategoryController@store',
			)
		);
		
		Route::resource('work-orders/categories', 'WorkOrderCategoryController', array(
			'names'=> array(
				'index'		=> 'maintenance.work-orders.categories.index',
				'create'  	=> 'maintenance.work-orders.categories.create',
				'store'   	=> 'maintenance.work-orders.categories.store',
				'show'    	=> 'maintenance.work-orders.categories.show',
				'edit'    	=> 'maintenance.work-orders.categories.edit',
				'update'  	=> 'maintenance.work-orders.categories.update',
				'destroy' 	=> 'maintenance.work-orders.categories.destroy',
			),
		));
                
		
		/*
		|--------------------------------------------------------------------------
		| Maintenance Work Order Routes
		|--------------------------------------------------------------------------
		*/
                Route::post('work-orders/{work_orders}/start-session', array(
                    'as' => 'maintenance.work-orders.session.start',
                    'uses' => 'WorkOrderSessionController@postStart'
                ));
                
                Route::post('work-orders/{work_orders}/end-session/{session}', array(
                    'as' => 'maintenance.work-orders.session.end',
                    'uses' => 'WorkOrderSessionController@postEnd'
                ));
                
                /*
		|--------------------------------------------------------------------------
		| Maintenance Work Order Report Routes
		|--------------------------------------------------------------------------
		*/
                Route::resource('work-orders/{work_orders}/report', 'WorkOrderReportController', array(
                    'only' => array(
                        'store',
                        'show', 
                        'edit',  
                        'update', 
                        'destroy',
                    ),
                    'names'=> array(
                        'store'   	=> 'maintenance.work-orders.report.store',
                        'show'    	=> 'maintenance.work-orders.report.show',
                        'edit'    	=> 'maintenance.work-orders.report.edit',
                        'update'  	=> 'maintenance.work-orders.report.update',
                        'destroy' 	=> 'maintenance.work-orders.report.destroy',
                    ),
		));
                
                
                /*
		|--------------------------------------------------------------------------
		| Maintenance Work Order Routes
		|--------------------------------------------------------------------------
		*/
		Route::resource('work-orders', 'WorkOrderController', array(
                    'names'=> array(
                        'index'         => 'maintenance.work-orders.index',
                        'create'  	=> 'maintenance.work-orders.create',
                        'store'   	=> 'maintenance.work-orders.store',
                        'show'    	=> 'maintenance.work-orders.show',
                        'edit'    	=> 'maintenance.work-orders.edit',
                        'update'  	=> 'maintenance.work-orders.update',
                        'destroy' 	=> 'maintenance.work-orders.destroy',
                    ),
		));
		
		
		/*
		|--------------------------------------------------------------------------
		| Maintenance Work Order Update Routes
		|--------------------------------------------------------------------------
		*/	
		Route::resource('work-orders.updates', 'WorkOrderUpdateController', array(
			'names'=> array(
				'index'		=> 'maintenance.work-orders.updates.index',
				'create'  	=> 'maintenance.work-orders.updates.create',
				'store'   	=> 'maintenance.work-orders.updates.store',
				'show'    	=> 'maintenance.work-orders.updates.show',
				'edit'    	=> 'maintenance.work-orders.updates.edit',
				'update'  	=> 'maintenance.work-orders.updates.update',
				'destroy' 	=> 'maintenance.work-orders.updates.destroy',
			),
		));
		
                /*
		|--------------------------------------------------------------------------
		| Maintenance Work Order Assignment Routes
		|--------------------------------------------------------------------------
		*/
                Route::resource('work-orders.assignments', 'AssignmentController', array(
                    'only' => array(
                        'index',
                        'create',
                        'store',
                        'destroy'
                    ),
                    'names' => array(
                        'index' => 'maintenance.work-orders.assignments.index',
                        'create' => 'maintenance.work-orders.assignments.create',
                        'store' => 'maintenance.work-orders.assignments.store',
                        'destroy' => 'maintenance.work-orders.assignments.destroy'
                    ),
                ));
		
                /*
                 * Asset Image Upload Routes
                 */
		Route::post('assets/images/uploads', array(
			'as' => 'maintenance.assets.images.uploads.store',
			'uses' => 'AssetImageUploadController@store'
		));
		
		Route::post('assets/images/uploads/destroy', array(
			'as' => 'maintenance.assets.images.uploads.destroy',
			'uses' => 'AssetImageUploadController@destroy'
		));
                
                Route::resource('assets.images', 'AssetImageController', array(
                        'only' => array(
                            'index',	
                            'create', 
                            'store',
                            'show', 
                            'destroy'
                        ),
			'names'=> array(
				'index'		=> 'maintenance.assets.images.index',
				'create'  	=> 'maintenance.assets.images.create',
				'store'   	=> 'maintenance.assets.images.store',
				'show'    	=> 'maintenance.assets.images.show',
				'destroy' 	=> 'maintenance.assets.images.destroy',
			),
		));
		
                /*
                 * End Asset Image Upload Routes
                 */
                
                /*
                 * Asset Manual Upload Routes
                 */
                Route::post('assets/manuals/uploads', array(
			'as' => 'maintenance.assets.manuals.uploads.store',
			'uses' => 'AssetManualUploadController@store'
		));
		
		Route::post('assets/manuals/uploads/destroy', array(
			'as' => 'maintenance.assets.manuals.uploads.destroy',
			'uses' => 'AssetManualUploadController@destroy'
		));
                
                Route::resource('assets.manuals', 'AssetManualController', array(
                        'only' => array(
                            'index',
                            'create',
                            'store',
                            'destroy'
                        ),
			'names'=> array(
				'index'		=> 'maintenance.assets.manuals.index',
				'create'  	=> 'maintenance.assets.manuals.create',
				'store'   	=> 'maintenance.assets.manuals.store',
				'destroy' 	=> 'maintenance.assets.manuals.destroy',
			),
		));
                /*
                 * End Asset Manual Upload Routes
                 */
		
                /*
                 * Asset Routes
                 */
                Route::resource('assets', 'AssetController', array(
			'names'=> array(
				'index'		=> 'maintenance.assets.index',
				'create'  	=> 'maintenance.assets.create',
				'store'   	=> 'maintenance.assets.store',
				'show'    	=> 'maintenance.assets.show',
				'edit'    	=> 'maintenance.assets.edit',
				'update'  	=> 'maintenance.assets.update',
				'destroy' 	=> 'maintenance.assets.destroy',
			),
		));
                /*
                 * End Asset Routes
                 */
                
                Route::resource('inventory', 'InventoryController', array(
			'names'=> array(
				'index'		=> 'maintenance.inventory.index',
				'create'  	=> 'maintenance.inventory.create',
				'store'   	=> 'maintenance.inventory.store',
				'show'    	=> 'maintenance.inventory.show',
				'edit'    	=> 'maintenance.inventory.edit',
				'update'  	=> 'maintenance.inventory.update',
				'destroy' 	=> 'maintenance.inventory.destroy',
			),
		));
                
                Route::resource('inventory.stocks', 'InventoryStockController', array(
                        'only' => array(
                            'create',
                            'store',
                            'show',
                            'edit',
                            'update',
                            'destroy',
                        ),
			'names'=> array(
				'index'		=> 'maintenance.inventory.stocks.index',
				'create'  	=> 'maintenance.inventory.stocks.create',
				'store'   	=> 'maintenance.inventory.stocks.store',
				'show'    	=> 'maintenance.inventory.stocks.show',
				'edit'    	=> 'maintenance.inventory.stocks.edit',
				'update'  	=> 'maintenance.inventory.stocks.update',
				'destroy' 	=> 'maintenance.inventory.stocks.destroy',
			),
		));
                
                
                Route::resource('inventory.stocks.movements', 'InventoryStockController', array(
                        'only' => array(
                            'index'
                        ),
			'names'=> array(
				'index'		=> 'maintenance.inventory.stocks.movements.index',
			),
		));
                
                /*
                 * Global Attachment Routes
                 */
		Route::resource('attachments', 'AttachmentController', 
			array(
                            'only' => array(
                                    'destroy'
                            ),
                            'names' => array(
                                    'destroy' => 'maintenace.attachments.destroy',
                            ),
			)
		);
		/*
                 * End Global Attachment Routes
                 */
		
		
		/*
		|--------------------------------------------------------------------------
		| Maintenance Location Routes
		|--------------------------------------------------------------------------
		*/
		Route::get('locations/json', array(
				'as' => 'maintenance.locations.json',
				'uses' => 'LocationController@getJson',
			)
		);
		
		Route::get('locations/create/{categories?}', array(
				'as' => 'maintenance.locations.nodes.create',
				'uses' => 'LocationController@create',
			)
		);
		
		Route::post('locations/move/{categories?}', array(
			'as' => 'maintenance.locations.nodes.move',
			'uses'=> 'LocationController@postMoveCategory'
		));
		
		Route::post('locations/create/{categories?}', array(
				'as' => 'maintenance.locations.nodes.store',
				'uses' => 'LocationController@store',
			)
		);
		
		Route::resource('locations', 'LocationController', array(
			'names'=> array(
				'index'		=> 'maintenance.locations.index',
				'create'  	=> 'maintenance.locations.create',
				'store'   	=> 'maintenance.locations.store',
				'show'    	=> 'maintenance.locations.show',
				'edit'    	=> 'maintenance.locations.edit',
				'update'  	=> 'maintenance.locations.update',
				'destroy' 	=> 'maintenance.locations.destroy',
			),
		));
		
                
                /*
		|--------------------------------------------------------------------------
		| Maintenance Category Routes
		|--------------------------------------------------------------------------
		*/
		Route::get('categories/json', array(
				'as' => 'maintenance.categories.json',
				'uses' => 'CategoryController@getJson',
			)
		);
		
		Route::get('categories/create/{categories?}', array(
				'as' => 'maintenance.categories.nodes.create',
				'uses' => 'CategoryController@create',
			)
		);
		
		Route::post('categories/move/{categories?}', array(
			'as' => 'maintenance.categories.nodes.move',
			'uses'=> 'CategoryController@postMoveCategory'
		));
		
		Route::post('categories/create/{categories?}', array(
				'as' => 'maintenance.categories.nodes.store',
				'uses' => 'CategoryController@store',
			)
		);
		
		Route::resource('categories', 'CategoryController', array(
			'names'=> array(
				'index'		=> 'maintenance.categories.index',
				'create'  	=> 'maintenance.categories.create',
				'store'   	=> 'maintenance.categories.store',
				'show'    	=> 'maintenance.categories.show',
				'edit'    	=> 'maintenance.categories.edit',
				'update'  	=> 'maintenance.categories.update',
				'destroy' 	=> 'maintenance.categories.destroy',
			),
		));
		
	}); /* End Maintenance Controller Routes */

	
	/*
	|--------------------------------------------------------------------------
	| Maintenance JSON API Routes
	|--------------------------------------------------------------------------
	*/
	Route::group(array('prefix'=>'api', 'namespace'=>'Stevebauman\Maintenance\Http\Apis'), function(){
		
		
		/*
		|--------------------------------------------------------------------------
		| Maintenance Work Order Api Routes
		|--------------------------------------------------------------------------
		*/
		Route::group(array('prefix'=>'work-orders'), function(){
			Route::get('', array('as'=>'maintenance.api.work-orders.get', 'uses'=>'WorkOrderApi@get'));
			Route::get('makes', array('as'=>'maintenance.api.work-orders.makes', 'uses'=>'WorkOrderApi@getMakes'));
			Route::get('models', array('as'=>'maintenance.api.work-orders.models', 'uses'=>'WorkOrderApi@getModels'));
			Route::get('serials', array('as'=>'maintenance.api.work-orders.serials', 'uses'=>'WorkOrderApi@getSerials'));
			Route::get('{work_orders}', array('as'=>'maintenance.api.work-orders.find', 'uses'=>'WorkOrderApi@find'));
		});
	
		/*
		|--------------------------------------------------------------------------
		| Maintenance Asset Api Routes
		|--------------------------------------------------------------------------
		*/
                Route::group(array('prefix'=>'assets'), function(){
			Route::get('', array('as'=>'maintenance.api.assets.get', 'uses'=>'AssetApi@get'));
                        Route::get('q', array('as'=>'maintenance.api.assets.query', 'uses'=>'AssetApi@getByQuery'));
		});
                
                Route::resource('inventory.stocks', 'InventoryStockApi', array(
                    'only' => array(
                        'edit',
                        'update'
                    ),
                    'names' => array(
                        'edit'    	=> 'maintenance.api.inventory.stocks.edit',
                        'update'  	=> 'maintenance.api.inventory.stocks.update',
                    ),
                ));
                
                /*
		|--------------------------------------------------------------------------
		| Maintenance Calendar Api Routes
		|--------------------------------------------------------------------------
		*/
                Route::group(array('prefix'=>'calendar'), function(){
                    
                    Route::resource('events', 'EventApi', array(
			'names'=> array(
				'index'		=> 'maintenance.api.calendar.events.index',
				'create'  	=> 'maintenance.api.calendar.events.create',
				'store'   	=> 'maintenance.api.calendar.events.store',
				'show'    	=> 'maintenance.api.calendar.events.show',
				'edit'    	=> 'maintenance.api.calendar.events.edit',
				'update'  	=> 'maintenance.api.calendar.events.update',
				'destroy' 	=> 'maintenance.api.calendar.events.destroy',
			),
                    ));
                    
                    Route::resource('events/assets', 'AssetEventApi', array(
                        'only' => array(
                            'index',
                            'show',
                        ),
			'names'=> array(
				'index' => 'maintenance.api.calendar.events.assets.index',
                                'show' => 'maintenance.api.calendar.events.assets.show',
			),
                    ));
                    
                });
	}); /* End Maintenance API Routes */
	
	
	
}); /* End Maintenance Routes */