<?php

use EurekaClient\EurekaClient;
use EurekaClient\Instance\Instance;
use EurekaClient\Instance\Metadata;
use EurekaClient\Instance\DataCenterInfo;

require_once 'vendor/autoload.php';

$instanceId = 'test_instance_id';
$availabilityZone = 'test_availability_zone';
$appName = 'new_app';

$metadata = new Metadata();
$metadata->set('test_key', 'test_value');

$dataCenterInfo = new DataCenterInfo();
$dataCenterInfo
  ->setName('MyOwn')
  ->setClass('com.netflix.appinfo.InstanceInfo$DefaultDataCenterInfo');

$instance = new Instance();
$instance
  ->setInstanceId('test_instance_id')
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

$eurekaRegistrationData = [
  'instanceId' => $instanceId,
  'hostName' => 'test_host_name',
  'app' => $appName,
  'ipAddr' => 'test_ip_addr',
  'port' => [
    '$' => 80,
    '@enabled' => 'true',
  ],
  'securePort' => [
    '$' => 443,
    '@enabled' => 'true',
  ],
  'homePageUrl' => 'http://' . $appName,
  'statusPageUrl' => 'http://' . $appName,
  'healthCheckUrl' => 'http://' . $appName,
  'secureHealthCheckUrl' => 'https://' . $appName,
  'vipAddress' => 'vip_address',
  'secureVipAddress' => 'secure_vip_address',
  'metadata' => [
    'test' => 'test',
  ],
  'dataCenterInfo' => [
    'name' => 'MyOwn',
    '@class' => 'com.netflix.appinfo.InstanceInfo$DefaultDataCenterInfo',
  ],
];

$eurekaClient = new EurekaClient('localhost', 8080);
$response = $eurekaClient->register($appName, $instance);
$allApps = $eurekaClient->getAllApps();
$app = $eurekaClient->getApp($appName);
$appInstance = $eurekaClient->getAppInstance($appName, $instanceId);
$instance = $eurekaClient->getInstance($instanceId);
$response = $eurekaClient->heartBeat($appName, $instanceId);
$response = $eurekaClient->takeInstanceOut($appName, $instanceId);
$response = $eurekaClient->putInstanceBack($appName, $instanceId);
$response = $eurekaClient->updateAppInstanceMetadata($appName, $instanceId, [
  'test' => 'new_value_1',
]);
$instances = $eurekaClient->getInstancesByVipAddress('vip_address');
$instances = $eurekaClient->getInstancesBySecureVipAddress('secure_vip_address');
$response = $eurekaClient->deRegister($appName, $instanceId);
$test = 0;
