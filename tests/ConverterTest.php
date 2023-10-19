<?php

use Currency\Market\IMarket;
use \PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        $this->model = new \Currency\Converter();
    }

    public function testFrom()
    {
        $this->assertInstanceOf(
            \Currency\Converter::class,
            $this->model->from('USD')
        );

        // Lowercase
        $this->assertInstanceOf(
            \Currency\Converter::class,
            $this->model->from('usd')
        );
    }

    public function testTo()
    {
        $this->assertInstanceOf(
            \Currency\Converter::class,
            $this->model->to('Rub')
        );

        // Lowercase
        $this->assertInstanceOf(
            \Currency\Converter::class,
            $this->model->from('rub')
        );
    }

    public function testPresetMarketFactory()
    {
        $market = $this->model->marketFactory('BankOfThai');

        $this->assertInstanceOf(
            \Currency\Market\IMarket::class,
            $market
        );
    }

    public function testConverter()
    {
        $value = 100;

        $converter = new \Currency\Converter();
        $converter->from('USD')->to('usd');

        $converter->useMarket($converter->marketFactory('BankOfThai'));

        $this->assertEquals(
            $value,
            $converter->convert($value)
        );
    }

    public function testConverterMockZero()
    {
        $stub = $this->getMockBuilder(IMarket::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $stub->method('getRate')
            ->willReturn(0);

        $value = 100;

        $converter = new \Currency\Converter();
        $converter->from('USD')->to('rub');

        $converter->useMarket($stub);

        $this->assertEquals(
            0,
            $converter->convert($value)
        );
    }

    public function testConverterMock()
    {
        $stub = $this->getMockBuilder(IMarket::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $stub->method('getRate')
            ->willReturn(2.22);

        $value = 100;

        $converter = new \Currency\Converter();
        $converter->from('USD')->to('rub');

        $converter->useMarket($stub);

        $this->assertEquals(
            ($value * 2.22),
            $converter->convert($value)
        );
    }

    /**
     * @expectedException Exception
     */
    public function testFromException()
    {
        $this->model->from('not-existing-iso');
    }

    /**
     * @expectedException Exception
     */
    public function testToException()
    {
        $this->model->to('not-existing-iso');
    }

    /**
     * @expectedException Exception
     */
    public function testMarketFactoryException()
    {
        $this->model->marketFactory('Not existing');
        $this->model->marketFactory('BankOfthai');
        $this->model->marketFactory();
        $this->model->marketFactory('');
    }

    /**
     * @expectedException TypeError
    */
    public function testUseMarketException()
    {
        $this->model->useMarket('Test');
    }

    /**
     * @expectedException Exception
     */
    public function testMarketUndefined()
    {
        $value = 100;

        $this->model->convert($value);
    }

}