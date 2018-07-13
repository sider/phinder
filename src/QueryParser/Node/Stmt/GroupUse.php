<?php declare(strict_types=1);

namespace Phinder\QueryParser\Node\Stmt;

use Phinder\QueryParser\Node\Name;
use Phinder\QueryParser\Node\Stmt;

class GroupUse extends Stmt
{
    /** @var int Type of group use */
    public $type;
    /** @var Name Prefix for uses */
    public $prefix;
    /** @var UseUse[] Uses */
    public $uses;

    /**
     * Constructs a group use node.
     *
     * @param Name     $prefix     Prefix for uses
     * @param UseUse[] $uses       Uses
     * @param int      $type       Type of group use
     * @param array    $attributes Additional attributes
     */
    public function __construct(Name $prefix, array $uses, int $type = Use_::TYPE_NORMAL, array $attributes = []) {
        parent::__construct($attributes);
        $this->type = $type;
        $this->prefix = $prefix;
        $this->uses = $uses;
    }

    public function getSubNodeNames() : array {
        return ['type', 'prefix', 'uses'];
    }
    
    public function getType() : string {
        return 'Stmt_GroupUse';
    }
}
