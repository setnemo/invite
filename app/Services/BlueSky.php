<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

class BlueSky
{
    public const HTTPS_BSKY_SOCIAL_XRPC = 'https://bsky.social/xrpc/';
    private Client $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param string $identifier
     * @param string $password
     * @return string
     * @throws GuzzleException|Throwable
     */
    public function atprotoCreateSession(string $identifier, string $password): string
    {
        try {
            return $this->getClient()->post(static::HTTPS_BSKY_SOCIAL_XRPC . 'com.atproto.server.createSession', [
                'headers' => ['Content-Type' => 'application/json'],
                'body'    => json_encode(['identifier' => $identifier, 'password' => $password,]),
            ])->getBody()->__toString();
        } catch (Throwable $throwable) {
            logger()->error('bskyGetProfile', [
                'identifier' => $identifier,
                'message'    => $throwable->getMessage(),
                'session'    => session()->all(),
                'trace'      => $throwable->getTraceAsString(),
            ]);
            redirect(route('home'));
        }

        throw $throwable;
    }

    /**
     * @param string $token
     * @return string
     * @throws GuzzleException|Throwable
     */
    public function atprotoGetAccountInviteCodes(string $token): string
    {
        try {
            return $this->getClient()->get(static::HTTPS_BSKY_SOCIAL_XRPC . 'com.atproto.server.getAccountInviteCodes', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ])->getBody()->__toString();
        } catch (Throwable $throwable) {
            logger()->error('bskyGetProfile', [
                'token'   => $token,
                'message' => $throwable->getMessage(),
                'session' => session()->all(),
                'trace'   => $throwable->getTraceAsString(),
            ]);
            redirect(route('home'));
        }

        throw $throwable;
    }

    /**
     * @param string $token
     * @param string $identifier
     * @return string
     * @throws GuzzleException|Throwable
     */
    public function bskyGetProfile(string $token, string $identifier): string
    {
        try {
            return $this->getClient()->get(static::HTTPS_BSKY_SOCIAL_XRPC . "app.bsky.actor.getProfile?actor={$identifier}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ])->getBody()->__toString();
        } catch (Throwable $throwable) {
            logger()->error('bskyGetProfile', [
                'token'      => $token,
                'identifier' => $identifier,
                'message'    => $throwable->getMessage(),
                'session'    => session()->all(),
                'trace'      => $throwable->getTraceAsString(),
            ]);
            redirect(route('home'));
        }

        throw $throwable;
    }
}
