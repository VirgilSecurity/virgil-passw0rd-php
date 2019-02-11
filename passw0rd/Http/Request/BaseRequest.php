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

namespace passw0rd\Http\Request;


use passw0rd\Http\HttpVirgilAgent;

abstract class BaseRequest
{
    const POST = 'POST';
    const GET = 'GET';

    protected $method = self::POST;
    protected $endpoint;
    protected $optionsHeader;
    protected $optionsBody;

    protected $virgilAgent;

    protected $appToken;

    /**
     * BaseRequest constructor.
     * @param string $endpoint
     */
    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->virgilAgent = new HttpVirgilAgent();
        $this->optionsBody = $this->formatBody();
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    private function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return array
     */
    public function getOptionsHeader(): array
    {
        return ["virgil-agent" => $this->virgilAgent->getFormatString(), "AppToken" => $this->appToken];
    }

    /**
     * @return string
     */
    public function getOptionsBody(): string
    {
        return $this->optionsBody;
    }

    /**
     * @return string
     */
    abstract protected function formatBody(): string;

    /**
     * @return string
     */
    public function getUri(): string
    {
        $pref = $this->appToken[1];

        $v = isset($_ENV['VIRGIL_ENV']) ? $_ENV['VIRGIL_ENV'] : 'api';
        $p = isset($_ENV['PASSW0RD_ENV']) ? $_ENV['PASSW0RD_ENV'] : 'api';

        if('T'==$pref)
            $uri = "https://$p.passw0rd.io/phe/v1/{$this->getEndpoint()}";

        if('V'==$pref)
            $uri = "https://$v.virgilsecurity.com/phe/v1/{$this->getEndpoint()}";

        return $uri;

    }
}