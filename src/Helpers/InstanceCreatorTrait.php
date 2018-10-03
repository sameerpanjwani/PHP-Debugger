<?php
/**
 * Created by PhpStorm.
 * User: maximizer
 * Date: 9/6/15
 * Time: 8:09 AM
 */

namespace App\Helpers;

use App\Helpers\Select2\MySelect2;
use Mondovo\DataTable\MyDataTable;

trait InstanceCreatorTrait {

    /**
     * Always return instance of class MyDatatable
     * @return MyDatatable New Instance
     */
    public function getDataTableInstance()
    {
        return \App::make(MyDataTable::class);
    }

    /**
     * Always return instance of class MySelect2
     * @return MySelect2 New Instance
     */
    public function getSelect2Instance()
    {
        return \App::make(MySelect2::class);
    }

}