<?php declare(strict_types=1);

namespace Phinder\QueryParser\Node\Stmt;

use Phinder\QueryParser\Node;

class Unset_ extends Node\Stmt
{
    /** @var Node\Expr[] Variables to unset */
    public $vars;

    /**
     * Constructs an unset node.
     *
     * @param Node\Expr[] $vars       Variables to unset
     * @param array       $attributes Additional attributes
     */
    public function __construct(array $vars, array $attributes = []) {
        parent::__construct($attributes);
        $this->vars = $vars;
    }

    public function getSubNodeNames() : array {
        return ['vars'];
    }
    
    public function getType() : string {
        return 'Stmt_Unset';
    }
}
