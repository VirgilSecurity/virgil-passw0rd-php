<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: purekitV3_storage.proto

namespace PurekitV3Storage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>purekitV3Storage.UserRecordSigned</code>
 */
class UserRecordSigned extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 version = 1;</code>
     */
    private $version = 0;
    /**
     * Generated from protobuf field <code>string user_id = 2;</code>
     */
    private $user_id = '';
    /**
     * Generated from protobuf field <code>bytes phe_record_ns = 3;</code>
     */
    private $phe_record_ns = '';
    /**
     * Generated from protobuf field <code>bytes phe_record_nc = 4;</code>
     */
    private $phe_record_nc = '';
    /**
     * Generated from protobuf field <code>bytes upk = 5;</code>
     */
    private $upk = '';
    /**
     * Generated from protobuf field <code>bytes encrypted_usk = 6;</code>
     */
    private $encrypted_usk = '';
    /**
     * Generated from protobuf field <code>bytes encrypted_usk_backup = 7;</code>
     */
    private $encrypted_usk_backup = '';
    /**
     * Generated from protobuf field <code>bytes encrypted_pwd_hash = 8;</code>
     */
    private $encrypted_pwd_hash = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $version
     *     @type string $user_id
     *     @type string $phe_record_ns
     *     @type string $phe_record_nc
     *     @type string $upk
     *     @type string $encrypted_usk
     *     @type string $encrypted_usk_backup
     *     @type string $encrypted_pwd_hash
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\PurekitV3Storage::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>uint32 version = 1;</code>
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Generated from protobuf field <code>uint32 version = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setVersion($var)
    {
        GPBUtil::checkUint32($var);
        $this->version = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string user_id = 2;</code>
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Generated from protobuf field <code>string user_id = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setUserId($var)
    {
        GPBUtil::checkString($var, True);
        $this->user_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes phe_record_ns = 3;</code>
     * @return string
     */
    public function getPheRecordNs()
    {
        return $this->phe_record_ns;
    }

    /**
     * Generated from protobuf field <code>bytes phe_record_ns = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setPheRecordNs($var)
    {
        GPBUtil::checkString($var, False);
        $this->phe_record_ns = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes phe_record_nc = 4;</code>
     * @return string
     */
    public function getPheRecordNc()
    {
        return $this->phe_record_nc;
    }

    /**
     * Generated from protobuf field <code>bytes phe_record_nc = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setPheRecordNc($var)
    {
        GPBUtil::checkString($var, False);
        $this->phe_record_nc = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes upk = 5;</code>
     * @return string
     */
    public function getUpk()
    {
        return $this->upk;
    }

    /**
     * Generated from protobuf field <code>bytes upk = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setUpk($var)
    {
        GPBUtil::checkString($var, False);
        $this->upk = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_usk = 6;</code>
     * @return string
     */
    public function getEncryptedUsk()
    {
        return $this->encrypted_usk;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_usk = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setEncryptedUsk($var)
    {
        GPBUtil::checkString($var, False);
        $this->encrypted_usk = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_usk_backup = 7;</code>
     * @return string
     */
    public function getEncryptedUskBackup()
    {
        return $this->encrypted_usk_backup;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_usk_backup = 7;</code>
     * @param string $var
     * @return $this
     */
    public function setEncryptedUskBackup($var)
    {
        GPBUtil::checkString($var, False);
        $this->encrypted_usk_backup = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_pwd_hash = 8;</code>
     * @return string
     */
    public function getEncryptedPwdHash()
    {
        return $this->encrypted_pwd_hash;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_pwd_hash = 8;</code>
     * @param string $var
     * @return $this
     */
    public function setEncryptedPwdHash($var)
    {
        GPBUtil::checkString($var, False);
        $this->encrypted_pwd_hash = $var;

        return $this;
    }

}

