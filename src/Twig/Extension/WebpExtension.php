<?php

declare(strict_types=1);

namespace Yby\Webp\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Yby\Webp\Util\WebpConvertor;

class WebpExtension extends AbstractExtension
{
    /**
     * @var WebpConvertor
     */
    private $webpConvertor;

    /**
     * WebpExtension constructor.
     * @param WebpConvertor $webpConvertor
     */
    public function __construct(
        WebpConvertor $webpConvertor
    ) {
        $this->webpConvertor = $webpConvertor;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('webp', [$this->webpConvertor, 'convertImageUrl']),
        ];
    }
}
