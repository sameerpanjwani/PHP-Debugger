<?php namespace Mondovo\Debugger\Contracts;


interface DebuggerLogRepositoryInterface
{
    public function activityLog();

    public function DebuggerLogDetails($id);
    
}