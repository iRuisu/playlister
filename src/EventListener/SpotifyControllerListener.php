<?php
declare(strict_types=1);

namespace App\EventListener;

use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class SpotifyControllerListener
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var SpotifyWebAPI
     */
    private $spotifyApi;

    public function __construct(SessionInterface $session, SpotifyWebAPI $spotifyApi)
    {
        $this->session = $session;
        $this->spotifyApi = $spotifyApi;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if($this->session->has('spotify_access_code')) {
            $this->spotifyApi->setAccessToken($this->session->get('spotify_access_code'));
        }
    }
}
