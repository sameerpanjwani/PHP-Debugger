<?php
/**
 * Created by PhpStorm.
 * User: radhi
 * Date: 7/18/2016
 * Time: 6:26 PM
 */

namespace Mondovo\Debugger\Services;


use Mondovo\Debugger\Components\ActivityLogComponents;
use Mondovo\Debugger\Contracts\ActivityLogDisplayServiceInterface;
use Mondovo\Debugger\Repositories\Db\DbDebuggerLogRepository;


class ActivityLogDisplayService implements ActivityLogDisplayServiceInterface
{
    protected $activity_log_components;

    protected $log_repository;

    public function __construct(ActivityLogComponents $activity_log_components,DbDebuggerLogRepository $log_repository)
    {
        parent::__construct();

        $this->activity_log_components = $activity_log_components;
        $this->log_repository = $log_repository;


    }
    public function getViewData(){
        $results = $this->activity_log_components->getComponentData();
        $results = array_merge($this->view, $results);
        return $results;
    }

    public function activityLogData(){
        $activity_log_list_data = $this->log_repository->activityLog();
        return $this->activity_log_components->ajaxForProxyListData($activity_log_list_data);
    }

    public function debuggerLogDetails($id){
        $debugger_log_details = $this->log_repository->firstDebuggerLogDetails($id);
        return $debugger_log_details;
    }

}