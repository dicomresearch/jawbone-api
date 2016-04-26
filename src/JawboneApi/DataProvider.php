<?php

namespace JawboneApi;

use Unirest;
use JawboneApi;
use JawboneApi\Interfaces\ItemInterface;
use JawboneApi\Interfaces\CollectibleInterface;
use JawboneApi\Interfaces\ViewableInterface;
use JawboneApi\Interfaces\AddableInterface;
use JawboneApi\Interfaces\EditableInterface;
use JawboneApi\Interfaces\RemovableInterface;
use JawboneApi\Collection;


class DataProvider
{
    const HTTPS = 'https://';

    const HTTP = 'http://';

    const HEADER_AUTH_KEY = 'Authorization';

    const HEADER_AUTH_VALUE_PREFIX = 'Bearer';

    const REQUEST_GET = 'get';

    const REQUEST_POST = 'post';

    const REQUEST_DELETE = 'delete';

    const REQUEST_LIST = 'list';

    const REQUEST_CREATE = 'create';

    const OK_STATUS_CODE = 200;

    const CREATED_STATUS_CODE = 201;

    protected $correctStatusCodeArray = [self::OK_STATUS_CODE, self::CREATED_STATUS_CODE];

    protected $domainName = 'jawbone.com';

    protected $apiURI = '/nudge/api/v.1.1/';

    protected $cacheLastRequestUri;

    public function loadCollection(JawboneApi\User $user, CollectibleInterface $item, Collection $collection)
    {
        $this->injectToSafeLoadingItem($item, $user);
        $searchParamArray = $collection->getSearchCondition()->getSearchParamArray();
        if (!empty($searchParamArray)) {
            $parameters = $searchParamArray;
            $requestUri = null;
        } else {
            $parameters = null;
            $requestUri = $collection->getNextPageUri();
        }
        $response = $this->sendRequest(static::REQUEST_LIST, $user, $item, $item->getListActionName(), $parameters, $requestUri);
        $collection->update($this->getItemDataFromResponse($response), $item);
    }

    public function loadItem(JawboneApi\User $user, ViewableInterface $item, $force = false, $parameters = null)
    {
        if (!$item->isLoaded() || $force) {
            $this->injectToSafeLoadingItem($item, $user);
            $response = $this->sendRequest(static::REQUEST_GET, $user, $item, $item->getViewActionName(), $parameters);
            $item->populateFromJson($this->getItemDataFromResponse($response));
        }
    }

    public function createItem(JawboneApi\User $user, AddableInterface $item, $parameters = [])
    {
        $this->checkPermission($item);
        if (is_null($item->getXid())) {
            $parameters = array_merge($item->getParametersForCreate(), $parameters);
            $response = $this->sendRequest(static::REQUEST_CREATE, $user, $item, $item->getAddActionName(), $parameters);
            $item->setXid($this->getItemDataFromResponse($response)->xid);
        } else {
            throw new JawboneApiException('Record already exists');
        }
    }

    public function editItem(JawboneApi\User $user, EditableInterface $item, $parameters = [])
    {
        $this->checkPermission($item);
        if (!is_null($item->getXid())) {
            $parameters = array_merge($item->getParametersForUpdate(), $parameters);
            return $this->sendRequest(static::REQUEST_POST, $user, $item, $item->getEditActionName(), $parameters);
        } else {
            throw new JawboneApiException('Record does not exist or has not been loaded');
        }
    }

    public function deleteItem(JawboneApi\User $user, RemovableInterface $item)
    {
        $this->checkPermission($item);
        if (!is_null($item->getXid())) {
            return $this->sendRequest(static::REQUEST_DELETE, $user, $item, $item->getRemoveActionName());
        } else {
            throw new JawboneApiException('Record does not exist or has not been loaded');
        }
    }

    /**
     * @param string          $requestType GET, POST, DELETE or pseudo type LIST, CREATE
     * @param JawboneApi\User $user
     * @param ItemInterface   $item
     * @param string|null     $actionName
     * @param array|null      $parameters
     * @param string|null     $requestUrl
     *
     * @return Unirest\HttpResponse
     * @throws JawboneApiException
     */
    public function sendRequest(
        $requestType = self::REQUEST_GET,
        JawboneApi\User $user,
        ItemInterface $item,
        $actionName = null,
        $parameters = null,
        $requestUri = null)
    {
        if (empty($parameters)) {
            $parameters = null;
        }

        $requestUrl = $this->createRequestUrl($user, $item, $requestType, $actionName, $requestUri);

        if ($this->isPseudoRequestType($requestType)) {
            $requestType = $this->getRealRequestType($requestType);
        }

        $response = Unirest\Request::$requestType(
            $requestUrl,
            $this->getDefaultHeaders($user),
            $parameters
        );

        if (property_exists($response->body, 'meta') && !in_array($response->body->meta->code, $this->correctStatusCodeArray)) {
            throw new JawboneApiException(
                "Error {$response->body->meta->code} with message '{$response->body->meta->message}'",
                $response->body->meta->code
            );
        }
        return $response;
    }

    /**
     * @param string $domainName
     */
    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;
    }

    /**
     * @return string
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    /**
     * @param string $apiURI
     */
    public function setApiURI($apiURI)
    {
        $this->apiURI = $apiURI;
    }

    /**
     * @return string
     */
    public function getApiURI()
    {
        return $this->apiURI;
    }

    /**
     * Return data part from response
     *
     * @param Unirest\HttpResponse $response
     *
     * @return \stdClass
     */
    public function getItemDataFromResponse(Unirest\Response $response)
    {
        return $response->body->data;
    }

    /**
     * Generates the address of the desired structure according with the logic of the data service
     *
     * @param JawboneApi\User $user
     * @param ItemInterface   $item
     * @param string          $requestType
     * @param string|null     $actionName
     * @param string|null     $requestUri
     *
     * @return string
     */
    protected function createRequestUrl(JawboneApi\User $user, ItemInterface $item, $requestType, $actionName = null, $requestUri = null)
    {
        $fullDomainName = static::HTTPS . $this->getDomainName();

        if (!is_null($requestUri)) {
            $requestUri = $requestUri;
        } else {
            $requestUri = $this->getApiURI() . $this->createRequestUri($user, $item, $requestType, $actionName);
        }

        $this->cacheLastRequestUri = $requestUri;

        return $fullDomainName . $requestUri;
    }

    protected function checkPermission(ItemInterface $item)
    {
        if (!AccessScope::scopeIdentifierEnable($item->getWritePermissionName())) {
            throw new JawboneApiException('Access denied.');
        }
    }

    protected function createRequestUri(JawboneApi\User $user, ItemInterface $item, $requestType, $actionName = null)
    {
        if ($this->isPseudoRequestType($requestType)) {
            $requestUri = $user->getEndPointName() . '/' . $user->getXid();
        } else {
            $requestUri = $item->getEndPointName() . '/' . $item->getXid();
        }

        if (!is_null($actionName)) {
            $requestUri .= '/' . $actionName;
        }

        return $requestUri;
    }

    protected function isPseudoRequestType($requestType)
    {
        return $requestType == static::REQUEST_LIST || $requestType == static::REQUEST_CREATE;
    }

    protected function getRealRequestType($requestType)
    {
        return $requestType == static::REQUEST_LIST ? static::REQUEST_GET : static::REQUEST_POST;
    }

    protected function getDefaultHeaders(JawboneApi\User $user)
    {
        return [
            static::HEADER_AUTH_KEY => static::HEADER_AUTH_VALUE_PREFIX . ' ' . $user->getAccessToken()
        ];
    }

    protected function injectToSafeLoadingItem(ItemInterface $item, JawboneApi\User $user)
    {
        if ($item instanceof JawboneApi\Interfaces\SelfLoadingInterface) {
            $item->setDataProvider($this);
            $item->setUser($user);
        }
    }
}
