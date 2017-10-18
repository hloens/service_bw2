<?php

namespace JWeiland\ServiceBw2\Domain\Model;

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
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Gueltigkeit extends AbstractEntity
{
    /**
     * This is NOT the ID of TYPO3 DB. It's the original ID from Service BW
     *
     * @var int
     */
    protected $id = 0;

    /**
     * @var string
     */
    protected $zusatz = '';

    /**
     * @var \DateTime
     */
    protected $beginn;

    /**
     * @var \DateTime
     */
    protected $ende;

    /**
     * Returns the id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Returns the beginn
     *
     * @return \DateTime $beginn
     */
    public function getBeginn()
    {
        return $this->beginn;
    }

    /**
     * Sets the beginn
     *
     * @param \DateTime $beginn
     *
     * @return void
     */
    public function setBeginn(\DateTime $beginn = null)
    {
        $this->beginn = $beginn;
    }

    /**
     * Returns the ende
     *
     * @return \DateTime $ende
     */
    public function getEnde()
    {
        return $this->ende;
    }

    /**
     * Sets the ende
     *
     * @param \DateTime $ende
     *
     * @return void
     */
    public function setEnde(\DateTime $ende = null)
    {
        $this->ende = $ende;
    }
}
