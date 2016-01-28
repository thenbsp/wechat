<?php

namespace Thenbsp\Wechat\Bridge;

use GuzzleHttp\Client;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Wechat\AccessToken;

class Http
{
    /**
     * GuzzleHttp\Client
     */
    protected $client;

    /**
     * Thenbsp\Wechat\Bridge\Serializer
     */
    protected $serializer;

    /**
     * Request Url
     */
    protected $uri;

    /**
     * Request Method
     */
    protected $method;

    /**
     * Request Body
     */
    protected $body;

    /**
     * Request Query
     */
    protected $query = array();

    /**
     * Query With AccessToken
     */
    protected $accessToken;

    /**
     * initialize
     */
    public function __construct($method, $uri)
    {
        $this->uri      = $uri;
        $this->method   = strtoupper($method);

        $this->client       = new Client;
        $this->serializer   = new Serializer;
    }

    /**
     * Create Client Factory
     */
    public static function request($method, $uri)
    {
        return new static($method, $uri);
    }

    /**
     * Query With AccessToken
     */
    public function withAccessToken(AccessToken $accessToken)
    {
        $this->query['access_token'] = $accessToken->getTokenString();

        return $this;
    }

    /**
     * Request Query
     */
    public function withQuery(array $query)
    {
        $this->query = array_merge($this->query, $query);

        return $this;
    }

    /**
     * Request Json Body
     */
    public function withBody(array $body)
    {
        $this->body = $this->serializer->jsonEncode($body);

        return $this;
    }

    /**
     * Request Xml Body
     */
    public function withXmlBody(array $body)
    {
        $this->body = $this->serializer->xmlEncode($body);

        return $this;
    }

    /**
     * Send Request
     */
    public function send($asArray = true)
    {
        $options = array();

        if( !empty($this->query) ) {
            $options['query'] = $this->query;
        }

        if( !empty($this->body) ) {
            $options['body'] = $this->body;
        }

        $response = $this->client->request($this->method, $this->uri, $options);
        $contents = $response->getBody()->getContents();

        if( !$asArray ) {
            return $contents;
        }

        if( $this->isJSON($contents) ) {
            return $this->serializer->jsonDecode($contents);
        } elseif( $this->isXML($contents) ) {
            return $this->serializer->xmlDecode($contents);
        } else {
            throw new \InvalidArgumentException(sprintf('Unable to parse: %s', (string) $contents));
        }
    }

    /**
     * check is json string
     */
    public function isJSON($data)
    {
        return (@json_decode($data) !== null);
    }

    /**
     * check is xml string
     */
    public function isXML($data)
    {
        $xml = @simplexml_load_string($data);

        return ($xml instanceof SimpleXmlElement) ? $xml : false;
    }
}
