<?php

namespace WeDevBr\Bankly\Auth;

use Illuminate\Support\Facades\Http;

/**
 * Class Auth
 *
 * @author Rafael Teixeira <rafael.teixeira@wedev.software>
 * @package WeDevBr\Bankly
 */
final class Auth
{
    /** @var self */
    private static $login;

    /** @var string */
    protected $loginUrl;

    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var string */
    protected $grantType = 'client_credentials';

    /** @var string */
    private $token;

    /** @var string */
    private $tokenExpiry;

    private function __construct()
    {
        //
    }

    /**
     * Returns the instance of this class
     *
     * @param string|null $loginUrl
     * @return self
     */
    public static function login($loginUrl = null)
    {
        if (is_null(self::$login)) {
            self::$login = new Auth();
        }

        if (is_null($loginUrl)) {
            self::$login->loginUrl = config('bankly')['login_url'];
        }

        return self::$login;
    }

    /**
     * @return self
     */
    public function setClientCredentials()
    {
        $this->clientId = $this->clientId ?? config('bankly')['client_id'];
        $this->clientSecret = $this->clientSecret ?? config('bankly')['client_secret'];
        return $this;
    }

    /**
     * @param null|string $clientId
     * @return self
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @param null|string $clientSecret
     * @return self
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @param string $grantType
     * @return self
     */
    public function setGrantType(string $grantType)
    {
        $this->grantType = $grantType;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        if (now()->unix() > $this->tokenExpiry || !$this->token) {
            $this->auth();
        }

        return $this->token;
    }

    /**
     * @return void
     */
    private function auth(): void
    {
        $this->setClientCredentials();

        //TODO: Add auth for username and password
        $body = [
            'grant_type' => $this->grantType,
            'client_secret' => $this->clientSecret,
            'client_id' => $this->clientId
        ];

        $response = Http::asForm()->post($this->loginUrl, $body)->throw()->json();
        $this->token = $response['access_token'];
        $this->tokenExpiry = now()->addSeconds($response['expires_in'])->unix();
    }
}
