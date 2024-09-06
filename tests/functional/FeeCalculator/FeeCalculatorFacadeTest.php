<?php

namespace TfcTest\Functional\FeeCalculator;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Money\Currency;
use Money\CurrencyPair;
use Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tfc\DataModel\CardIssuer;
use Tfc\DataModel\Country;
use Tfc\DataModel\Transaction;
use Tfc\ExchangeRate\ExchangeRateFacadeInterface;
use Tfc\FeeCalculator\FeeCalculatorFacade;
use Tfc\FeeCalculator\FeeCalculatorFactory;
use Tfc\PaymentCard\PaymentCardFacadeInterface;

class FeeCalculatorFacadeTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public static function calculateTransactionFeeDataProvider()
    {
        return [
            [
                'config' => [
                    'eu' => 0.005,
                    'non-eu' => 0.01,
                ],
                'transactionData' => [
                    'binCode' => '112233',
                    'amount' => new Money(100000, new Currency('EUR')),
                ],
                'feeCurrency' => new Currency('USD'),
                'exchangePair' => new CurrencyPair(
                    new Currency('EUR'),
                    new Currency('USD'),
                    '1.15'
                ),
                'cardIssuerData' => [
                    'country' => [
                        'currency' => new Currency('EUR'),
                        'name' => 'Sweden',
                        'alpha2' => 'SE', 
                    ],
                ],
                'expectedFeeEmount' => 575,
            ],
        ];
    }

    #[DataProvider('calculateTransactionFeeDataProvider')]
    public function testCalculateTransactionFee(
        array $config,
        array $transactionData,
        Currency $feeCurrency,
        CurrencyPair $exchangePair,
        array $cardIssuerData,
        int $expectedFeeEmount,
    ): void {
        $cardIssuer = $this->makeCardIssuer($cardIssuerData);
        $transaction = $this->makeTransaction($transactionData);

        $feeCalculatorFactory = $this->createFeeCalculatorFactoryMock(
            $config,
            $transaction,
            $feeCurrency,
            $exchangePair,
            $cardIssuer
        );

        $feeCalculatorFacade = new FeeCalculatorFacade($feeCalculatorFactory);

        $feeAmount = $feeCalculatorFacade->calculateTransactionFee(
            $transaction,
            $feeCurrency,
        );

        $this->assertEquals($feeAmount->getCurrency(), $feeCurrency);
        $this->assertEquals($feeAmount->getAmount(), $expectedFeeEmount);
    }

    protected function makeTransaction(array $data): Transaction
    {
        return Transaction::fromArray($data);
    }

    protected function makeCardIssuer(array $data): CardIssuer
    {
        $data['country'] = $this->makeCountry($data['country']);

        return CardIssuer::fromArray($data);
    }

    protected function makeCountry(array $data): Country
    {
        return Country::fromArray($data);
    }

    /**
     * @return \Mockery\Mock&\Tfc\FeeCalculator\FeeCalculatorFactory
     */
    protected function createFeeCalculatorFactoryMock(
        array $config,
        Transaction $transaction,
        Currency $feeCurrency,
        CurrencyPair $exchangePair,
        CardIssuer $cardIssuer,
    ) {
        $exchangeRateFacadeMock = Mockery::mock(ExchangeRateFacadeInterface::class);
        $exchangeRateFacadeMock
            ->shouldReceive('getExchangePair')
            ->once()
            ->with($transaction->getAmount()->getCurrency(), $feeCurrency)
            ->andReturn($exchangePair);

        $paymentCardFacadeMock = Mockery::mock(PaymentCardFacadeInterface::class);
        $paymentCardFacadeMock
            ->shouldReceive('getPaymentCardIssuer')
            ->once()
            ->with($transaction->getBinCode())
            ->andReturn($cardIssuer);

        $feeCalculatorFactoryMock = Mockery::mock(FeeCalculatorFactory::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $feeCalculatorFactoryMock
            ->shouldReceive('getConfig')
            ->once()
            ->andReturn($config);
        $feeCalculatorFactoryMock
            ->shouldReceive('provideExchangeRateFacade')
            ->once()
            ->andReturn($exchangeRateFacadeMock);
        $feeCalculatorFactoryMock
            ->shouldReceive('providePaymentCardFacade')
            ->once()
            ->andReturn($paymentCardFacadeMock);

        return $feeCalculatorFactoryMock;
    }
}