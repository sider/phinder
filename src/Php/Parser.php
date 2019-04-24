<?php

namespace Phinder\Php;

use PhpParser\Lexer;
use PhpParser\ParserFactory;
use PhpParser\Error as ParseError;
use RecursiveIteratorIterator as RecItrItr;
use RecursiveDirectoryIterator as RecDirItr;
use Phinder\Error\FileNotFound;
use Phinder\Error\InvalidPhp;

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
                if ($ext === 'php' || $ext === 'ctp') {
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
        $content = @file_get_contents($path);
        if ($content === false) {
            throw new FileNotFound($path);
        }

        try {
            $ast = $this->parseString($content);

            return new File($path, $ast);
        } catch (ParseError $e) {
            throw new InvalidPhp($path, $e);
        }
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
