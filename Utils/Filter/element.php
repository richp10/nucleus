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

namespace Kadet\Xmpp\Utils\filter\element;

use Kadet\Xmpp\Xml\XmlElement;

/**
 * Predicate used to check if given element is from specified namespace.
 *
 * Assume that `$element` is representation of:
 *
 * ```xml
 * <element xmlns="uri:xmlns:foo" />
 * ```
 *
 * ```php
 * $foo = xmlns("uri:xmlns:foo");
 * $bar = xmlns("uri:xmlns:bar");
 *
 * $foo($element); // true
 * $bar($element); // false, as element is from uri:xmlns:foo namespace
 * ```
 *
 * @param string|\Closure $uri Expected XML namespace URI or predicate
 * @return \Closure
 */
function xmlns($uri)
{
    $predicate = $uri instanceof \Closure ? $uri : \Kadet\Xmpp\Utils\filter\same($uri);

    return function ($element) use ($predicate) {
        if (!$element instanceof XmlElement) {
            return false;
        }

        return $predicate($element->namespace);
    };
}

/**
 * Predicate used to check if given element has specified name.
 *
 * Assume that `$element` is representation of:
 *
 * ```xml
 * <foo />
 * ```
 *
 * ```php
 * $foo = name("foo");
 * $bar = name("bar");
 *
 * $foo($element); // true
 * $bar($element); // false, as element name is foo
 * ```
 *
 * @param string|\Closure $name Expected element name or predicate.
 * @return \Closure
 */
function name($name)
{
    $predicate = $name instanceof \Closure ? $name : \Kadet\Xmpp\Utils\filter\same($name);

    return function ($element) use ($predicate) {
        if (!$element instanceof XmlElement) {
            return false;
        }

        return $predicate($element->localName);
    };
}

/**
 * Predicate used to check if element's attribute matches value.
 *
 * Assume that `$element` is representation of:
 *
 * ```xml
 * <element foo="yes" bar="no" />
 * ```
 *
 * ```php
 * $foo = attribute("foo", "yes");
 * $bar = attribute("bar", "yes")
 *
 * $foo($element); // true
 * $bar($element); // false
 * ```
 *
 * @param string $name    Argument name
 * @param string|\Closure Expected argument value
 * @return \Closure
 */
function attribute($name, $value)
{
    $predicate = $value instanceof \Closure ? $value : \Kadet\Xmpp\Utils\filter\equals($value);

    return function ($element) use ($name, $predicate) {
        if (!$element instanceof XmlElement) {
            return false;
        }

        return $predicate($element->getAttribute($name));
    };
}
