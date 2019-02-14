<?php

namespace Framework\Twig;

use Framework\Session\FlashService;

/**
 * Extensions for flashes
 *
 * Class FlashExtension
 * @package Framework\Twig
 */
class FlashExtension extends \Twig_Extension
{
    /**
     * @var FlashService
     */
    private $flashService;

    /**
     * FlashExtension constructor.
     * @param FlashService $flashService
     */
    public function __construct(FlashService $flashService)
    {
        $this->flashService = $flashService;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('flash', [$this, 'getFlash'])
        ];
    }

    public function getFlash($type): ?string
    {
        return $this->flashService->get($type);
    }
}
