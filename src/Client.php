<?php
declare(strict_types=1);

namespace Helis\EnebaClient;

use DateTime;
use Helis\EnebaClient\Credentials\ClientCredentialsInterface;
use Helis\EnebaClient\Denormalizer\Denormalizer;
use Helis\EnebaClient\Denormalizer\DenormalizerInterface;
use Helis\EnebaClient\Enum\CallbackTypeEnum;
use Helis\EnebaClient\Enum\SelectionSetFactoryProviderNameEnum as ProviderNameEnum;
use Helis\EnebaClient\Model\ActionResponse;
use Helis\EnebaClient\Model\Callbacks;
use Helis\EnebaClient\Model\CallbackResponse;
use Helis\EnebaClient\Model\Input\StockFilter;
use Helis\EnebaClient\Model\Relay\Connection\StockConnection;
use Helis\EnebaClient\Provider\SelectionSetFactoryProvider;
use Helis\EnebaClient\Provider\SelectionSetFactoryProviderInterface;
use Helis\EnebaClient\Storage\AccessTokenStorageInterface;
use Helis\EnebaClient\Storage\ArrayAccessTokenStorage;
use Fypex\GraphqlQueryBuilder\Argument\VariableValue;
use Fypex\GraphqlQueryBuilder\Mutation;
use Fypex\GraphqlQueryBuilder\SelectionSet\Field;
use Fypex\GraphqlQueryBuilder\Variable\ScalarVariable;
use Http\Client\Curl\Client as CurlClient;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Money\Money;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Client implements ClientInterface
{
    use TClient;

    private $logger;
    private $client;
    private $messageFactory;
    private $denormalizer;
    private $credentials;
    private $tokenStorage;
    private $selectionSetFactoryProvider;

    /**
     * @var string|null
     */
    private $oauthUrl;

    /**
     * @var string|null
     */
    private $graphQLUrl;

    /**
     * @var string|null
     */
    private $isDevelopMode = false;
    /**
     * @var string|null
     */
    private $oauthClientId;

    public function __construct(
        ClientCredentialsInterface $credentials,
        ?HttpClient $client = null,
        ?MessageFactory $messageFactory = null,
        ?DenormalizerInterface $denormalizer = null,
        ?SelectionSetFactoryProviderInterface $selectionSetFactoryProvider = null,
        ?AccessTokenStorageInterface $tokenStorage = null,
        ?LoggerInterface $logger = null
    ) {
        $this->credentials = $credentials;
        $this->logger = $logger;
        $this->client = $client ?: new CurlClient();
        $this->messageFactory = $messageFactory ?: new DiactorosMessageFactory();
        $this->tokenStorage = $tokenStorage ?: new ArrayAccessTokenStorage();
        $this->denormalizer = $denormalizer ?: Denormalizer::getInstance();
        $this->selectionSetFactoryProvider = $selectionSetFactoryProvider ?: SelectionSetFactoryProvider::getInstance();
    }

//    public function createAuction(
//        UuidInterface $productId,
//        bool $enabled,
//        array $keys, bool
//        $autoRenew,
//        Money $price,
//        Money $acquisitionPrice,
//        int $declaredStock = null
//    ): ActionResponse
//    {
//
//        $mutation = (new Mutation())
//            ->addField(
//                new Field(
//                    Eneba::GQL_CREATE_AUCTION_MUTATION,
//                    $this->selectionSetFactoryProvider->get(ProviderNameEnum::ACTION_RESPONSE())->get(),
//                    [
//                        'input' => new VariableValue('$input'),
//                    ]
//                )
//            )
//            ->addVariable(new ScalarVariable('$input', 'S_API_CreateAuctionInput', true));
//
//        $input = [
//            'input' => [
//                'keys' => $keys,
//                'enabled' => $enabled,
//                'autoRenew' => $autoRenew,
//                'productId' => $productId->toString(),
//                'price' => [
//                    'amount' => (int)$price->getAmount(),
//                    'currency' => $price->getCurrency()->getCode(),
//                ],
//                'acquisitionPrice' => [
//                    'amount' => (int)$acquisitionPrice->getAmount(),
//                    'currency' => $acquisitionPrice->getCurrency()->getCode(),
//                ],
//                'declaredStock' => $declaredStock
//            ],
//        ];
//
//        if (is_int($declaredStock)){
//            $input['input']['declaredStock'] = $declaredStock;
//        }
//
//        $request = $this->createMessage($mutation->toString(), $input);
//
//        $response = $this->client->sendRequest($request);
//        $data = $this->handleResponse($response);
//
//        return $this->denormalizer->denormalize(
//            $data['data'][Eneba::GQL_CREATE_AUCTION_MUTATION],
//            ActionResponse::class
//        );
//
//    }


    public function updateAuction(UuidInterface $auctionId, int $declaredStock): ActionResponse
    {
        $mutation = (new Mutation())
            ->addField(
                new Field(
                    Eneba::GQL_UPDATE_AUCTION_MUTATION,
                    $this->selectionSetFactoryProvider->get(ProviderNameEnum::ACTION_RESPONSE())->get(),
                    [
                        'input' => new VariableValue('$input'),
                    ]
                )
            )
            ->addVariable(new ScalarVariable('$input', 'S_API_UpdateAuctionInput', true));

        $input = [
            'input' => [
                'id' => $auctionId->toString(),
                'declaredStock' => $declaredStock
            ],
        ];

        $request = $this->createMessage($mutation->toString(), $input);
        $response = $this->client->sendRequest($request);
        $data = $this->handleResponse($response);

        return $this->denormalizer->denormalize(
            $data['data'][Eneba::GQL_UPDATE_AUCTION_MUTATION],
            ActionResponse::class
        );
    }
    public function getStock(?StockFilter $filter = null): StockConnection
    {

        $query = $this->createConnectionQuery(
            Eneba::GQL_STOCK_QUERY,
            $this->selectionSetFactoryProvider->get(ProviderNameEnum::STOCK_CONNECTION())->get(),
            [
                'stockId' => new VariableValue('$stockId'),
                'productId' => new VariableValue('$productId'),
            ],
        );

        $query->addVariable(new ScalarVariable('$stockId', 'S_Uuid'));
        $query->addVariable(new ScalarVariable('$productId', 'S_Uuid'));

        $request = $this->createMessage($query->toString(), $filter ? [
            'cursor' => $this->generateCursor($filter->getPage(), $filter->getPerPage()),
            'limit' => $filter->getPerPage(),
            'stockId' => $filter->getStockId(),
            'productId' => $filter->getProductId(),
        ] : []);

        $response = $this->client->sendRequest($request);
        $data = $this->handleResponse($response);

        return $this->denormalizer->denormalize($data['data'][Eneba::GQL_STOCK_QUERY], StockConnection::class);
    }
    public function removeCallback(UuidInterface $callback_id){

        $mutation = (new Mutation())
            ->addField(
                new Field(
                    Eneba::GQL_REMOVE_CALLBACK_MUTATION,
                    $this->selectionSetFactoryProvider->get(ProviderNameEnum::CALLBACK())->get(),
                    [
                        'input' => new VariableValue('$input'),
                    ]
                )
            )
            ->addVariable(new ScalarVariable('$input', 'P_API_RemoveCallbackInput', true));

        $request = $this->createMessage($mutation->toString(), [
            'input' => [
                'id' => $callback_id->toString(),
            ]
        ]);

        $response = $this->client->sendRequest($request);
        $data = $this->handleResponse($response);

        return $this->denormalizer->denormalize(
            $data['data'][Eneba::GQL_REMOVE_CALLBACK_MUTATION],
            CallbackResponse::class
        );

    }
    public function setApiCallback(CallbackTypeEnum $type, string $url, string $authorization): CallbackResponse
    {

        $mutation = (new Mutation())
            ->addField(
                new Field(
                    Eneba::GQL_REGISTER_CALLBACK_MUTATION,
                    $this->selectionSetFactoryProvider->get(ProviderNameEnum::CALLBACK())->get(),
                    [
                        'input' => new VariableValue('$input'),
                    ]
                )
            )
            ->addVariable(new ScalarVariable('$input', 'P_API_RegisterCallbackInput', true));


        $request = $this->createMessage($mutation->toString(), [
            'input' => [
                'type' => $type->getValue(),
                'url' => $url,
                'authorization' => $authorization,
            ]
        ]);

        $response = $this->client->sendRequest($request);

        $data = $this->handleResponse($response);

        return $this->denormalizer->denormalize(
            $data['data'][Eneba::GQL_REGISTER_CALLBACK_MUTATION],
            CallbackResponse::class
        );
    }

    /** @return array<Callbacks> */
    public function getApiCallbacks(): array
    {

        $query = $this->createConnectionQuery(
            Eneba::GQL_CALLBACK_QUERY,
            $this->selectionSetFactoryProvider->get(ProviderNameEnum::CALLBACKS())->get(), [], false
        );

        $request = $this->createMessage($query->toString(),[]);

        $response = $this->client->sendRequest($request);
        $data = $this->handleResponse($response);

        return $this->denormalizer->denormalize($data['data'][Eneba::GQL_CALLBACK_QUERY], Callbacks::class);
    }

    public function triggerCallback(
        CallbackTypeEnum $type,
        UuidInterface $orderId,
        UuidInterface $auctionId,
        Money $price,
        int $keyCount
    ): CallbackResponse
    {

        $mutation = (new Mutation())
            ->addField(
                new Field(
                    Eneba::GQL_TRIGGER_CALLBACK_MUTATION,
                    $this->selectionSetFactoryProvider->get(ProviderNameEnum::CALLBACK())->get(),
                    [
                        'input' => new VariableValue('$input'),
                    ]
                )
            )
            ->addVariable(new ScalarVariable('$input', 'P_API_TriggerCallbackInput', true));


        $request = $this->createMessage($mutation->toString(), [
            'input' => [
                'type' => $type->getValue(),
                'orderId' => $orderId->toString(),
                'auction' => [
                    'auctionId' => $auctionId->toString(),
                    'price' => [
                        'amount' => $price->getAmount(),
                        'currency' => $price->getCurrency(),
                    ],
                    'keyCount' => $keyCount
                ],
            ]
        ]);

        $response = $this->client->sendRequest($request);
        $data = $this->handleResponse($response);

        return $this->denormalizer->denormalize(
            $data['data'][Eneba::GQL_TRIGGER_CALLBACK_MUTATION],
            CallbackResponse::class
        );
    }

}
