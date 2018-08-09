<?php
declare(strict_types=1);

namespace App\Controller;

use SpotifyWebAPI\Session as SpotifySession;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SpotifyController extends AbstractController
{
    /**
     * @Route("/", name="get_personal_info")
     */
    public function start(SpotifyWebAPI $spotifyWebAPI)
    {
        dump($spotifyWebAPI->getMyPlaylists()); exit;
    }

    /**
     * @Route("/me", name="me")
     */
    public function getPersonalInfo(SpotifyWebAPI $spotifyWebAPI)
    {
        dump($spotifyWebAPI->me()); exit;
    }

    /**
     * @Route("/callback", name="callback")
     *
     * @param Request $request
     * @param Session $session
     * @return RedirectResponse
     */
    public function callback(Request $request, Session $session)
    {
        //todo - explore using kernel events to handle resetting the authorization code
        //todo - this should be purely for api maybe???
        //todo - look into using a trait to check for access code in every controller method
        $spotifySession = new SpotifySession(
            $this->getParameter('spotify_key'),
            $this->getParameter('spotify_secret'),
            'http://127.0.0.1:8000/callback'
        );

        $code = $request->get('code');
        $spotifySession->requestAccessToken($code);

        $session->set('spotify_code', $code);
        $session->set('spotify_access_code', $spotifySession->getAccessToken());

        return new RedirectResponse('/');
    }
}