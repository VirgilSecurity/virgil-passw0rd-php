<?php
/**
 * Copyright (C) 2015-2018 Virgil Security Inc.
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *     (1) Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *     (2) Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *     (3) Neither the name of the copyright holder nor the names of its
 *     contributors may be used to endorse or promote products derived from
 *     this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ''AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING
 * IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Lead Maintainer: Virgil Security Inc. <support@virgilsecurity.com>
 */

namespace passw0rd\Protocol;

use passw0rd\Core\ClientPrivateKey;
use passw0rd\Core\PHEClient;
use Passw0rd\EnrollmentResponse;
use passw0rd\Exeptions\ProtocolException;
use passw0rd\Helpers\ArrayHelperTrait;
use passw0rd\Http\HttpClient;
use passw0rd\Http\Request\EnrollRequest;
use passw0rd\Http\Request\VerifyPasswordRequest;
use Passw0rd\VerifyPasswordResponse;

class Protocol implements AvailableProtocol
{
    use ArrayHelperTrait;

    private $protocolService;
    private $httpClient;
    private $context;
    private $client;
    private $server;

    /**
     * Protocol constructor.
     * @param ProtocolContext $context
     * @throws \Exception
     */
    public function __construct(ProtocolContext $context)
    {
        $this->httpClient = new HttpClient();
        $this->context = $context;

        try {
            $this->client = new PHEClient();
            $clientPrivateKey = ClientPrivateKey::getInstance($this->client);
            $this->client->setKeys($clientPrivateKey->get(), $this->context->getPublicKey());
        }
        catch(\Exception $e) {
            throw new ProtocolException('Protocol error with PHE client constructor or setKeys method');
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @throws ProtocolException
     */
    public function __call(string $name, array $arguments)
    {
        if(!in_array($name, AvailableProtocol::ENDPOINTS))
            throw new ProtocolException("Incorrect endpoint: $name. Correct endpoints: {$this->toString(AvailableProtocol::ENDPOINTS)}");

        return;
    }

    /**
     * @param string $password
     * @param bool $encodeToBase64
     * @return string
     * @throws ProtocolException
     */
    public function enroll(string $password, bool $encodeToBase64 = true): string
    {
        $enrollRequest = new EnrollRequest('enroll');
        $this->httpClient->setRequest($enrollRequest);
        $response = $this->httpClient->getResponse(false);

        if($response->getStatusCode() !== 200)
            throw new ProtocolException("Api error. Status code: {$response->getStatusCode()}");

        $protobufResponse = $response->getBody()->getContents();

        $protoEnrollmentResponse = new EnrollmentResponse();
        $protoEnrollmentResponse->mergeFromString($protobufResponse);

        $enrollmentResponse = $protoEnrollmentResponse->getResponse();

        $res = $this->client->enrollAccount($enrollmentResponse, $password);

        return $encodeToBase64==true ? base64_encode($res[0]) : $res[0];
    }

    /**
     * @param string $password
     * @param string $record
     * @return bool
     * @throws ProtocolException
     */
    public function verifyPassword(string $password, string $record): bool
    {
        try {
            $verifyPasswordRequest = $this->client->createVerifyPasswordRequest($password, $record);
        }
        catch(\Exception $e) {
            throw new ProtocolException('Verify password request error');
        }

        $verifyPassword = new VerifyPasswordRequest('verify-password', $verifyPasswordRequest);
        $this->httpClient->setRequest($verifyPassword);
        $response = $this->httpClient->getResponse(false);

        if($response->getStatusCode() !== 200)
            throw new ProtocolException("Protocol error"); // TODO need some refactoring!

        $protobufResponse = $response->getBody()->getContents();

        $protoVerifyPasswordResponse = new VerifyPasswordResponse();
        $protoVerifyPasswordResponse->mergeFromString($protobufResponse);

        $verifyPasswordResponse = $protoVerifyPasswordResponse->getResponse();

        try {
            $this->client->checkResponseAndDecrypt($password, $record, $verifyPasswordResponse);
        }
        catch(\Exception $e) {
            throw new ProtocolException("Authentication failed");
        }

        return true;
    }

    public function updatePassword()
    {

    }
}