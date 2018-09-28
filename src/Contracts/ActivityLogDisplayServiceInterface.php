<?php
/**
 * Created by PhpStorm.
 * User: Maifoes
 * Date: 4/20/2018
 * Time: 11:55 AM
 */

namespace Mondovo\Debugger\Contracts;


interface ActivityLogDisplayServiceInterface
{
    public function getViewData();

    public function activityLogData();

    public function debuggerLogDetails($id);

}