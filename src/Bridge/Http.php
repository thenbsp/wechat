<?php

namespace Thenbsp\Wechat\Bridge;

use GuzzleHttp\Client;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Wechat\AccessToken;
use Doctrine\Common\Collections\ArrayCollection;

class Http
{
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
        $this->body = Serializer::jsonEncode($body);

        return $this;
    }

    /**
     * Request Xml Body
     */
    public function withXmlBody(array $body)
    {
        $this->body = Serializer::xmlEncode($body);

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

        $response = (new Client)->request($this->method, $this->uri, $options);
        $contents = $response->getBody()->getContents();

        if( !$asArray ) {
            return $contents;
        }

        if( Serializer::isJSON($contents) ) {
            $result = Serializer::jsonDecode($contents);
        } elseif( Serializer::isXML($contents) ) {
            $result = Serializer::xmlDecode($contents);
        } else {
            throw new \Exception(sprintf('Unable to parse: %s', (string) $contents));
        }

        return new ArrayCollection($result);
    }
}
