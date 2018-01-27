<?php

namespace EurekaClientTests;

use EurekaClient\EurekaClient;
use EurekaClient\Instance\DataCenterInfo;
use EurekaClient\Instance\Instance;
use EurekaClient\Instance\Metadata;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Class EurekaClientTest
 */
class EurekaClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var EurekaClient
     */
    private $eurekaClient;

    /**
     * @var string
     */
    private $appName;

    /**
     * @var string
     */
    private $instanceId;

    /**
     * @var array
     */
    private $metadata;

    /**
     * @var string
     */
    private $vipAddress;

    /**
     * @var string
     */
    private $secureVipAddress;

    /**
     * Test fixture.
     */
    public function setUp()
    {
        $this->httpClient = $this->getMockBuilder('GuzzleHttp\Client')
            ->setMethods(['request'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->response = $this->getMockBuilder('GuzzleHttp\Psr7\Response')
            ->setMethods(['getBody'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->response->expects($this->any())
            ->method('getBody')
            ->willReturn('{}');

        $this->eurekaClient = new EurekaClient('localhost', 8080, $this->httpClient);
        $this->appName = 'test_app_name';
        $this->instanceId = 'test_instance_id';
        $this->metadata = [
            'test_key' => 'test_value'
        ];
        $this->vipAddress = 'test_vip_address';
        $this->secureVipAddress = 'test_secure_vip_address';
    }

    /**
     * Test EurekaClient::register() method.
     */
    public function testRegister()
    {
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
            ->setInstanceId($this->instanceId)
            ->setHostName('test_host_name')
            ->setApp($this->appName)
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

        // Expectations.
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('POST', 'localhost:8080/eureka/v2 /apps/' . $this->appName, [
                'json' => [
                    'instance' => $instance->export()
                ]
            ]);

        $this->eurekaClient->register($this->appName, $instance);
    }

    /**
     * Test EurekaClient::deRegister() method.
     */
    public function testDeRegister()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'localhost:8080/eureka/v2 /apps/' . $this->appName . '/' . $this->instanceId);

        $this->eurekaClient->deRegister($this->appName, $this->instanceId);
    }

    /**
     * Test EurekaClient::heartBeat() method.
     */
    public function testHeartbeat()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'localhost:8080/eureka/v2 /apps/' . $this->appName . '/' . $this->instanceId);

        $this->eurekaClient->heartBeat($this->appName, $this->instanceId);
    }

    /**
     * Test EurekaClient::getAllApps() method.
     */
    public function testGetAllApps()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'localhost:8080/eureka/v2 /apps', [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ])
            ->willReturn($this->response);

        $this->eurekaClient->getAllApps($this->appName, $this->instanceId);
    }

    /**
     * Test EurekaClient::getApp() method.
     */
    public function testGetApp()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'localhost:8080/eureka/v2 /apps/' . $this->appName, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ])
            ->willReturn($this->response);

        $this->eurekaClient->getApp($this->appName);
    }

    /**
     * Test EurekaClient::getAppInstance() method.
     */
    public function testGetAppInstance()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'localhost:8080/eureka/v2 /apps/' . $this->appName . '/' . $this->instanceId, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ])
            ->willReturn($this->response);

        $this->eurekaClient->getAppInstance($this->appName, $this->instanceId);
    }

    /**
     * Test EurekaClient::getInstance() method.
     */
    public function testGetInstance()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'localhost:8080/eureka/v2 /instances/' . $this->instanceId, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ])
            ->willReturn($this->response);

        $this->eurekaClient->getInstance($this->instanceId);
    }

    /**
     * Test EurekaClient::takeInstanceOut() method.
     */
    public function testTakeInstanceOut()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'localhost:8080/eureka/v2 /apps/' . $this->appName . '/' . $this->instanceId . '/status', [
                'query' => [
                    'value' => 'OUT_OF_SERVICE'
                ]
            ])
            ->willReturn($this->response);

        $this->eurekaClient->takeInstanceOut($this->appName, $this->instanceId);
    }

    /**
     * Test EurekaClient::putInstanceBack() method.
     */
    public function testPutInstanceBack()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'localhost:8080/eureka/v2 /apps/' . $this->appName . '/' . $this->instanceId . '/status', [
                'query' => [
                    'value' => 'UP'
                ]
            ])
            ->willReturn($this->response);

        $this->eurekaClient->putInstanceBack($this->appName, $this->instanceId);
    }

    /**
     * Test EurekaClient::updateAppInstanceMetadata() method.
     */
    public function testUpdateAppInstanceMetadata()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'localhost:8080/eureka/v2 /apps/' . $this->appName . '/' . $this->instanceId . '/metadata', [
                'query' => $this->metadata
            ])
            ->willReturn($this->response);

        $this->eurekaClient->updateAppInstanceMetadata($this->appName, $this->instanceId, $this->metadata);
    }

    /**
     * Test EurekaClient::getInstancesByVipAddress() method.
     */
    public function testGetInstancesByVipAddress()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'localhost:8080/eureka/v2 /vips/' . $this->vipAddress, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ])
            ->willReturn($this->response);

        $this->eurekaClient->getInstancesByVipAddress($this->vipAddress);
    }

    /**
     * Test EurekaClient::getInstancesBySecureVipAddress() method.
     */
    public function testGetInstancesBySecureVipAddress()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'localhost:8080/eureka/v2 /svips/' . $this->secureVipAddress, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ])
            ->willReturn($this->response);

        $this->eurekaClient->getInstancesBySecureVipAddress($this->secureVipAddress);
    }
}
