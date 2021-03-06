<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: purekitV3_client.proto

namespace PurekitV3Client;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>purekitV3Client.DeleteRoleAssignmentsRequest</code>
 */
class DeleteRoleAssignmentsRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string role_name = 1;</code>
     */
    private $role_name = '';
    /**
     * Generated from protobuf field <code>repeated string user_ids = 2;</code>
     */
    private $user_ids;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $role_name
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $user_ids
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\PurekitV3Client::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string role_name = 1;</code>
     * @return string
     */
    public function getRoleName()
    {
        return $this->role_name;
    }

    /**
     * Generated from protobuf field <code>string role_name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setRoleName($var)
    {
        GPBUtil::checkString($var, True);
        $this->role_name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated string user_ids = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getUserIds()
    {
        return $this->user_ids;
    }

    /**
     * Generated from protobuf field <code>repeated string user_ids = 2;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setUserIds($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->user_ids = $arr;

        return $this;
    }

}

