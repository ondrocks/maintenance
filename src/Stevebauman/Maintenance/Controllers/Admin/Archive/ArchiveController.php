<?php

namespace Stevebauman\Maintenance\Controllers\Admin\Archive;

use Stevebauman\Maintenance\Controllers\BaseController;

class ArchiveController extends BaseController {
    
    public function getIndex()
    {
        return view('maintenance::admin.archive.index', array(
            'title' => 'Archive'
        ));
    }
    
}