<?php

declare(strict_types=1);

namespace Arkitect\Tests\E2E\Cli;

use Arkitect\CLI\PhpArkitectApplication;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

class DebugExpressionCommandTest extends TestCase
{
    public function test_you_need_to_specify_the_expression(): void
    {
        $appTester = $this->createAppTester();
        $appTester->run(['debug:expression']);
        $this->assertEquals(1, $appTester->getStatusCode());
    }

    public function test_zero_results(): void
    {
        $appTester = $this->createAppTester();
        $appTester->run(['debug:expression', 'expression' => 'Extend', 'arguments' => ['NotFound'], '--from-dir' => __DIR__]);
        $this->assertEquals('', $appTester->getDisplay());
    }

    public function test_some_classes_found(): void
    {
        $appTester = $this->createAppTester();
        $appTester->run(['debug:expression', 'expression' => 'NotExtend', 'arguments' => ['NotFound'], '--from-dir' => __DIR__.'/../_fixtures/mvc/Domain']);
        $this->assertEquals("App\Domain\Model\n", $appTester->getDisplay());
    }

    private function createAppTester(): ApplicationTester
    {
        $app = new PhpArkitectApplication();
        $app->setAutoExit(false);

        return new ApplicationTester($app);
    }
}
