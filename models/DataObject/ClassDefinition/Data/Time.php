<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Model\DataObject\ClassDefinition\Data;

use Pimcore\Model;

class Time extends Model\DataObject\ClassDefinition\Data\Input
{
    /**
     * Static type of this element
     *
     * @internal
     *
     * @var string
     */
    public $fieldtype = 'time';

    /**
     * Column length
     *
     * @internal
     *
     * @var int
     */
    public $columnLength = 5;

    /**
     * @internal
     *
     * @var string|null
     */
    public $minValue;

    /**
     * @internal
     *
     * @var string|null
     */
    public $maxValue;

    /**
     * @internal
     *
     * @var int
     */
    public $increment = 15 ;

    /**
     * @return string|null
     */
    public function getMinValue()
    {
        return $this->minValue;
    }

    /**
     * @param string|null $minValue
     */
    public function setMinValue($minValue)
    {
        if (strlen($minValue)) {
            $this->minValue = $this->toTime($minValue);
        } else {
            $this->minValue = null;
        }
    }

    /**
     * @return string|null
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }

    /**
     * @param string|null $maxValue
     */
    public function setMaxValue($maxValue)
    {
        if (strlen($maxValue)) {
            $this->maxValue = $this->toTime($maxValue);
        } else {
            $this->maxValue = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkValidity($data, $omitMandatoryCheck = false, $params = [])
    {
        parent::checkValidity($data, $omitMandatoryCheck);

        if (is_string($data)) {
            if (!preg_match('/^(2[0-3]|[01][0-9]):[0-5][0-9]$/', $data) && $data !== '') {
                throw new Model\Element\ValidationException('Wrong time format given must be a 5 digit string (eg: 06:49) [ '.$this->getName().' ]');
            }
        } elseif (!empty($data)) {
            throw new Model\Element\ValidationException('Wrong time format given must be a 5 digit string (eg: 06:49) [ '.$this->getName().' ]');
        }

        if (!$omitMandatoryCheck && strlen($data)) {
            if (!$this->toTime($data)) {
                throw new Model\Element\ValidationException('Wrong time format given must be a 5 digit string (eg: 06:49) [ '.$this->getName().' ]');
            }

            if (strlen($this->getMinValue()) && $this->isEarlier($this->getMinValue(), $data)) {
                throw new Model\Element\ValidationException('Value in field [ '.$this->getName().' ] is not at least ' . $this->getMinValue());
            }

            if (strlen($this->getMaxValue()) && $this->isLater($this->getMaxValue(), $data)) {
                throw new Model\Element\ValidationException('Value in field [ ' . $this->getName() . ' ] is bigger than ' . $this->getMaxValue());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isDiffChangeAllowed($object, $params = [])
    {
        return true;
    }

    /**
     * @param string|null $data
     *
     * @return bool
     */
    public function isEmpty($data)
    {
        return strlen($data) !== 5;
    }

    /**
     * Returns a 5 digit time string of a given time
     *
     * @param string $timestamp
     *
     * @return null|string
     */
    private function toTime($timestamp)
    {
        $timestamp = strtotime($timestamp);
        if (!$timestamp) {
            return null;
        }

        return date('H:i', $timestamp);
    }

    /**
     * Returns a timestamp representation of a given time
     *
     * @param string $string
     * @param int|null $baseTimestamp
     *
     * @return int
     */
    private function toTimestamp($string, $baseTimestamp = null)
    {
        if ($baseTimestamp === null) {
            $baseTimestamp = time();
        }

        return strtotime($string, $baseTimestamp);
    }

    /**
     * Returns whether or not a time is earlier than the subject
     *
     * @param string $subject
     * @param string $comparison
     *
     * @return bool
     */
    private function isEarlier($subject, $comparison)
    {
        $baseTs = time();

        return $this->toTimestamp($subject, $baseTs) > $this->toTimestamp($comparison, $baseTs);
    }

    /**
     * Returns whether or not a time is later than the subject
     *
     * @param string $subject
     * @param string $comparison
     *
     * @return bool
     */
    private function isLater($subject, $comparison)
    {
        $baseTs = time();

        return $this->toTimestamp($subject, $baseTs) < $this->toTimestamp($comparison, $baseTs);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataForSearchIndex($object, $params = [])
    {
        return '';
    }

    /**
     * @return int
     */
    public function getIncrement()
    {
        return $this->increment;
    }

    /**
     * @param int $increment
     */
    public function setIncrement($increment)
    {
        $this->increment = (int) $increment;
    }
}
