<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Exceptions\CasdoorException;
use Casdoor\Util\Util;

/**
 * Class Email.
 *
 * @author ab1652759879@gmail.com
 */
class Email
{
    public string $title;
    public string $content;
    public string $sender;
    public array $receivers;
    protected AuthConfig $authConfig;

    public function __construct(string $title, string $content, string $sender, AuthConfig $authConfig, string ...$receivers)
    {
        $this->title      = $title;
        $this->content    = $content;
        $this->sender     = $sender;
        $this->authConfig = $authConfig;
        $this->receivers  = $receivers;
    }

    public function sendEmail()
    {
        $postBytes = json_encode($this, JSON_THROW_ON_ERROR);

        $resp = Util::doPost('send-email', [], $this->authConfig, $postBytes, false);

        if ($resp->status != 'ok') {
            throw new CasdoorException($resp->msg);
        }
    }
}
