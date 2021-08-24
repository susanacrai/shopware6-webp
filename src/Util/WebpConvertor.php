<?php

declare(strict_types=1);

namespace Yby\Webp\Util;

use League\Flysystem\FileNotFoundException;
use Shopware\Production\Kernel;
use Symfony\Component\Asset\UrlPackage;
use WebPConvert\Convert\Exceptions\ConversionFailedException;
use WebPConvert\WebPConvert;

class WebpConvertor
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var UrlPackage
     */
    private $urlPackage;

    /**
     * WebpConvertor constructor.
     * @param Kernel $kernel
     * @param UrlPackage $urlPackage
     */
    public function __construct(
        Kernel $kernel,
        UrlPackage $urlPackage
    ) {
        $this->kernel = $kernel;
        $this->urlPackage = $urlPackage;
    }

    /**
     * @param string $imageUrl
     * @return string
     */
    public function convertImageUrl(string $imageUrl): string
    {
        $imagePath = $this->getFileFromImageUrl($imageUrl);
        if (!$imagePath) {
            return '';
        }

        $webpPath = preg_replace('/\.(png|jpg|PNG|JPG)$/', '.webp', $imagePath);

        if (file_exists($webpPath)) {
            return preg_replace('/\.(png|jpg|PNG|JPG)$/', '.webp', $imageUrl);
        }

        if ($this->shouldConvert($imagePath, $webpPath) === false) {
            return '';
        }

        $options = $this->getOptions();

        try {
            WebPConvert::convert($imagePath, $webpPath, $options);
        } catch (ConversionFailedException $e) {
            return '';
        }

        $webpUrl = preg_replace('/\.(png|jpg|PNG|JPG)$/', '.webp', $imageUrl);
        return $webpUrl;
    }

    /**
     * @return array
     */
    private function getOptions(): array
    {
        $options = [];
        $options['metadata'] = 'none';
        $options['quality'] = 100;

        return $options;
    }

    /**
     * @param $imagePath
     * @param $webpPath
     * @return bool
     */
    private function shouldConvert($imagePath, $webpPath): bool
    {
        if ($imagePath === $webpPath) {
            return false;
        }

        if (!file_exists($webpPath)) {
            return true;
        }

        if (filemtime($imagePath) < filemtime($webpPath)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $imageUrl
     * @return string
     * @throws FileNotFoundException
     */
    private function getFileFromImageUrl(string $imageUrl): string
    {
        $imagePath = $this->getPublicDirectory() . str_replace($this->urlPackage->getBaseUrl($imageUrl), '', $imageUrl);
        if (file_exists($imagePath)) {
            return $imagePath;
        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    private function getPublicDirectory(): string
    {
        return $this->kernel->getProjectDir() . '/public';
    }
}
