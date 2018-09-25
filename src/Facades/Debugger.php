<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 7/12/2018
 * Time: 1:03 PM
 */

namespace Mondovo\Debugger\Facades;


use Illuminate\Support\Facades\Facade;

class Debugger extends Facade
{
    protected static function getFacadeAccessor() { return 'debugger'; }
}