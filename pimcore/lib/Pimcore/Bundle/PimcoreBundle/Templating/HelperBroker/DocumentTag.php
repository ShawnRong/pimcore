<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\PimcoreBundle\Templating\HelperBroker;

use Pimcore\Bundle\PimcoreBundle\Templating\PhpEngine;
use Pimcore\Bundle\PimcoreBundle\Templating\Renderer\TagRenderer;

class DocumentTag implements HelperBrokerInterface
{
    /**
     * @var TagRenderer
     */
    protected $tagRenderer;

    /**
     * @param TagRenderer $tagRenderer
     */
    public function __construct(TagRenderer $tagRenderer)
    {
        $this->tagRenderer = $tagRenderer;
    }

    /**
     * @inheritDoc
     */
    public function supports(PhpEngine $engine, $method)
    {
        if ($this->tagRenderer->tagExists($method)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function helper(PhpEngine $engine, $method, array $arguments)
    {
        $document = $engine->getViewParameter('document');

        if (null === $document) {
            throw new \RuntimeException(sprintf('Trying to render the tag "%s", but no document was found', $method));
        }

        if (!isset($arguments[0])) {
            throw new \Exception('You have to set a name for the called tag (editable): ' . $method);
        }

        // set default if there is no editable configuration provided
        if (!isset($arguments[1])) {
            $arguments[1] = [];
        }

        return $this->tagRenderer->render($document, $method, $arguments[0], $arguments[1]);
    }
}
