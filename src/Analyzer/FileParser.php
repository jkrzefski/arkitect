<?php
declare(strict_types=1);

namespace Arkitect\Analyzer;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;

class FileParser implements Parser
{
    /** @var \PhpParser\Parser */
    private $parser;

    /** @var \PhpParser\NodeTraverser */
    private $traverser;

    /** @var FileVisitor */
    private $fileVisitor;

    public function __construct()
    {
        $this->fileVisitor = new FileVisitor();

        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new NameResolver());
        $this->traverser->addVisitor($this->fileVisitor);
    }

    public function getClassDescriptions(): array
    {
        return $this->fileVisitor->getClassDescriptions();
    }

    public function parse(string $fileContent): void
    {
        try {
            $this->fileVisitor->clearParsedClassDescriptions();

            $stmts = $this->parser->parse($fileContent);

            $this->traverser->traverse($stmts);
        } catch (\Throwable $e) {
            echo 'Parse Error: ', $e->getMessage();
            print_r($e->getTraceAsString());
        }
    }
}
