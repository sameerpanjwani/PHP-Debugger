<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 9/25/2018
 * Time: 3:50 PM
 */

namespace Mondovo\Debugger\Repositories;
use Carbon\Carbon;
use Eloquent;

class DebuggerLogParentRepository extends Eloquent
{
    protected $table = 'debugger_log_parent';

    protected $fillable = [
        'created_at'
    ];

    public function insertInToParent(){
        return self::create(['created_at' => Carbon::now()]);
    }
}