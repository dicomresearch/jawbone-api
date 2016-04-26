<?php

namespace JawboneApi;

/**
 * Class AccessScope
 * @package JawboneApi
 */
class AccessScope
{
    const BASIC_READ = "basic_read";
    const EXTENDED_READ = "extended_read";
    const LOCATION_READ = "location_read";
    const FRIENDS_READ = "friends_read";
    const MOOD_READ = "mood_read";
    const MOOD_WRITE = "mood_write";
    const MOVE_READ = "move_read";
    const MOVE_WRITE = "move_write";
    const SLEEP_READ = "sleep_read";
    const SLEEP_WRITE = "sleep_write";
    const MEAL_READ = "meal_read";
    const MEAL_WRITE = "meal_write";
    const WEIGHT_READ = "weight_read";
    const WEIGHT_WRITE = "weight_write";
    const CARDIAC_READ = "cardiac_read";
    const CARDIAC_WRITE = "cardiac_write";
    const GENERIC_READ = "generic_event_read";
    const GENERIC_WRITE = "generic_event_write";

    /**
     * @var array|null Array of current enable scopes
     */
    private static $enableScopeArray;

    /**
     * @param string|null $scopeIdentifier
     *
     * @return array|bool
     */
    public static function getScopeDescription($scopeIdentifier = null)
    {
        $allScopeDescriptions = [
            static::BASIC_READ => 'Read access to basic information about the user',
            static::EXTENDED_READ => 'Read access to additional information about the user',
            static::LOCATION_READ => 'Read access to location-related data',
            static::FRIENDS_READ => 'Read access to the list of XID of the user’s friends',
            static::MOOD_READ => 'Read access to mood-related data',
            static::MOOD_WRITE => 'Access to post a mood',
            static::MOVE_READ => 'Read access to move-related data and workouts',
            static::MOVE_WRITE => 'Access to create a workout',
            static::SLEEP_READ => 'Read access to sleep-related data',
            static::SLEEP_WRITE => 'Access to create a sleep',
            static::MEAL_READ => 'Read access to meal-related data',
            static::MEAL_WRITE => 'Access to post a meal',
            static::WEIGHT_READ => 'Read access to user’s body metrics related data',
            static::WEIGHT_WRITE => 'Access to post user’s body metrics related data',
            static::CARDIAC_READ => 'Read access to user’s cardiac related data',
            static::CARDIAC_WRITE => 'Access to post user’s cardiac related data',
            static::GENERIC_READ => 'Read access to user\'s generic events',
            static::GENERIC_WRITE => 'Access to post generic events related to the user'
        ];

        if (is_null($scopeIdentifier)) {
            return $allScopeDescriptions;
        } elseif (static::existScopeIdentifier($scopeIdentifier)) {
            return $allScopeDescriptions[$scopeIdentifier];
        } else {
            return false;
        }
    }

    /**
     * Array of all scope identities
     * @return array
     */
    public static function getAllScopeArray()
    {
        return array_keys(static::getScopeDescription());
    }

    /**
     * Array of only current enable scope identities
     * @return array
     */
    public static function getEnableScopeArray()
    {
        if (!is_array(static::$enableScopeArray)) {
            static::$enableScopeArray = static::getAllScopeArray();
        }
        return static::$enableScopeArray;
    }

    /**
     * @param string|array $scopeArray
     */
    public static function setEnableScopeArray($scopeArray)
    {
        $scopeArray = static::normalizeScopeArray($scopeArray);
        static::$enableScopeArray = $scopeArray;
    }

    /**
     * @param string|array $scopeArray
     */
    public static function disableScope($scopeArray)
    {
        static::setEnableScopeArray(
            array_diff(
                static::getEnableScopeArray(),
                static::normalizeScopeArray($scopeArray)
            )
        );
    }

    /**
     * @param string|array $scopeArray
     */
    public static function enableScope($scopeArray)
    {
        static::setEnableScopeArray(
            array_merge(
                static::getEnableScopeArray(),
                static::normalizeScopeArray($scopeArray)
            )
        );
    }

    /**
     * @param string $scopeIdentifier
     *
     * @return bool
     */
    public static function scopeIdentifierEnable($scopeIdentifier)
    {
        return array_search($scopeIdentifier, static::getEnableScopeArray()) !== false;
    }

    /**
     * @param string|array $scopeArray
     *
     * @return array
     * @throws JawboneApiException
     */
    protected static function normalizeScopeArray($scopeArray)
    {
        if (!is_array($scopeArray) && static::existScopeIdentifier($scopeArray)) {
            $scopeArray = [$scopeArray];
        } else {
            throw new JawboneApiException('Parameter must be a single scope identifier or an array of such identifiers.');
        }
        return $scopeArray;
    }

    /**
     * @param string $scopeIdentifier
     *
     * @return bool
     */
    protected static function existScopeIdentifier($scopeIdentifier)
    {
        return array_search($scopeIdentifier, static::getAllScopeArray()) !== false;
    }
}
