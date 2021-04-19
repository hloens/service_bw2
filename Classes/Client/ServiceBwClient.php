<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client;

use JWeiland\ServiceBw2\Client\Helper\LocalizationHelper;
use JWeiland\ServiceBw2\Client\Helper\TokenHelper;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 *  Request class to be used for all Service BW API request classes
 */
class ServiceBwClient implements SingletonInterface
{
    protected const DEFAULT_PAGINATION_CONFIGURATION = [
        'nextItem' => 'nextPage',
        'pageParameter' => 'page',
        'pageSizeParameter' => 'pageSize',
        'pageSize' => 1000
    ];

    protected const DEFAULT_LOCALIZATION_CONFIGURATION = [
        'headerParameter' => 'Accept-Language'
    ];

    protected $path = '';

    protected $isPaginatedRequest = false;
    protected $paginationConfiguration = self::DEFAULT_PAGINATION_CONFIGURATION;

    protected $isLocalizedRequest = false;
    protected $localizationConfiguration = self::DEFAULT_LOCALIZATION_CONFIGURATION;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ExtConf
     */
    protected $extConf;

    /**
     * @var FrontendInterface
     */
    protected $cache;

    /**
     * @param RequestFactory $requestFactory
     * @param Registry $registry
     * @param TokenHelper $tokenHelper
     * @param ExtConf $extConf
     */
    public function __construct(RequestFactory $requestFactory, Registry $registry, TokenHelper $tokenHelper, ExtConf $extConf)
    {
        $this->requestFactory = $requestFactory;
        $this->registry = $registry;
        $this->extConf = $extConf;
        $this->cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('servicebw_request');

        if (!$this->registry->get('ServiceBw', 'token', false)) {
            $tokenHelper->fetchAndSaveToken();
        }
    }

    /**
     * @param string $path
     * @param array $getParameters
     * @param bool $isLocalizedRequest
     * @param bool $isPaginatedRequest
     * @param string|null $body
     * @param array $overridePaginationConfiguration
     * @param array $overrideLocalizationConfiguration
     * @return array
     */
    public function request(
        string $path,
        array $getParameters = [],
        bool $isLocalizedRequest = true,
        bool $isPaginatedRequest = false,
        ?string $body = null,
        array $overridePaginationConfiguration = [],
        array $overrideLocalizationConfiguration = []
    ): array
    {
        $cacheIdentifier = md5(json_encode(func_get_args()));
        if (!$this->cache->has($cacheIdentifier)) {
            $this->path = $path;
            $this->isPaginatedRequest = $isPaginatedRequest;
            $this->isLocalizedRequest = $isLocalizedRequest;
            $this->paginationConfiguration = array_merge(self::DEFAULT_PAGINATION_CONFIGURATION, $overridePaginationConfiguration);
            $this->localizationConfiguration = array_merge(self::DEFAULT_LOCALIZATION_CONFIGURATION, $overrideLocalizationConfiguration);

            $query = array_merge(
                $this->getQueryForDefaultParameters(),
                $getParameters,
                $isPaginatedRequest ? $this->getQueryForPaginatedRequest() : []
            );

            $items = [];
            do {
                $response = $this->requestFactory->request(
                    $this->extConf->getBaseUrl() . $this->path,
                    'GET',
                    [
                        'headers' => $this->getHeaders(),
                        'body' => $body,
                        'query' => $query
                    ]
                );

                $responseBody = (array)json_decode($response->getBody()->getContents(), true);

                $isNextPageSet = false;
                if ($isPaginatedRequest) {
                    if ($isNextPageSet = array_key_exists($this->paginationConfiguration['nextItem'], $responseBody)) {
                        $query[$this->paginationConfiguration['pageParameter']] = $responseBody[$this->paginationConfiguration['nextItem']];
                    }
                    $items = array_merge($items, $responseBody['items']);
                }

            } while($isPaginatedRequest && $isNextPageSet);

            $this->cache->set($cacheIdentifier, $isPaginatedRequest ? $items : $responseBody, ['service_bw2_request']);
        }

        return $this->cache->get($cacheIdentifier);
    }

    protected function getHeaders(): array
    {
        $headers = [
            'Authorization' => $this->registry->get('ServiceBw', 'token', '')
        ];
        if ($this->isLocalizedRequest) {
            $localizationHelper = GeneralUtility::makeInstance(ObjectManager::class)->get(LocalizationHelper::class);
            $headers[$this->localizationConfiguration['headerParameter']] = $localizationHelper->getFrontendLanguageIsoCode();
        }
        return $headers;
    }

    protected function getQueryForPaginatedRequest(): array
    {
        return [
            $this->paginationConfiguration['pageParameter'] => 0,
            $this->paginationConfiguration['pageSizeParameter'] => $this->paginationConfiguration['pageSize']
        ];
    }

    protected function getQueryForDefaultParameters(): array
    {
        $query = ['mandantId' => $this->extConf->getMandant()];
        if ($this->extConf->getAgs()) {
            $query['gebietAgs'] = $this->extConf->getAgs();
        }
        if ($this->extConf->getGebietId()) {
            $query['gebietId'] = $this->extConf->getGebietId();
        }
        return $query;
    }

    /**
     * @return array
     */
    public function getPaginationConfiguration(): array
    {
        return $this->paginationConfiguration;
    }

    /**
     * @param array $paginationConfiguration
     */
    public function setPaginationConfiguration(array $paginationConfiguration): void
    {
        $this->paginationConfiguration = $paginationConfiguration;
    }

    /**
     * @return array
     */
    public function getLocalizationConfiguration(): array
    {
        return $this->localizationConfiguration;
    }

    /**
     * @param array $localizationConfiguration
     */
    public function setLocalizationConfiguration(array $localizationConfiguration): void
    {
        $this->localizationConfiguration = $localizationConfiguration;
    }
}
