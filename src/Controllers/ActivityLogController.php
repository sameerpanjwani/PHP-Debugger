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
        /*parent::__construct();*/

        $this->activity_log_display_service = $activity_log_display_service;
    }

    public function index()
    {
        $view = $this->activity_log_display_service->getViewData();
        $view['module_details']['main_module'] = 'daad';
        $view['module_details']['sub_module'] = 'daad';
        $view['website_properties']['white-label-logo'] = 'false';
        $view['website_properties']['logo'] = '';
        $view['website_properties']['circle-logo'] = 'daad';
        $view['admin_user_details']['admin_name'] = '';
        $view['lang_path'] = '';
        $view['current_page_url'] = '20';
        //$results = array_merge($module_details, $view);
        return view('pages.baf.activity-log', $view);

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
            $html = '<tr><td>'.'id'.'</td><td>'.$value->id.'</td></tr>
                    <tr><td>'.'message'.'</td><td>'.$value->message.'</td></tr>
                    <tr><td>'.'subject'.'</td><td>'.$value->subject.'</td></tr>
                    <tr><td>'.'properties'.'</td><td>'.$value->properties.'</td></tr>
                    <tr><td>'.'channel_name'.'</td><td>'.$value->channel_name.'</td></tr>
                    <tr><td>'.'function_name'.'</td><td>'.$value->function_name.'</td></tr>
                    <tr><td>'.'file_name'.'</td><td>'.$value->file_name.'</td></tr>
                     <tr><td>'.'class_name'.'</td><td>'.$value->class_name.'</td></tr>
                      <tr><td>'.'line_no'.'</td><td>'.$value->line_no.'</td></tr>
                       <tr><td>'.'function_arguments'.'</td><td>'.$value->function_arguments.'</td></tr>
                        <tr><td>'.'time_from_start'.'</td><td>'.$value->time_from_start.'</td></tr>
                         <tr><td>'.'time_from_previous'.'</td><td>'.$value->time_from_previous.'</td></tr>
                          <tr><td>'.'memory_from_start'.'</td><td>'.$value->memory_from_start.'</td></tr>
                           <tr><td>'.'memory_from_previous'.'</td><td>'.$value->memory_from_previous.'</td></tr>
                            <tr><td>'.'created_at'.'</td><td>'.$value->created_at.'</td></tr>
                             <tr><td>'.'log_parent'.'</td><td>'.$value->log_parent.'</td></tr>
                              <tr><td>'.'step_no'.'</td><td>'.$value->step_no.'</td></tr>';
        }
        $html = '<table><tbody>'.$html.'</tbody></table>';
        return $html;
    }

}
