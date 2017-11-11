<?php

require_once 'vendor/autoload.php';

$instanceId = 'test_instance_id';
$availabilityZone = 'test_availability_zone';
$appName = 'new_app';

$eurekaClient = new EurekaClient('localhost', 8080);
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

$response = $eurekaClient->register($appName, $eurekaRegistrationData);
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
