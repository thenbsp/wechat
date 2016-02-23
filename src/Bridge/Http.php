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
     * SSL 证书
     */
    protected $sslCert;
    protected $sslKey;

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
     * Query With AccessToken
     */
    public function withAccessToken(AccessToken $accessToken)
    {
        $this->query['access_token'] = $accessToken->getTokenString();

        return $this;
    }

    /**
     * Request SSL Cert
     */
    public function withSSLCert($sslCert, $sslKey)
    {
        $this->sslCert = $sslCert;
        $this->sslKey  = $sslKey;

        return $this;
    }

    /**
     * Send Request
     */
    public function send($asArray = true)
    {
        $options = array();

        // query
        if( !empty($this->query) ) {
            $options['query'] = $this->query;
        }

        // body
        if( !empty($this->body) ) {
            $options['body'] = $this->body;
        }

        // ssl cert
        if( $this->sslCert && $this->sslKey ) {
            $options['cert']    = $this->sslCert;
            $options['ssl_key'] = $this->sslKey;
        }

        $response = (new Client)->request($this->method, $this->uri, $options);
        $contents = $response->getBody()->getContents();

        if( !$asArray ) {
            return $contents;
        }

        $array = Serializer::parse($contents);

        return new ArrayCollection($array);
    }
}
