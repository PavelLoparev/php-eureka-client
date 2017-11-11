<?php

use GuzzleHttp\Client;

class EurekaClient {

  /**
   * @var string ip
   */
  private $ip;

  /**
   * @var int port
   */
  private $port;

  /**
   * @var Client object
   */
  private $client;

  /**
   * @var $context
   */
  private $context;

  /**
   * @return string
   */
  private function getEurekaApiUrl() {
    return $this->ip . ':' . $this->port . '/' . $this->context;
  }

  /**
   * EurekaClient constructor.
   * @param $ip
   * @param $port
   * @param $context
   */
  public function __construct($ip, $port, $context) {
    $this->ip = $ip;
    $this->port = $port;
    $this->context = $context;
    $this->client = new Client([
      'headers' => [
        'content-type' => 'application/json',
      ],
    ]);
  }

  /**
   * Register service in eureka
   * @param $appId
   * @param $data
   * @return mixed|\Psr\Http\Message\ResponseInterface
   */
  public function register($appId, $data) {
    return $this->client->request('POST', $this->getEurekaApiUrl() . '/apps/' . $appId, [
      'json' => [
       'instance' => $data,
      ],
    ]);
  }

  public function deRegister($appId, $instanceId) {
    return $this->client->request('DELETE', $this->getEurekaApiUrl() . '/apps/' . $appId . '/' . $instanceId);
  }

  public function heartBeat($appId, $instanceId) {
    return $this->client->request('PUT', $this->getEurekaApiUrl() . '/apps/' . $appId . '/' . $instanceId);
  }

}
