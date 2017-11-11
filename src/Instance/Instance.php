<?php

namespace EurekaClient\Instance;

class Instance extends Parameters {

  public function setInstanceId($instanceId) {
    return $this->set('instanceId', $instanceId);
  }

  public function setHostName($hostName) {
    return $this->set('hostName', $hostName);
  }

  public function setApp($app) {
    return $this->set('app', $app);
  }

  public function setIpAddr($ipAddr) {
    return $this->set('ipAddr', $ipAddr);
  }

  public function setPort($port, $enabled = true) {
    return $this->set('port', [
      '$' => $port,
      '@enabled' => ($enabled) ? 'true' : 'false',
    ]);
  }

  public function setSecurePort($port, $enabled = true) {
    return $this->set('securePort', [
      '$' => $port,
      '@enabled' => ($enabled) ? 'true' : 'false',
    ]);
  }

  public function setHomePageUrl($homePageUrl) {
    return $this->set('homePageUrl', $homePageUrl);
  }

  public function setStatusPageUrl($statusPageUrl) {
    return $this->set('statusPageUrl', $statusPageUrl);
  }

  public function setHealthCheckUrl($healthCheckUrl) {
    return $this->set('healthCheckUrl', $healthCheckUrl);
  }

  public function setSecureHealthCheckUrl($secureHealthCheckUrl) {
    return $this->set('secureHealthCheckUrl', $secureHealthCheckUrl);
  }

  public function setVipAddress($vipAddress) {
    return $this->set('vipAddress', $vipAddress);
  }

  public function setSecureVipAddress($secureVipAddress) {
    return $this->set('secureVipAddress', $secureVipAddress);
  }

  public function setMetadata(Metadata $metadata) {
    return $this->set('metadata', $metadata->export());
  }

  public function setDataCenterInfo(DataCenterInfo $dataCenterInfo) {
    return $this->set('dataCenterInfo', $dataCenterInfo->export());
  }

}
