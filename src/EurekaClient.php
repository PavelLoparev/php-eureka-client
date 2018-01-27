<?php

namespace EurekaClient;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use EurekaClient\Instance\Instance;

/**
 * Class EurekaClient
 *
 * @see https://github.com/Netflix/eureka/wiki/Eureka-REST-operations Eureka REST operations
 */
class EurekaClient
{
    /**
     * @var string host
     */
    private $host;

    /**
     * @var int port
     */
    private $port;

    /**
     * @var ClientInterface object
     */
    private $client;

    /**
     * @var string $context
     */
    private $context;

    /**
     * @return string
     */
    private function getEurekaUri()
    {
        return $this->host . ':' . $this->port . '/' . $this->context;
    }

    /**
     * EurekaClient constructor.
     *
     * @param string $host
     * @param int $port
     * @param ClientInterface $client
     * @param string $context
     */
    public function __construct($host, $port, ClientInterface $client, $context = 'eureka/v2 ')
    {
        $this->host = $host;
        $this->port = $port;
        $this->context = $context;
        $this->client = $client;
    }

    /**
     * Register app in eureka.
     *
     * @param string $appId
     * @param Instance $data
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    public function register($appId, Instance $data)
    {
        return $this->client->request('POST', $this->getEurekaUri() . '/apps/' . $appId, [
            'json' => [
                'instance' => $data->export()
            ]
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
    public function deRegister($appId, $instanceId)
    {
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
    public function heartBeat($appId, $instanceId)
    {
        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId);
    }

    /**
     * Get all registered applications.
     *
     * @throws GuzzleException
     *
     * @return array
     */
    public function getAllApps()
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/apps', [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

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
    public function getApp($appId)
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/apps/' . $appId, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return \GuzzleHttp\json_decode($response->getBody(), true);
    }

    /**
     * Get application Instance.
     *
     * @param string $appId
     * @param string $instanceId
     *
     * @throws GuzzleException
     *
     * @return array
     */
    public function getAppInstance($appId, $instanceId)
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return \GuzzleHttp\json_decode($response->getBody(), true);
    }

    /**
     * Get Instance.
     *
     * @param string $instanceId
     *
     * @throws GuzzleException
     *
     * @return array
     */
    public function getInstance($instanceId)
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/instances/' . $instanceId, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return \GuzzleHttp\json_decode($response->getBody(), true);
    }

    /**
     * Take Instance out of the service.
     *
     * @param string $appId
     * @param string $instanceId
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    public function takeInstanceOut($appId, $instanceId)
    {
        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId . '/status', [
            'query' => [
                'value' => 'OUT_OF_SERVICE'
            ]
        ]);
    }

    /**
     * Put Instance back into the service.
     *
     * @param string $appId
     * @param string $instanceId
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    public function putInstanceBack($appId, $instanceId)
    {
        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId . '/status', [
            'query' => [
                'value' => 'UP'
            ]
        ]);
    }

    /**
     * Update app Instance metadata.
     *
     * @param string $appId
     * @param string $instanceId
     * @param array $metadata
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    public function updateAppInstanceMetadata($appId, $instanceId, array $metadata)
    {
        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId . '/metadata', [
            'query' => $metadata
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
    public function getInstancesByVipAddress($vipAddress)
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/vips/' . $vipAddress, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

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
    public function getInstancesBySecureVipAddress($secureVipAddress)
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/svips/' . $secureVipAddress, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return \GuzzleHttp\json_decode($response->getBody(), true);
    }
}
