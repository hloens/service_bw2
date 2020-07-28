<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request\Zustaendigkeiten;

use JWeiland\ServiceBw2\PostProcessor\PublishStatusPostProcessor;
use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class Leistung
 *
 * Description provided by service_bw API documentation:
 * Liefert alle Zuständigkeiten für eine bestimmte Leistung als paginierte Liste.
 */
class Leistung extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/zustaendigkeiten/leistung/{leistungId}/?page={page}&pageSize={pageSize}';

    /**
     * @var array
     */
    protected $additionalPostProcessorClassNames = [
        PublishStatusPostProcessor::class
    ];

    /**
     * @var array
     */
    protected $allowedParameters = [
        'leistungId' => [
            'dataType' => 'integer',
            'required' => true
        ],
        'page' => [
            'dataType' => 'integer',
            'required' => true
        ],
        'pageSize' => [
            'dataType' => 'integer',
            'required' => true
        ],
    ];
}
