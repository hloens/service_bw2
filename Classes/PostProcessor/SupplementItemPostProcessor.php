<?php
declare(strict_types=1);
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
 * Post processor to replace default "Leistungen" items by supplement items.
 */
class SupplementItemPostProcessor extends AbstractPostProcessor
{

    /**
     * @param mixed $response
     * @return array
     */
    public function process($response): array
    {
        $response = (array)$response;
        foreach ($response as $key => $value) {
            if (
                array_key_exists('type', $value)
                && $value['type'] === 'ERGAENZUNG'
                && array_key_exists($value['landesLeistungId'], $response)
            ) {
                 unset($response[$value['landesLeistungId']]);
            }
        }
        return $response;
    }
}
