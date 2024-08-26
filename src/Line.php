<?php

declare(strict_types=1);

namespace cyberthai\Linebot;

use cyberthai\Linebot\Provider\LineProvider;
use cyberthai\Linebot\Helpers\Users;
use cyberthai\Linebot\Helpers\Webhook;
use cyberthai\Linebot\Helpers\Richmenu;
use cyberthai\Linebot\Helpers\Message;

class Line {
    public $provider;

    public function __construct(public array $config) {
        $this->provider = new LineProvider(
            $config['clientId'] ?? '',
            $config['clientSecret'] ?? '',
            $config['channelAccessToken'] ?? '',
        );
    }

    /*
     * Users API
     */

    public function users(): \cyberthai\Linebot\Helpers\Users {
        return new Users($this->provider);
    }

    // Setting Webhook
    public function webhook(): \cyberthai\Linebot\Helpers\Webhook {
        return new Webhook($this->provider);
    }


    //Richmenu
    public function richmenu(): \cyberthai\Linebot\Helpers\Richmenu {
        return new Richmenu($this->provider);
    }

    //Messaging API
    public function message(): \cyberthai\Linebot\Helpers\Message {
        return new Message($this->provider);
    }

    //Bot info
    public function info(): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->provider->request(
            'users',
            'info',
            'GET',
            $header,
            []
        );
    }
}