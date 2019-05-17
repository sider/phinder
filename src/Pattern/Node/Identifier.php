<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class Identifier extends Node
{
    private static $_WILDCARDS = ['_', '?'];

    protected $parts;

    protected $isFullyQualified;

    public function __construct($isFullyQualified, $parts)
    {
        $this->isFullyQualified = $isFullyQualified;
        $this->parts = $parts;
    }

    protected function matchNode($phpNode)
    {
        if ($this->_isWildcard()) {
            return true;
        }

        $type = $phpNode->getType();

        if ($type === 'Identifier') {
            return $this->_matchParts([$phpNode->name]);
        }

        if ($this->isFullyQualified) {
            return $type === 'Name_FullyQualified'
                && $this->_matchParts($phpNode->parts);
        }

        return $this->_matchParts($phpNode->parts);
    }

    protected function getSubNodeNames()
    {
        return ['isFullyQualified', 'parts'];
    }

    protected function isTargetType($phpNodeType)
    {
        return $this->_isWildcard()
            || $phpNodeType === 'Name'
            || $phpNodeType === 'Name_FullyQualified'
            || $phpNodeType === 'Identifier';
    }

    private function _isWildcard()
    {
        return $this->isFullyQualified === false
            && count($this->parts) === 1
            && in_array($this->parts[0], self::$_WILDCARDS, true);
    }

    private function _matchParts($parts)
    {
        if (count($parts) < count($this->parts)) {
            return false;
        }

        $parts1 = array_reverse($this->parts);
        $parts2 = array_reverse($parts);
        for ($i = 0; $i < count($parts1); ++$i) {
            $p1 = $parts1[$i];
            $p2 = $parts2[$i];
            if (!in_array($p1, self::$_WILDCARDS) && $p1 !== $p2) {
                return false;
            }
        }

        return true;
    }
}
