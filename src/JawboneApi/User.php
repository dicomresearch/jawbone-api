<?php

namespace JawboneApi;

use JawboneApi\Interfaces\SelfLoadingInterface;
use JawboneApi\Interfaces\ViewableInterface;
use JawboneApi\Items\AbstractItem;
use JawboneApi\Traits\AttachImageTrait;
use JawboneApi\Traits\SelfLoadingTrait;

/**
 * Class User
 * @package JawboneApi
 */
class User extends AbstractItem implements ViewableInterface, SelfLoadingInterface
{
    use SelfLoadingTrait, AttachImageTrait;

    /**
     * Pseudo user id for current user
     */
    const PSEUDO_XID = '@me';

    const FEMALE = 0;

    const MALE = 1;

    const LOAD_FRIENDS_ACTION = 'friends';

    protected $jsonToPropertyMap = [
        'xid' => 'xid',
        'first' => 'firstName',
        'last' => 'lastName',
        'image' => 'imageUri',
        'weight' => 'weight',
        'height' => 'height',
        'gender' => 'gender'
    ];

    /**
     * OAuth access token
     *
     * @var string
     */
    private $accessToken;

    /**
     * @var string First name of user
     */
    public $firstName;

    /**
     * @var string Last name of user
     */
    public $lastName;

    /**
     * @var int Weight of the user, in kilograms
     */
    public $weight;

    /**
     * @var int Height of the user, in meters.
     */
    public $height;

    /**
     * @var bool Gender of the user
     */
    public $gender;

    /**
     * @var array Array of user friends' xid
     */
    private $friends;

    public function __construct($accessToken)
    {
        $this->setAccessToken($accessToken);
    }

    /**
     * Return endpoint part of request uri
     *
     * @return string
     */
    public function getEndPointName()
    {
        return 'users';
    }

    /**
     * Return action name part of request uri for view single record
     *
     * @return null
     */
    public function getViewActionName()
    {
        return null;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getXid()
    {
        $xid = parent::getXid();
        if (is_null($xid)) {
            $xid = static::PSEUDO_XID;
        }
        return $xid;
    }

    /**
     * @param bool $gender
     *
     * @return $this
     */
    public function setGenderFromJson($gender)
    {
        if ($gender) {
            $this->gender = static::FEMALE;
        } else {
            $this->gender = static::MALE;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function getGenderForJson()
    {
        return (boolean) $this->gender;
    }

    public function getFriends()
    {
        if (is_null($this->friends)) {
            $this->loadFriends();
        }
        return $this->friends;
    }

    protected function loadFriends()
    {
        $response = $this->getDataProvider()->sendRequest(DataProvider::REQUEST_GET, $this, $this, static::LOAD_FRIENDS_ACTION);
        $this->friends = $this->getDataProvider()->getItemDataFromResponse($response)->items;
    }
}
