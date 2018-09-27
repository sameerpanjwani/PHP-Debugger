# PHP-Debugger  
 Debugger is a another method to debug the code where we can see the result in slack and baf path
 
 ## installation
 
 ## Requirements
 * laravel
 
 
 ## Usage
 * slack_user_token has to be defined in .env file
 * slack usernames has to be defined in a config file
 
 use Mondovo\Debugger\Facades\Debugger;
 
 ##Development
 Debugger can be used in three ways
 
 * Define init function in construct and use function log
 
    * Debugger::init('subject','channel name','slackusername')
    
        * subject -> slack subject
        * channel name-> slack will send message to this particular channel if channel doesnot exists it creates new channel and send
          
        * username ->slack username
    
    * Debugger::log('message','properties','subject','slackChannel','slackUsername','isError', 'dbLogging')
                  
      * message -> message want to send, message can be object,array,string
      * properties -> set properties, properties can be set as string, object , array
      * subject -> subject is 'optional' if init is set
      * slackchannel -> 'optional' if init is set
      * slackusername -> 'optional' if init is set
      * isError -> 'optional' set true if we want to invoke this as a critical one
      * dbLogging -> 'optional' set false if not to be save in database
      
  * Call log function directly
    * Debugger::log('message','properties','subject','slackChannel','slackUsername','isError', 'dbLogging')
                 
         * message -> message want to send, message can be object,array,string
         * properties -> set properties, properties can be set as string, object , array
         * subject -> subject is 'optional' if init is set
         * slackchannel -> 'optional' if init is set
         * slackusername -> 'optional' if init is set
         * isError -> 'optional' set true if we want to invoke this as a critical one
         * dbLogging -> 'optional' set false if not to be save in database 
      
  * Call Critical function directly
    * critical function can be used calling init function in costruct function and calling critical function or critical function only
    * calling critical function intimate it as a critical bug and it invokes critical functionality 
    
    * public function critical('message','properties',subject'','slackChannel','slackUsername')
    
        * message -> message want to send, message can be object,array,string
        * properties -> set properties, properties can be set as string, object , array
        * subject -> subject is 'optional' if init is set
        * slackchannel -> 'optional' if init is set
        * slackusername -> 'optional' if init is set 
