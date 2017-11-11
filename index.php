<?php

use EurekaClient\EurekaClient;
use EurekaClient\Instance\Instance;
use EurekaClient\Instance\Metadata;
use EurekaClient\Instance\DataCenterInfo;

require_once 'vendor/autoload.php';

$instanceId = 'test_instance_id';
$appName = 'new_app';

$metadata = new Metadata();
$metadata->set('test_key', 'test_value');

$dataCenterMetadata = new Metadata();
$dataCenterMetadata->set('data_center_test_key', 'data_center_test_value');

$dataCenterInfo = new DataCenterInfo();
$dataCenterInfo
  ->setName('Amazon')
  ->setClass('com.netflix.appinfo.AmazonInfo')
  ->setMetadata($dataCenterMetadata);

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
  'new_key' => 'new_value',
]);
$instances = $eurekaClient->getInstancesByVipAddress('test_vip_address');
$instances = $eurekaClient->getInstancesBySecureVipAddress('test_secure_vip_address');
$response = $eurekaClient->deRegister($appName, $instanceId);
