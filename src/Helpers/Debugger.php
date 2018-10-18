<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 7/12/2018
 * Time: 11:42 AM
 */

namespace Mondovo\Debugger\Helpers;

use Mondovo\Debugger\Contracts\DebuggerInterface;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DB;
use Mondovo\Debugger\Repositories\DebuggerLogParentRepository;
use Mondovo\Debugger\Repositories\DebuggerLogRepository;

class Debugger implements DebuggerInterface
{
    protected $output_type;

    private $message = '';

    private $subject = '';

    private $properties = '';

    private $isError = false;

    private $slackChannel = '';

    private $dbLogging = true;

    private $messageLevel = '';

    private $slackPreUrl = "https://slack.com/api/";

    private $slackPostUrl = "&as_user=true&pretty=1";

    private $slackChannelId = '';

    private $slackUserId = '';

    private $slackUsername = '';

    private $lastSlackApiError = '';

    private $slackFieldAttachments = '';

    private $firstCallTime = '';

    private $lastCallTime = '';

    private $firstTimeMemoryUsage = '';

    private $lastTimeMemoryUsage = '';

    private $slackTextAttachment = '';

    private $timeFromStart = '';

    private $timeFromPrevious = '';

    private $usageFromStart = '';

    private $usageFromPrevious = '';

    private $debuggerparent = '';

    private $debuggerId = '';

    private $disableLogging = false;

    private $disableCritical = false;

    private $stepNo = 0;

    private $dbMessage = '';

    private $debug_file = '';

    private $debug_class = '';

    private $debug_function = '';

    private $debug_line = '';

    private $debug_args = '';

    private $function_args = '';

    /**
     * @return string
     */
    public function getSlackPreUrl(): string
    {
        return $this->slackPreUrl;
    }

    /**
     * @param string $slackPreUrl
     */
    public function setSlackPreUrl(string $slackPreUrl)
    {
        $this->slackPreUrl = $slackPreUrl;
    }

    /**
     * @return string
     */
    public function getDbMessage(): string
    {
        return $this->dbMessage;
    }

    /**
     * @param string $dbMessage
     */
    public function setDbMessage(string $dbMessage)
    {
        $this->dbMessage = $dbMessage;
    }

    /**
     * @return string
     */
    public function getDbProperties(): string
    {
        return $this->dbProperties;
    }

    /**
     * @param string $dbProperties
     */
    public function setDbProperties(string $dbProperties)
    {
        $this->dbProperties = $dbProperties;
    }

    private $dbProperties = '';

    public function __construct()
    {
        $slackUserToken =  config('debugger.slack_user_token');
        if($slackUserToken == '' || $slackUserToken == 'undefined' || $slackUserToken == 'null'){
            throw new \Exception('Please define the SLACK_USER_TOKEN key in your .env file for the Debugger to work.');
            return false;
        }
        $dbLoggingDefault = config('debugger.database_logging');
        $this->setDbLogging($dbLoggingDefault);
    }

    /**
     * @return string
     */
    public function getLastSlackApiError(): string
    {
        return $this->lastSlackApiError;
    }

    /**
     * @param string $lastSlackApiError
     */
    public function setLastSlackApiError(string $lastSlackApiError)
    {
        $this->lastSlackApiError = $lastSlackApiError;
    }


    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        if (is_object($message)){
            $message_array = (array)$message;
            $message = json_encode($message_array);
            $this->setDbMessage($message);
            $message = route('baf_home')."/debugger_log_details/".$this->debuggerId;

        }
        elseif(is_array($message)){
            $setSize = memory_get_usage();
            $message = json_encode($message);
            $messageSizeinBytes = memory_get_usage() - $setSize;
            if($messageSizeinBytes > '4096'){
                $this->setDbProperties($message);
                $message = route('baf_home')."/debugger_log_details/".$this->debuggerId;
            }
        }
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties($properties)
    {
        if(is_array($properties)){
            $setSize = memory_get_usage();
            $properties = json_encode($properties);
            $propertySizeinBytes = memory_get_usage() - $setSize;
            if($propertySizeinBytes > '4096'){
                $this->setDbProperties($properties);
                $properties = route('baf_home')."/debugger_log_details/".$this->debuggerId;
            }
        }
        elseif(is_object($properties)){
            $properties_array = (array)$properties;
            $properties = json_encode($properties_array);
            $this->setDbProperties($properties);
            $properties = route('baf_home')."/debugger_log_details/".$this->debuggerId;
    }

        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function getSlackChannel(): string
    {
        return $this->slackChannel;
    }

    /**
     * @param string $slackChannel
     */
    public function setSlackChannel(string $slackChannel)
    {
        $this->slackChannel = $slackChannel;
    }

    /**
     * @return bool
     */
    public function isDbLogging(): bool
    {
        return $this->dbLogging;
    }

    /**
     * @param bool $dbLogging
     */
    public function setDbLogging(bool $dbLogging)
    {
        $this->dbLogging = $dbLogging;
    }

    /**
     * @return string
     */
    public function getMessageLevel(): string
    {
        return $this->messageLevel;
    }

    /**
     * @param string $messageLevel
     */
    public function setMessageLevel(string $messageLevel)
    {
        $this->messageLevel = $messageLevel;
    }

    /**
     * @return bool
     */
    public function isIsError(): bool
    {
        return $this->isError;
    }

    /**
     * @param bool $isError
     */
    public function setIsError(bool $isError)
    {
        $this->isError = $isError;
    }

    /**
     * @return string
     */
    public function getSlackChannelId(): string
    {
        return $this->slackChannelId;
    }

    /**
     * @param string $slackChannelId
     */
    public function setSlackChannelId(string $slackChannelId)
    {
        $this->slackChannelId = $slackChannelId;
    }

    /**
     * @return string
     */
    public function getSlackUserId($slackUsername=""): string
    {
        if($slackUsername=="")
        {
            return $this->slackUserId;
        }
        return $this->setSlackUsername($slackUsername);

    }

    /**
     * @param string $slackUserId
     */
    public function setSlackUserId(string $slackUserId)
    {
        return $this->slackUserId = $slackUserId;
    }

    /**
     * @return string
     */
    public function getFirstCallTime(): string
    {
        return $this->firstCallTime;
    }

    /**
     * @param string $firstCallTime
     */
    public function setFirstCallTime(string $firstCallTime)
    {
        $this->firstCallTime = $firstCallTime;
    }

    /**
     * @return string
     */
    public function getLastCallTime(): string
    {
        return $this->lastCallTime;
    }

    /**
     * @param string $lastCallTime
     */
    public function setLastCallTime(string $lastCallTime)
    {
        $this->lastCallTime = $lastCallTime;
    }

    /**
     * @return string
     */
    public function getFirstTimeMemoryUsage(): string
    {
        return $this->firstTimeMemoryUsage;
    }

    /**
     * @param string $firstTimeMemoryUsage
     */
    public function setFirstTimeMemoryUsage(string $firstTimeMemoryUsage)
    {
        $this->firstTimeMemoryUsage = $firstTimeMemoryUsage;
    }

    /**
     * @return string
     */
    public function getLastTimeMemoryUsage(): string
    {
        return $this->lastTimeMemoryUsage;
    }

    /**
     * @param string $lastTimeMemoryUsage
     */
    public function setLastTimeMemoryUsage(string $lastTimeMemoryUsage)
    {
        $this->lastTimeMemoryUsage = $lastTimeMemoryUsage;
    }

    /**
     * @return mixed
     */
    public function getSlackFieldAttachments()
    {
        return $this->slackFieldAttachments;
    }

    /**
     * @param array $slackFieldAttachments
     */
    public function setSlackFieldAttachments($slackFieldAttachments)
    {
        $this->slackFieldAttachments = $slackFieldAttachments;
    }

    /**
     * @return array
     */
    public function getSlackTextAttachment()
    {
        return $this->slackTextAttachment;
    }

    /**
     * @param array $slackTextAttachment
     */
    public function setSlackTextAttachment($slackTextAttachment)
    {
        $this->slackTextAttachment = $slackTextAttachment;
    }

    /**
     * @return string
     */
    public function getSlackUsername(): string
    {
        return $this->slackUsername;
    }

    /**
     * @return bool
     */
    public function isDisableLogging(): bool
    {
        return $this->disableLogging;
    }

    /**
     * @param bool $disableLogging
     */
    public function setDisableLogging(bool $disableLogging)
    {
        $this->disableLogging = $disableLogging;
    }

    /**
     * @return bool
     */
    public function isDisableCritical(): bool
    {
        return $this->disableCritical;
    }

    /**
     * @param bool $disableCritical
     */
    public function setDisableCritical(bool $disableCritical)
    {
        $this->disableCritical = $disableCritical;
    }

    /**
     * @return string
     */
    public function getStepNo(): string
    {
        return $this->stepNo;
    }

    /**
     * @param string $stepNo
     */
    public function setStepNo()
    {
        $stepNo = $this->stepNo + 1;
        $this->stepNo = $stepNo;
    }

    /**
     * @param string $slackUsername
     */
    public function setSlackUsername(string $slackUsername)
    {
        if($slackUsername[0]!="@"){
            $slackUsername="@".$slackUsername;
        }
        $this->slackUsername = $slackUsername;

        $slackUserId = config('debugger.slack_predefined_users')[$slackUsername];

        if($slackUserId){
            return $this->setSlackUserId($slackUserId);
        }else{
            throw new \Exception("Invalid Slack Username passed. Please ensure the Slack Username and it's associated User ID has been defined in the Recorder Config.");
       }
    }

    public function init($defaultSubject,$defaultSlackChannel,$defaultSlackUsername,$defaultDbLogging="")
    {
        $environment_set = config('constants.app_env');
        $production_environments = config('debugger.production_environments');
        if(in_array($environment_set, $production_environments)){
            $this->setDisableLogging(false);
            return false;
        }
        $this->setSubject($defaultSubject);
        $this->setSlackChannel($defaultSlackChannel);
        $this->setSlackUsername($defaultSlackUsername);
        if($defaultDbLogging!="")
            $this->setDbLogging($defaultDbLogging);

    }

    public function appendToMessage($message){
        $this->setMessage($this->getMessage()."\n".$message);
    }

    public function critical($message,$properties = '',$subject = '',$slackChannel = '',$slackUsername='')
    {
        if($this->isDisableCritical()){
            return true;
        }

        return $this->log($message,$properties,$subject,$slackChannel,$slackUsername,true,true,true);
    }


    //Log function
    public function log($message,$properties = null,$subject = '',$slackChannel = '',$slackUsername='',$isError = false,$dbLogging = true,$bypass = false)
    {
        $this->setDbMessage('');

        $this->setDbProperties('');

        $this->debuggerId = '';

        if($this->isDisableLogging() && !$bypass){
            return true;
        }

        $this->setStepNo();

        if($this->firstCallTime == "") {
            $this->setFirstCallTime(time());
            $this->setLastCallTime(time());
            $this->computeTimeTaken();
        }else{
            $this->computeTimeTaken();
            $this->setLastCallTime(time());
        }

        if($this->firstTimeMemoryUsage == ""){
            $this->setFirstTimeMemoryUsage(memory_get_usage());
            $this->setLastTimeMemoryUsage(memory_get_usage());
            $this->computeMemoryUsage();
        }else{
            $this->computeMemoryUsage();
            $this->setLastTimeMemoryUsage(memory_get_usage());
        }

        if($message){
            $this->setMessage($message);
        }

        if($subject){
            $this->setSubject($subject);
        }

        if(empty($properties)){
            $this->setProperties('');
        }elseif (!empty($properties)){
            $this->setProperties($properties);
        }


        if($dbLogging!="" && !$dbLogging){
            $this->withoutDb();
        }

        if($isError){
            $this->asError();
        }

        if($slackUsername!=""){
            $this->setSlackUsername($slackUsername);
        }

        if($slackChannel){
            $this->setSlackChannel($slackChannel);

        }

        $attachments = $this->debugBacktrace();

        // If dbLogging is enabled
        if($dbLogging){
            if($this->debuggerparent == "") {
                $this->debuggerparent = $this->insertDebuggerParentDetails($this->getMessage());
            }

            $dbMessage = ($this->getDbMessage() == '') ? $this->getMessage() : $this->getDbMessage();
            $dbProperties = ($this->getDbProperties() == '') ? $this->getProperties() : $this->getDbProperties();

            $this->debuggerId = $this->insertDebuggerDetails($dbMessage,$this->getSubject(),$dbProperties,$this->getSlackChannel(),$attachments['function'],$attachments['file'],$attachments['class'],$attachments['line'],$attachments['args'],$this->timeFromStart,$this->timeFromPrevious,$this->usageFromStart,$this->usageFromPrevious,$this->debuggerparent,$this->getStepNo());
        }

        if($message){
            $this->setMessage($message);
        }

        if(empty($properties)){
            $this->setProperties('');
        }elseif (!empty($properties)){
            $this->setProperties($properties);
        }

        $attachments = $this->constructAttachment();

        $this->setSlackFieldAttachments($attachments['attachment']);

        if($this->getSlackChannel()!=""){
            $channelId = $this->createChannel($this->getSlackChannel());
            $this->sendSlackMessage($channelId);
        }

        // If not error
        if($this->isError) {
            $this->asError();
            \Log::critical($this->message);
        }

        return true;

    }


    // If any Error
    public function asError()
    {
        $this->setIsError(true);
        $this->setMessageLevel('critical');
    }


    // If dblogging false
    public function withoutDb()
    {
        $this->setDbLogging(false);
    }

    // Function to create a channel if channel does not exists
    //If passes, will return channelId
    public function createChannel($channelName)
    {
        //$create_channel_url = $this->slackPreUrl."channels.create?token=".$recorder_token."&name=".$channel_name."&pretty=1";
        $response = $this->sendSlackApiRequest("channels.create",["name"=>$channelName]);
        if(!$response && $this->getLastSlackApiError()!="name_taken"){
           throw new \Exception("Channel could not be created");
        }

        if(($response->error == 'token_revoked') || ($response->error != 'name_taken')){
            throw new \Exception($response->error);
        }
        //mail_me('maifoes','Debugger error',json_encode($this->getLastSlackApiError()).'-'.json_encode($response));
        if($this->getLastSlackApiError()=="name_taken"){
            $channelId=$channelName;
            $this->setSlackChannelId($channelName);
        } else {
            $channelId = $response->channel->name;
            $this->setSlackChannelId($channelId);
        }

        $slackUsername = $this->getSlackUsername();

        if($slackUsername != "")
        {
            $slackUserId = $this->getSlackUserId($slackUsername);
            $this->inviteSlackUserToChannel($channelId,$slackUserId);
            return $channelId;
        }

        $slackUserId = $this->getSlackUserId($slackUsername);
        $this->inviteSlackUserToChannel($channelId,$slackUserId);

        return $channelId;


        //return json_decode($url_response)->ok;

    }

    private function inviteSlackUserToChannel($channelName,$slackUserId){
        $channelsList = $this->sendSlackApiRequest("channels.list",[]);
        $channelsList = objectToArray($channelsList->channels);
        foreach($channelsList as $channels){
            if($channels['name'] == strtolower($channelName)){
                $channelId = $channels['id'];
            }
        }

        $response = $this->sendSlackApiRequest("channels.invite",["channel"=>$channelId,"user"=>$this->slackUserId]);

        if(!$response){
            throw new \Exception("Could not invite user to the channel. Error: ".$this->getLastSlackApiError());
        }
        return true;
    }

    // Function to send a slack message
    public function sendSlackMessage($channelId)
    {

        $message = $this->getMessage();
        $this->setSlackTextAttachment($this->getProperties());
        $attachment = $this->getSlackFieldAttachments();

        $response = $this->sendSlackApiRequest("chat.postMessage",["channel"=>$channelId,"text"=>$message,"attachments"=>$attachment]);

        if(!$response){
            throw new \Exception("Could not send a message to the channel $channelId.  Error: ".$this->getLastSlackApiError());
        }

        return true;

    }

    private function sendSlackApiRequest($function,$parameters){
        $slackUserToken =  config('debugger.slack_user_token');
        $parameters['token']=$slackUserToken;
        $parameters['pretty']="1";
        $slackApiUrl = $this->buildUrl($this->slackPreUrl.$function,$parameters);
        //print_r($slackApiUrl)
        $guzzle = \App::make(Client::class);
        $response = $guzzle->request('POST', $slackApiUrl)->getBody()->getContents();
        $objectResponse = $this->handleJsonOutput($response,"object");
        $this->handleSlackException($objectResponse);
        return $objectResponse;
        //$slackApiUrl = $this->slackPreUrl.$function."?token=".$recorder_token."&channel=".$this->slackChannel."&text=".$this->message.$this->slackPostUrl;
    }

    private function handleSlackException($response){
        if(!$response->ok){
            $this->setLastSlackApiError($response->error);
            return false;

        }

        return true;
    }

    public function deleteOldLogs()
    {
        \App::make(DebuggerLogRepository::class)->deleteOldLogs();
    }


    /**
     * Function is public because we want to be able to test it + useful for other purposes as well
     * @param $url
     * @param array $parameters
     * @return string
     */
    public function buildUrl($url, array $parameters)
    {
        $query_parameters = http_build_query($parameters);
        $complete_url = $url . "?" . $query_parameters;
        return $complete_url;
    }

    /**
     * @param $complete_url
     * @return array
     */
    protected function callFileMethod($complete_url)
    {
        $output = file($complete_url);

        return $output;
    }

    /**
     * //$output_type: "json_response"/"json"/"array"/"object" - "json_response" is a ready made output that can be directly returned in the view
     * Default output_type is json_response
     * Output type can also be defined by the function setOutputType
     * @param $output_type
     * @param $api_output
     * @return mixed
     */
    protected function handleJsonOutput($api_output, $output_type="json_response")
    {

        $this->validateOutputTypes($output_type);
        if($this->getOutputType()!=""){
            //override parameter passed with the set type
            $output_type = $this->getOutputType();
        }

        if ($output_type == "array") {
            return json_decode($api_output, true);
        }

        if ($output_type == "object") {
            return json_decode($api_output);
        }

        if ($output_type == "json") {
            return $api_output;
        }

        return response($api_output)->header("Content-Type", "application/json");
    }

    /**
     * @return mixed
     */
    public function getOutputType()
    {
        return $this->output_type;
    }

    /**Allowed values: "json_response"/"json"/"array"/"object" else exception thrown
     * @param mixed $output_type
     */
    public function setOutputType($output_type)
    {
        $this->validateOutputTypes($output_type);
        $this->output_type = $output_type;
    }

    /**
     * @param $output_type
     * @throws \Exception
     */
    private function validateOutputTypes($output_type)
    {
        $allowed_types = ["json_response", "json", "array", "object"];
        if (!in_array($output_type, $allowed_types)) {
            throw new \Exception("Invalid Output Type declared.");
        }
    }


    public function constructAttachment()
    {

        $attachments = [['fields'=>[['title'=>'File','value'=>$this->debug_file,'short'=>false],['title'=>'Class','value'=>$this->debug_class,'short'=>false],['title'=>'Function','value'=>$this->debug_function,'short'=>false],['title'=>'Line','value'=>$this->debug_line,'short'=>false],['title'=>'Arguments','value'=>$this->function_args,'short'=>false],['title'=>'Properties','value'=>$this->getProperties(),'short'=>false],['title'=>'subject','value'=>$this->getSubject(),'short'=>false],['title'=>'Time from Start','value'=>$this->timeFromStart,'short'=>false],['title'=>'Memory from Start','value'=>$this->usageFromStart,'short'=>false]]],];

        $json_attachment = json_encode($attachments);

        $backtraceArray = array('attachment'=>$json_attachment,'file'=>$this->debug_file,'class'=>$this->debug_class,'function'=>$this->debug_function,'line'=>$this->debug_line,'args' => $this->function_args);

        return $backtraceArray;

    }

    public function debugBacktrace()
    {
        $backtrace = debug_backtrace();

        $this->debug_file = addslashes($backtrace[2]['file']);
        $this->debug_class = addslashes($backtrace[3]['class']);
        $this->debug_function = addslashes($backtrace[3]['function']);
        $this->debug_line = $backtrace[2]['line'];
        $this->debug_args = $backtrace[3]['args'];

        //dd($this->debug_args);
        if(!empty($this->debug_args)){
            $this->function_args = implode($this->debug_args,',');
            $this->function_args = json_encode($this->function_args);
        }else{
            $this->function_args = '';
        }

        $backtraceArray = array('file'=>$this->debug_file,'class'=>$this->debug_class,'function'=>$this->debug_function,'line'=>$this->debug_line,'args' => $this->function_args);

        return $backtraceArray;
    }

    public function computeTimeTaken()
    {
        $startTimeDiff = time() - $this->firstCallTime;
        $this->timeFromStart = round(abs($startTimeDiff/ 60*60) ,3).'Sec';
        $prevTimeDiff = time() - $this->lastCallTime;
        $this->timeFromPrevious = round(abs($prevTimeDiff/ 60*60) ,3).'Sec';
    }

    function computeMemoryUsage()
    {
        $startUsageDiff = (memory_get_usage() - $this->firstTimeMemoryUsage)* '0.000001';
        $this->usageFromStart = round($startUsageDiff,3).'Mb';
        $prevUsageDiff = (memory_get_usage() - $this->lastTimeMemoryUsage)* '0.000001';
        $this->usageFromPrevious =round($prevUsageDiff,3).'Mb';
    }

    public function insertDebuggerDetails($message,$subject,$properties,$channel_name,$function_name,$file_name,$class_name,$line_no,$function_arguments,$time_from_start,$time_from_previous,$memory_from_start,$memory_from_previous,$debugger_parent,$step_no)
    {
        return \App::make(DebuggerLogRepository::class)->insertInToLogs($message, $subject, $properties, $channel_name, $function_name, $file_name, $class_name, $line_no, $function_arguments, $time_from_start, $time_from_previous, $memory_from_start, $debugger_parent, $memory_from_previous, $step_no)->id;
    }


    public function insertDebuggerParentDetails($message)
    {
        return \App::make(DebuggerLogParentRepository::class)->insertInToParent()->id;
    }

}


