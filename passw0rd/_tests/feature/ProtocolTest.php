<?php
/**
 * Copyright (C) 2015-2019 Virgil Security Inc.
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

use Dotenv\Dotenv;
use passw0rd\Core\PHEClient;
use Passw0rd\EnrollmentResponse;
use passw0rd\Exeptions\ProtocolException;
use passw0rd\Http\Request\EnrollRequest;
use passw0rd\Http\Request\VerifyPasswordRequest;
use passw0rd\Protocol\Protocol;
use passw0rd\Protocol\ProtocolContext;
use passw0rd\Http\HttpClient;
use Passw0rd\VerifyPasswordResponse;


class ProtocolTest extends \PHPUnit\Framework\TestCase
{
    protected $client;
    protected $context;
    protected $protocol;
    protected $httpClient;
    protected $password;

    protected function setUp()
    {
        // TODO Fix it!
        $this->markTestSkipped("Some problems with env variables");

        $this->context = (new ProtocolContext)->create([
            'appToken' => getenv("APP_TOKEN"),
            'servicePublicKey' => getenv("SERVICE_PUBLIC_KEY"),
            'appSecretKey' => getenv("APP_SECRET_KEY"),
            'updateToken' => getenv("UPDATE_TOKEN"),
        ]);

        $this->protocol = new Protocol($this->context);
        $this->httpClient = new HttpClient();
        $this->password = "password234";
    }

    public function testProtocolEnrollAccountAndVerifyPasswordShouldSucceed()
    {
        // API Request
        $enrollRequest = new EnrollRequest('enroll');
        $this->httpClient->setRequest($enrollRequest);

        // API Response
        $response = $this->httpClient->getResponse(false);

        if ($response->getStatusCode() !== 200)
            throw new ProtocolException("Api error. Status code: {$response->getStatusCode()}");

        $protobufResponse = $response->getBody()->getContents();

        // Protobuf Response
        $protoEnrollmentResponse = new EnrollmentResponse();
        $protoEnrollmentResponse->mergeFromString($protobufResponse);
        $enrollmentResponse = $protoEnrollmentResponse->getResponse();

        // PHE Response
        try {
            $res = $this->context->getPHEClient()->enrollAccount($enrollmentResponse, $this->password);
        } catch (\Exception $e) {
            throw new ProtocolException(__METHOD__ . ": {$e->getMessage()}, {$e->getCode()}");
        }

        $this->assertEquals(202, strlen($res[0]));

        // PHE Request
        try {
            $verifyPasswordRequest = $this->context->getPHEClient()->createVerifyPasswordRequest($this->password,
                $res[0]);
        } catch (\Exception $e) {
            throw new ProtocolException('Verify password request error');
        }

        // API Request
        $verifyPassword = new VerifyPasswordRequest('verify-password', $verifyPasswordRequest);

        $this->httpClient->setRequest($verifyPassword);

        // API Response
        $response = $this->httpClient->getResponse(false);

        if ($response->getStatusCode() !== 200)
            throw new ProtocolException("Api error. Status code: {$response->getStatusCode()}");

        $protobufResponse = $response->getBody()->getContents();

        // Protobuf Response
        $protoVerifyPasswordResponse = new VerifyPasswordResponse();
        $protoVerifyPasswordResponse->mergeFromString($protobufResponse);
        $verifyPasswordResponse = $protoVerifyPasswordResponse->getResponse();

        $checkResponseAndDecrypt = $this->context->getPHEClient()->checkResponseAndDecrypt($this->password, $res[0],
            $verifyPasswordResponse);

        $this->assertInternalType('string', $checkResponseAndDecrypt);
        $this->assertEquals(32, strlen($checkResponseAndDecrypt));

        $wrongPassword = "wrong-password";
        $this->expectException(\Exception::class);
        $this->context->getPHEClient()->checkResponseAndDecrypt($wrongPassword, $res[0],
            $verifyPasswordResponse);
    }
}