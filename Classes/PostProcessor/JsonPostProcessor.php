<?php
namespace JWeiland\ServiceBw2\PostProcessor;

/*
 * This file is part of the service_bw2 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Class JsonPostProcessor
 */
class JsonPostProcessor extends AbstractPostProcessor
{
    /**
     * Post process json response
     *
     * @param mixed $response
     * @return array
     * @throws \HttpResponseException if JSON decode fails
     */
    public function process($response): array
    {
        $response = trim((string)$response);
        if (empty($response)) {
            return [];
        }
        $decodedResponse = json_decode($response, true);
        if (!empty($decodedResponse) && \is_array($decodedResponse) && isset($decodedResponse['items'])) {
            // sometimes the records are not at array root, they are in array key "items"
            // if so then the array inside items will used as root and all other properties
            // will be copied into $arr['_root']
            $processedResponse = $decodedResponse['items'];
            unset($decodedResponse['items']);
            $processedResponse['_root'] = $decodedResponse;
            $decodedResponse = $processedResponse;
        } elseif ($decodedResponse === null) {
            // throw exception if json could not be decoded!
            throw new \HttpResponseException(
                'Could not decode the JSON from HTTP response!',
                1525850941
            );
        }
        return $decodedResponse;
    }
}
