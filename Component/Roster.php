<?php
/**
 * Nucleus - XMPP Library for PHP
 *
 * Copyright (C) 2016, Some rights reserved.
 *
 * @author Kacper "Kadet" Donat <kacper@kadet.net>
 *
 * Contact with author:
 * Xmpp: me@kadet.net
 * E-mail: contact@kadet.net
 *
 * From Kadet with love.
 */

namespace Kadet\Xmpp\Component;


use Kadet\Xmpp\XmppClient;

class Roster extends Component
{
    public function setClient(XmppClient $client)
    {
        parent::setClient($client);
        $this->_client->on('init', function() {

        });
    }


}
