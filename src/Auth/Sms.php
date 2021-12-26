<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Exceptions\CasdoorException;
use Casdoor\Util\Util;

/**
 * Class Sms.
 *
 * @author ab1652759879@gmail.com
 */
class Sms
{
    public string $content;
    public array $receivers;
    protected AuthConfig $authConfig;

    public function __construct(string $content, string ...$receivers, AuthConfig $authConfig)
    {
        $this->content    = $content;
        $this->receivers  = $receivers;
        $this->authConfig = $authConfig;
    }

    public function sendSms(): void
    {
        $postBytes = json_encode($this, JSON_THROW_ON_ERROR);

        $resp = Util::doPost('send-sms', [], $this->authConfig, $postBytes, false);
    
        if ($resp->status != 'ok') {
            throw new CasdoorException($resp->msg);
        }
    }
}
