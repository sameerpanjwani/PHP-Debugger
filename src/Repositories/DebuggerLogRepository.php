<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 9/25/2018
 * Time: 3:46 PM
 */

namespace Mondovo\Debugger\Repositories;
use Carbon\Carbon;
use Eloquent;

class DebuggerLogRepository extends Eloquent
{
    protected $table = 'debugger_log';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
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
        'created_at',
        'log_parent',
        'step_no'
    ];

    public function deleteOldLogs()
    {
        $deleteFrequency = config('debugger.delete_frequency');
        self::whereRaw("created_at < DATE_SUB(NOW(), INTERVAL ".$deleteFrequency." DAY)")->delete();
        DebuggerLogParentRepository::whereRaw("created_at < DATE_SUB(NOW(), INTERVAL ".$deleteFrequency." DAY)")->delete();

    }

    public function insertInToLogs($message, $subject, $properties, $channel_name, $function_name, $file_name, $class_name, $line_no, $function_arguments, $time_from_start, $time_from_previous, $memory_from_start, $debugger_parent, $memory_from_previous, $step_no)
    {
        $values = [
            'message' => $message,
            'subject' => $subject,
            'properties' => $properties,
            'channel_name' => $channel_name,
            'function_name' => $function_name,
            'file_name' => $file_name,
            'class_name' => $class_name,
            'line_no' => $line_no,
            'function_arguments' => $function_arguments,
            'time_from_start' => $time_from_start,
            'time_from_previous' => $time_from_previous,
            'memory_from_start' => $memory_from_start,
            'memory_from_previous' => $memory_from_previous,
            'created_at' => Carbon::now(),
            'log_parent' => $debugger_parent,
            'step_no' => $step_no

        ];
        return self::create($values);
    }
}