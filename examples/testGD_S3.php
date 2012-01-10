<?php
include("../IronWorker.class.php");

$config = parse_ini_file('config.ini', true);

$name = "testGD_S3.php-".microtime(true);

$iw = new IronWorker('config_sw.ini');

# using default project_id from config
$project_id = "";

# Creating zip package.
$zipName = "code/$name.zip";
IronWorker::zipDirectory(dirname(__FILE__)."/workers/draw_gd_and_upload_to_s3", $zipName, true);

# Posting package.
$res = $iw->postCode($project_id, 'gd_s3.php', $zipName, $name);


$payload = array(
    's3' => array(
        'access_key' => $config['s3']['access_key'],
        'secret_key' => $config['s3']['secret_key'],
        'bucket'     => $config['s3']['bucket'],
    ),
    'image_url' => 'http://www.iron.io/assets/banner-mq-bg.jpg',
    'text'      => 'Hello from Iron Worker!'
);

# Adding new task.
$task_id = $iw->postTask($project_id, $name, $payload);
echo "task_id = $task_id \n";

sleep(10);

$details = $iw->getTaskDetails($project_id, $task_id);
print_r($details);
$log = $iw->getLog($project_id, $task_id);
print_r($log);

