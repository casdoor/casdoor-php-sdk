<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Exceptions\CasdoorException;
use Casdoor\Util\Util;

/**
 * Class Sms, if your casdoor is set up correctly, you can send sms by using this class
 *
 * @author ab1652759879@gmail.com
 */
class Sms
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var array
     */
    public $receivers;

    /**
     * @var AuthConfig
     */
    protected $authConfig;

    public function __construct(AuthConfig $authConfig, string $content, string ...$receivers)
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
