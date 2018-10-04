<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 7/25/2018
 * Time: 11:03 AM
 */

namespace Mondovo\Debugger\Components;

use Mondovo\DataTable\MyDataTable;

class ActivityLogComponents
{
    protected $language_path;

    protected $activity_log_list;

    public function __construct()
    {
        /*parent::__construct();*/
        $this->language_path = [
                                    'activity_log' => 'Debugger Log',
                                ];

        $this->activity_log_list = \App::make(MyDataTable ::class);
    }

    public function getLanguagePath()
    {
        return $this->language_path;
    }

    public function getComponentData()
    {
        $activity_log_list = $this->getActivityLogData();
        $this->view["activity_log_list_data"] = $activity_log_list->drawHtml();
        $this->view["activity_log_list_data_js"] = $activity_log_list->drawJs();

        return $this->view;
    }


    public function getActivityLogData()
    {
        $activity_log_columns = [
            'id|data-filter-type:text|min-width:130px',
            'parent_id|data-filter-type:text|min-width:130px',
            'step_no|data-filter-type:text|min-width:130px',
            'message|data-filter-type:text|min-width:130px',
            'subject|data-filter-type:text|min-width:130px',
            'properties|data-filter-type:text|min-width:130px',
            'channel_name|data-filter-type:text|min-width:130px',
            'function_name|data-filter-type:text|min-width:130px',
            'file_name|data-filter-type:text|min-width:130px',
            'class_name|data-filter-type:text|min-width:130px',
            'line_no|data-filter-type:text|min-width:130px',
            'function_arguments|data-filter-type:text|min-width:130px',
            'time_from_start|data-filter-type:text|min-width:130px',
            'time_from_previous|data-filter-type:text|min-width:130px',
            'memory_from_start|data-filter-type:text|min-width:130px',
            'memory_from_previous|data-filter-type:text|min-width:130px',
            'created_at|data-filter-type:text|min-width:130px',

        ];
        //$activity_log_list = $this->getDataTableInstance();
        $this->language_path = "";
        return $this->activity_log_list->setTableId('ActivityLog')
            ->setColumnDefinitionsWithAlias($activity_log_columns, $this->language_path)
            //->setColumnDefinitions($activity_log_columns)
            ->setCheckboxColumnsNameInJs('id')
            ->enableFilter()
            ->showExportButton()
            //->enableCopyToClipboardByColumnName()
            ->setAjaxUrl('ajax/activity-log-data');
        //return $activity_log_list;
    }

    public function ajaxForProxyListData($activity_log_list_data)
    {
        //$activity_log_list = $this->getDataTableInstance();
        return $this->activity_log_list->disableCache()->of($activity_log_list_data)
            ->editColumn('status', function ($data) {
                return $this->editColumnForLiveStatus($data['status']);
            })
            ->editColumn('properties', function ($data) {
                //return implode("|", $data);
                return "<a href='" . route('debugger_log_details',$data['id'] ) . "' target='_blank' title='Click to view Detail'>" . substr($data['properties'],'0','10') . "</a>";
            })
            ->editColumn('message', function ($data) {
                //return implode("|", $data);
                return "<a href='" . route('debugger_log_details',$data['id'] ) . "' target='_blank' title='Click to view Detail'>" . substr($data['message'],'0','10') . "</a>";
            })
            ->make(true);
    }


}