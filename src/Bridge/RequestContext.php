<?php

namespace Thenbsp\Wechat\Bridge;

use Thenbsp\Wechat\Bridge\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class RequestContext extends ArrayCollection
{
    /**
     * Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * initialize request
     */
    public function __construct(Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();

        $this->setRequest($request);
    }

    /**
     * set request
     */
    public function setRequest(Request $request)
    {
        $content = $request->getContent();

        try {
            $options = Serializer::parse($content);
        } catch (\InvalidArgumentException $e) {
            $options = array();
        }

        // update ArrayCollection from request content
        parent::__construct($options);

        $this->request = $request;
    }

    /**
     * get request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
