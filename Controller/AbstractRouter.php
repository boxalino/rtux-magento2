<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Controller;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\ConfigurationInterface;
use \Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use \Magento\Framework\App\Request\Http;
use \Magento\Framework\App\ResponseInterface;

/**
 * Dynamic Controller base
 *
 * Custom router for the Boxalino Narrative dynamically rendered pages
 * Manages the default match logic
 * Must be extended and completed with the desired logic for matching routes and actions
 */
abstract class AbstractRouter implements \Magento\Framework\App\RouterInterface
{

    const BOXALINO_API_ISOLATE_REQUEST = '_bx_allow_duplicate_items';

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $response;

    /**
     * @var ConfigurationInterface
     */
    protected $apiConfiguration;

    /**
     * Request path: from base url to query string
     *
     * @var null|string
     */
    protected $_requestPath = null;

    /**
     * @param ActionFactory $actionFactory
     * @param Http $request
     * @param ResponseInterface $response
     */
    public function __construct(
        ActionFactory $actionFactory,
        Http $request,
        ResponseInterface $response,
        ConfigurationInterface $apiConfiguration
    ){
        $this->actionFactory = $actionFactory;
        $this->request = $request;
        $this->response = $response;
        $this->apiConfiguration = $apiConfiguration;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return false|ActionInterface
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if ($this->apiConfiguration->isEnabled())
        {
            // get the path form base to query string
            $path = trim($request->getPathInfo(), '/');
            $p = $p = explode('/', $path);

            $this->_requestPath = array_filter($p);

            // if path is empty, there's no need to continue
            if (empty($this->_requestPath) || false === $this->matchPath())
            {
                return false;
            }

            // set params
            foreach ($this->getParams() as $key => $value)
            {
                $request->setParam($key, $value);
            }

            // main page
            $request->setModuleName($this->getIntegrationModuleName())->setControllerName($this->getController())->setActionName($this->getAction());
            $request->setAlias(\Magento\Framework\UrlInterface::REWRITE_REQUEST_PATH_ALIAS, $path);

            return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
        }

        return false;
    }

    /**
     * Return the params that has to be set on the request
     *
     * @return array
     */
    abstract protected function getParams() : array;

    /**
     * Match the router path against the logic the url are being generated
     *
     * @return boolean
     */
    abstract protected function matchPath() : bool;

    /**
     * @return string
     */
    abstract protected function getIntegrationModuleName() : string;

    /**
     * Implement abstract method that will return the controller name that
     * will be dispatched
     *
     * @return string
     */
    protected function getController() : string
    {
        $callingClass = get_called_class();

        return $callingClass::BOXALINO_INTEGRATION_ROUTER_CONTROLLER;
    }

    /**
     * Implement abstract method that will return the controller action that
     * will be dispatched.
     *
     * @return string
     */
    protected function getAction() : string
    {
        $callingClass = get_called_class();

        return $callingClass::BOXALINO_INTEGRATION_ROUTER_ACTION;
    }

}
