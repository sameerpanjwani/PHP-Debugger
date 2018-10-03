<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 7/24/2018
 * Time: 4:14 PM
 */

namespace Mondovo\Debugger\Controllers;

use Mondovo\Debugger\Contracts\ActivityLogDisplayServiceInterface;

class ActivityLogController
{
    protected $activity_log_display_service;

    public function __construct(ActivityLogDisplayServiceInterface $activity_log_display_service)
    {
        parent::__construct();

        $this->activity_log_display_service = $activity_log_display_service;
    }

    public function index()
    {
        dd('ddd');
        $view = $this->activity_log_display_service->getViewData();
        $results = array_merge($this->view, $view);
        return view('pages.baf.activity-log', $results);

    }

    public function activityLogData()
    {
        $output = $this->activity_log_display_service->activityLogData();
        return $output;
    }

    public function debuggerLogDetails($id = '')
    {
        $details = $this->activity_log_display_service->debuggerLogDetails($id);
        return $this->constructDebuggerPage($details);
    }

    public function constructDebuggerPage($details)
    {
        $html = '';
        foreach($details as $key => $value){
            /*if((($key == 'message') || ($key == 'properties')) && ($value[0] == '{')){
                dd($value);
            }*/
            $html = $html.'<tr><td>'.$key.'</td><td>'.$value.'</td>';
        }
        $html = '<table><tbody>'.$html.'</tbody></table>';
        return $html;
    }

}