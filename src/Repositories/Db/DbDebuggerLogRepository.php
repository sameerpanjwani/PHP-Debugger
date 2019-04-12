<?php namespace Mondovo\Debugger\Repositories\Db;



use DB;
use Mondovo\Debugger\Contracts\DebuggerLogRepositoryInterface;

class DbDebuggerLogRepository implements DebuggerLogRepositoryInterface
{
    public function activityLog()
    {
        $select = [
            'id',
            'log_parent as parent_id',
            'step_no',
            'message',
            'subject',
            'properties',
            'channel_name',
            'function_name',
            'file_name',
            'class_name',
            'line_no',
            'function_arguments',
            'time_from_start',
            'time_from_previous',
            'memory_from_start',
            'memory_from_previous',
            'created_at'
        ];
        return DB::table('debugger_log')
            ->select($select);
    }

    public function DebuggerLogDetails($id)
    {
        return DB::table('debugger_log')
            ->where('id','=',$id)->get();
    }

}
