<?php
declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use SpotifyWebAPI\Session as SpotifySession;

class SpotifyAuthListener
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var string
     */
    private $spotifyKey;

    /**
     * @var string
     */
    private $spotifySecret;

    public function __construct(SessionInterface $session, string $spotifyKey, string $spotifySecret)
    {
        $this->session = $session;
        $this->spotifyKey = $spotifyKey;
        $this->spotifySecret = $spotifySecret;
    }

    public function onKernelRequest()
    {
        $spotifySession = new SpotifySession(
            $this->spotifyKey,
            $this->spotifySecret,
            'http://127.0.0.1:8000/callback'
        );

        if(!$this->session->has('spotify_code')) {

            $options = [
                'scope' => [
                    'user-read-email',
                ],
            ];

            return new RedirectResponse($spotifySession->getAuthorizeUrl($options));
        }

        return true;
    }
}
