<?php

require_once 'vendor/autoload.php';

use EurekaClient\EurekaClient;
use EurekaClient\Instance\Instance;
use EurekaClient\Instance\Metadata;
use EurekaClient\Instance\DataCenterInfo;
use GuzzleHttp\Client;

$appName = 'new_app';
$instanceId = 'test_instance_id';

// Create app instance metadata.
$metadata = new Metadata();
$metadata->set('test_key', 'test_value');

// Create data center metadata.
$dataCenterMetadata = new Metadata();
$dataCenterMetadata->set('data_center_test_key', 'data_center_test_value');

// Create data center info (Amazon example).
$dataCenterInfo = new DataCenterInfo();
$dataCenterInfo
  ->setName('Amazon')
  ->setClass('com.netflix.appinfo.AmazonInfo')
  ->setMetadata($dataCenterMetadata);

// Create Eureka app instance.
$instance = new Instance();
$instance
  ->setInstanceId($instanceId)
  ->setHostName('test_host_name')
  ->setApp($appName)
  ->setIpAddr('127.0.0.1')
  ->setPort(80)
  ->setSecurePort(433)
  ->setHomePageUrl('http://localhost')
  ->setStatusPageUrl('http://localhost/status')
  ->setHealthCheckUrl('http://localhost/health-check')
  ->setSecureHealthCheckUrl('https://localhost/health-check')
  ->setVipAddress('test_vip_address')
  ->setSecureVipAddress('test_secure_vip_address')
  ->setMetadata($metadata)
  ->setDataCenterInfo($dataCenterInfo);

// Create an http client.
$httpClient = new Client();

// Eureka client usage example.
$eurekaClient = new EurekaClient('localhost', 8080, $httpClient);

try {
  // Register new application instance.
  $response = $eurekaClient->register($appName, $instance);

  // Query for all instances.
  $allApps = $eurekaClient->getAllApps();

  // Query for all application instances.
  $app = $eurekaClient->getApp($appName);

  // Query for a specific application instance.
  $appInstance = $eurekaClient->getAppInstance($appName, $instanceId);

  // Query for a specific instance.
  $instance = $eurekaClient->getInstance($instanceId);

  // Send application instance heartbeat.
  $response = $eurekaClient->heartBeat($appName, $instanceId);

  // Take instance out of service.
  $response = $eurekaClient->takeInstanceOut($appName, $instanceId);

  // Put instance back into service.
  $response = $eurekaClient->putInstanceBack($appName, $instanceId);

  // Update metadata.
  $response = $eurekaClient->updateAppInstanceMetadata($appName, $instanceId, [
    'new_key' => 'new_value',
  ]);

  // Query for all instances under a particular vip address/
  $instances = $eurekaClient->getInstancesByVipAddress('test_vip_address');

  // Query for all instances under a particular secure vip address.
  $instances = $eurekaClient->getInstancesBySecureVipAddress('test_secure_vip_address');

  // De-register application instance.
  $response = $eurekaClient->deRegister($appName, $instanceId);
}
catch (Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}
