<?php declare(strict_types=1);

namespace Phinder\QueryParser\Builder;

use Phinder\QueryParser;
use Phinder\QueryParser\BuilderHelpers;
use Phinder\QueryParser\Node;
use Phinder\QueryParser\Node\Stmt;

class Function_ extends FunctionLike
{
    protected $name;
    protected $stmts = [];

    /**
     * Creates a function builder.
     *
     * @param string $name Name of the function
     */
    public function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * Adds a statement.
     *
     * @param Node|QueryParser\Builder $stmt The statement to add
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function addStmt($stmt) {
        $this->stmts[] = BuilderHelpers::normalizeStmt($stmt);

        return $this;
    }

    /**
     * Returns the built function node.
     *
     * @return Stmt\Function_ The built function node
     */
    public function getNode() : Node {
        return new Stmt\Function_($this->name, [
            'byRef'      => $this->returnByRef,
            'params'     => $this->params,
            'returnType' => $this->returnType,
            'stmts'      => $this->stmts,
        ], $this->attributes);
    }
}
