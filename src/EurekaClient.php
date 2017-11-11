<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class EurekaClient
 */
class EurekaClient {

  /**
   * @var string host
   */
  private $host;

  /**
   * @var int port
   */
  private $port;

  /**
   * @var Client object
   */
  private $client;

  /**
   * @var string $context
   */
  private $context;

  /**
   * @return string
   */
  private function getEurekaUri() {
    return $this->host . ':' . $this->port . '/' . $this->context;
  }

  /**
   * EurekaClient constructor.
   *
   * @param string $host
   * @param int $port
   * @param string $context
   */
  public function __construct($host, $port, $context = 'eureka/v2') {
    $this->host = $host;
    $this->port = $port;
    $this->context = $context;
    $this->client = new Client([
      'headers' => [
        'Accept' => 'application/json',
      ],
    ]);
  }

  /**
   * Register app in eureka.
   *
   * @param string $appId
   * @param array $data
   *
   * @throws GuzzleException
   *
   * @return ResponseInterface
   */
  public function register($appId, array $data) {
    return $this->client->request('POST', $this->getEurekaUri() . '/apps/' . $appId, [
      'headers' => [
        'content-type' => 'application/json',
      ],
      'json' => [
       'instance' => $data,
      ],
    ]);
  }

  /**
   * De-register app from eureka.
   *
   * @param string $appId
   * @param string $instanceId
   *
   * @throws GuzzleException
   *
   * @return ResponseInterface
   */
  public function deRegister($appId, $instanceId) {
    return $this->client->request('DELETE', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId);
  }

  /**
   * Send app heartbeat.
   *
   * @param string $appId
   * @param string $instanceId
   *
   * @throws GuzzleException
   *
   * @return ResponseInterface
   */
  public function heartBeat($appId, $instanceId) {
    return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId);
  }

  /**
   * Get all registered applications.
   *
   * @throws GuzzleException
   *
   * @return array
   */
  public function getAllApps() {
    $response = $this->client->request('GET', $this->getEurekaUri() . ' /apps');

    return \GuzzleHttp\json_decode($response->getBody(), true);
  }

  /**
   * Get application.
   *
   * @param string $appId
   *
   * @throws GuzzleException
   *
   * @return array
   */
  public function getApp($appId) {
    $response = $this->client->request('GET', $this->getEurekaUri() . ' /apps/' . $appId);

    return \GuzzleHttp\json_decode($response->getBody(), true);
  }

  /**
   * Get application instance.
   *
   * @param string $appId
   * @param string $instanceId
   *
   * @throws GuzzleException
   *
   * @return array
   */
  public function getAppInstance($appId, $instanceId) {
    $response = $this->client->request('GET', $this->getEurekaUri() . ' /apps/' . $appId . '/' . $instanceId);

    return \GuzzleHttp\json_decode($response->getBody(), true);
  }

  /**
   * Get instance.
   *
   * @param string $instanceId
   *
   * @throws GuzzleException
   *
   * @return array
   */
  public function getInstance($instanceId) {
    $response = $this->client->request('GET', $this->getEurekaUri() . ' /instances/' . $instanceId);

    return \GuzzleHttp\json_decode($response->getBody(), true);
  }

  /**
   * Take instance out of the service.
   *
   * @param string $appId
   * @param string $instanceId
   *
   * @throws GuzzleException
   *
   * @return ResponseInterface
   */
  public function takeInstanceOut($appId, $instanceId) {
    return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId . '/status', [
      'query' => [
        'value' => 'OUT_OF_SERVICE',
      ],
    ]);
  }

  /**
   * Put instance back into the service.
   *
   * @param string $appId
   * @param string $instanceId
   *
   * @throws GuzzleException
   *
   * @return ResponseInterface
   */
  public function putInstanceBack($appId, $instanceId) {
    return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId . '/status', [
      'query' => [
        'value' => 'UP',
      ],
    ]);
  }

  /**
   * Update app instance metadata.
   *
   * @param string $appId
   * @param string $instanceId
   * @param array $metadata
   *
   * @throws GuzzleException
   *
   * @return ResponseInterface
   */
  public function updateAppInstanceMetadata($appId, $instanceId, array $metadata) {
    return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId . '/metadata', [
      'query' => $metadata,
    ]);
  }

  /**
   * Get all instances by a vip address.
   *
   * @param string $vipAddress
   *
   * @throws GuzzleException
   *
   * @return array
   */
  public function getInstancesByVipAddress($vipAddress) {
    $response = $this->client->request('GET', $this->getEurekaUri() . ' /vips/' . $vipAddress);

    return \GuzzleHttp\json_decode($response->getBody(), true);
  }

  /**
   * Get all instances by a secure vip address.
   *
   * @param string $secureVipAddress
   *
   * @throws GuzzleException
   *
   * @return array
   */
  public function getInstancesBySecureVipAddress($secureVipAddress) {
    $response = $this->client->request('GET', $this->getEurekaUri() . ' /svips/' . $secureVipAddress);

    return \GuzzleHttp\json_decode($response->getBody(), true);
  }

}
