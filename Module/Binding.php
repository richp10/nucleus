<?php
/**
 * XMPP Library
 *
 * Copyright (C) 2016, Some right reserved.
 *
 * @author Kacper "Kadet" Donat <kacper@kadet.net>
 *
 * Contact with author:
 * Xmpp: me@kadet.net
 * E-mail: contact@kadet.net
 *
 * From Kadet with love.
 */

namespace Kadet\Xmpp\Module;


use Kadet\Xmpp\Stanza\Stanza;
use Kadet\Xmpp\Stream\Features;
use Kadet\Xmpp\Xml\XmlElement;
use Kadet\Xmpp\XmppClient;
use \Kadet\Xmpp\Utils\filter as with;

class Binding extends ClientModule
{
    const XMLNS = 'urn:ietf:params:xml:ns:xmpp-bind';

    public function setClient(XmppClient $client)
    {
        parent::setClient($client);

        $client->on('features', function (Features $features) {
            return !$this->bind($features);
        });
    }

    public function bind(Features $features)
    {
        if($features->has(\Kadet\Xmpp\Utils\filter\element('bind', self::XMLNS))) {
            $stanza = new Stanza('iq', ['type' => 'set']);
            $bind = $stanza->append(new XmlElement('bind', self::XMLNS));

            if(!$this->_client->jid->isBare()) {
                $bind->append(new XmlElement('resource', null, $this->_client->jid->resource));
            }

            $this->_client->once('element', function(Stanza $element) {
                $this->handleResult($element);
            }, with\stanza\id($stanza->id));

            $this->_client->write($stanza);
            return true;
        }

        return false;
    }

    public function handleResult(Stanza $stanza)
    {
        if($stanza->type === 'result') {
            $this->_client->bind($stanza->element('bind', self::XMLNS)->element('jid')->innerXml);
        }
    }
}