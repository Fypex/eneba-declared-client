<?php


namespace Helis\EnebaClient;

use DateTime;
use Fypex\GraphqlQueryBuilder\Argument\VariableValue;
use Fypex\GraphqlQueryBuilder\Query;
use Fypex\GraphqlQueryBuilder\SelectionSet\Field;
use Fypex\GraphqlQueryBuilder\SelectionSet\Fragment;
use Fypex\GraphqlQueryBuilder\SelectionSet\SelectionSet;
use Fypex\GraphqlQueryBuilder\Variable\ScalarVariable;
use Fypex\GraphqlQueryBuilder\Variable\StringVariable;
use Helis\EnebaClient\Credentials\ClientCredentialsInterface;
use Helis\EnebaClient\Exception\GeneralException;
use Helis\EnebaClient\Exception\GraphQLException;
use Helis\EnebaClient\Exception\HttpException;
use Helis\EnebaClient\Model\AccessToken;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait TClient
{
    public function setOauthClientId(?string $oauthClientId): void
    {
        $this->oauthClientId = $oauthClientId;
    }

    public function setOauthUrl(?string $oauthUrl): void
    {
        $this->oauthUrl = $oauthUrl;
    }

    public function setGraphQLUrl(?string $graphQLUrl): void
    {
        $this->graphQLUrl = $graphQLUrl;
    }

    public function setDevelopMode(bool $dev = false): void
    {
        $this->isDevelopMode = $dev;
    }




    private function getGraphQLUrl(): string
    {
        return $this->graphQLUrl ?: Eneba::DEFAULT_GRAPHQL_URL;
    }

    private function isDevelopMode(): string
    {
        return $this->isDevelopMode;
    }

    private function getOauthUrl(): string
    {
        return $this->oauthUrl ?: Eneba::DEFAULT_OAUTH_URL;
    }

    private function getOauthClientId(): string
    {
        return $this->oauthClientId ?: Eneba::DEFAULT_OAUTH_CLIENT_ID;
    }

    private function handleResponse(ResponseInterface $response, bool $graphql = true): array
    {
        if (!$this->isJsonResponse($response)) {
            $this->logger && $this->logger->error('Response does not contain valid JSON data', [
                'response' => $response,
            ]);
            throw new GeneralException('Response is not "application/json" type: '.$response->getStatusCode().', '.$response->getReasonPhrase());
        }

        $data = json_decode((string)$response->getBody(), true);

        if ($response->getStatusCode() !== 200) {
            $this->logger && $this->logger->error('Response status is not 200', [
                'response' => $response,
            ]);

            if (!$graphql) {
                throw new HttpException($data['message'] ?? 'HTTP exception');
            }

            $messages = array_column($data['errors'] ?? [], 'message');
            $exceptions = array_column(array_column($data['errors'] ?? [], 'extensions'), 'code');
            throw new GraphQLException($messages, $exceptions);
        }

        if (isset($data['errors'])) {
            $messages = array_column($data['errors'] ?? [], 'message');
            $exceptions = array_column(array_column($data['errors'] ?? [], 'extensions'), 'code');
            throw new GraphQLException($messages, $exceptions);
        }

        return $data;
    }

    private function createConnectionQuery(
        string $name,
        SelectionSet $selectionSet,
        array $selectionSetArgs = [],
        bool $setSelectionSetArgs = true,
        ?Fragment $fragment = null
    ): Query {

        if ($setSelectionSetArgs){
            $selectionSetArgs = [
                    'after' => new VariableValue('$cursor'),
                    'first' => new VariableValue('$limit'),
                ] + $selectionSetArgs;
        }

        $query = (new Query())
            ->addField(
                new Field($name, $selectionSet, $selectionSetArgs ?? [])
            );

        if ($setSelectionSetArgs){
            $query
                ->addVariable(new StringVariable('$cursor'))
                ->addVariable(new ScalarVariable('$limit', 'Int'));
        }

        if ($fragment){
            $query->addFragment($fragment);
        }

        return $query;

    }

    private function createConnectionQueryWithFragment(
        string $name,
        Fragment $fragment,
        array $selectionSetArgs = []
    ): Query
    {

        return (new Query())
            ->addField(
                new Field($name, $fragment->getSelection(), [
                        'after' => new VariableValue('$cursor'),
                        'first' => new VariableValue('$limit'),
                    ] + $selectionSetArgs)
            )
            ->addVariable(new StringVariable('$cursor'))
            ->addVariable(new ScalarVariable('$limit', 'Int'))
            ->addFragment($fragment);

    }

    private function getHeaders(string $contentType = 'application/json', bool $authorized = true): array
    {
        $headers = [
            'X-API-Client-Version' => Eneba::API_CLIENT_VERSION,
            'Content-Type' => $contentType,
            'Accept' => 'application/json',
        ];

        if ($authorized) {
            $headers['Authorization'] = 'Bearer ' . $this->getAuthorizationToken();
        }

        return $headers;
    }

    private function getAuthorizationToken(): string
    {
        $storedToken = $this->tokenStorage->get();
        if ($storedToken) {
            return $storedToken;
        }

        $accessToken = $this->authorize($this->credentials);
        $this->tokenStorage->set(
            $accessToken->getToken(),
            new DateTime('+' . ($accessToken->getExpiresIn() - Eneba::TOKEN_EXPIRATION_TIME_WINDOW) . ' seconds')
        );

        return $accessToken->getToken();
    }

    private function authorize(ClientCredentialsInterface $credentials): AccessToken
    {

        $body = http_build_query([
            'grant_type' => Eneba::OAUTH_GRANT_TYPE,
            'client_id' => $this->getOauthClientId(),
            'id' => $credentials->getClientId(),
            'secret' => $credentials->getClientSecret(),
        ]);

        $request = $this->messageFactory->createRequest(
            'POST',
            $this->getOauthUrl(),
            $this->getHeaders('application/x-www-form-urlencoded', false),
            $body
        );

        $response = $this->client->sendRequest($request);
        $data = $this->handleResponse($response, false);

        return $this->denormalizer->denormalize($data, AccessToken::class);
    }

    private function generateCursor(int $page, int $perPage = 20): ?string
    {
        if ($page === 1) {
            return null;
        }

        return base64_encode('arrayconnection:' . (($page - 1) * $perPage - (int)($page > 1)));
    }

    private function createMessage(string $query, array $variables = []): RequestInterface
    {

        return $this->messageFactory->createRequest(
            'POST',
            $this->getGraphQLUrl(),
            $this->getHeaders(),
            json_encode(['query' => $query, 'variables' => $variables,])
        );

    }

    private function isJsonResponse(ResponseInterface $response): bool
    {
        $header = $response->getHeader('Content-Type')[0] ?? null;
        [$type,] = explode(';', $header);

        return $type === 'application/json';
    }
}
