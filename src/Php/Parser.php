<?php

namespace Phinder\Php;

use PhpParser\Lexer;
use PhpParser\ParserFactory;
use RecursiveIteratorIterator as RecItrItr;
use RecursiveDirectoryIterator as RecDirItr;
use Phinder\Error\FileNotFound;
use Phinder\Utility;

final class Parser
{
    private $_phpParser = null;

    public function __construct()
    {
        $this->_phpParser = (new ParserFactory())->create(
            ParserFactory::PREFER_PHP7,
            self::_createLexer()
        );
    }

    public function parseFilesInDirectory($path)
    {
        if (is_dir($path)) {
            foreach (new RecItrItr(new RecDirItr($path)) as $itr) {
                $ext = $itr->getExtension();
                if ($ext === 'php') {
                    yield $this->parseFile($itr->getPathname());
                }
            }
        } elseif (is_file($path)) {
            yield $this->parseFile($path);
        } else {
            throw new FileNotFound($path);
        }
    }

    public function parseFile($path)
    {
        $ast = $this->parseString(Utility::fileGetContents($path));

        return new File($path, $ast);
    }

    public function parseString($string)
    {
        return $this->_phpParser->parse($string);
    }

    private static function _createLexer()
    {
        return new Lexer(
            [
                'usedAttributes' => [
                    'startLine',
                    'endLine',
                    'startTokenPos',
                    'endTokenPos',
                    'startFilePos',
                    'endFilePos',
                ],
            ]
        );
    }
}
