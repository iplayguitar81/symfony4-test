<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

use App\Repository\UserRepository;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    //declare $userRepository, $router, $csrfTokenManager & $passwordEncoder
    private $userRepository;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;


    //initialize and set userRepository, router, csrfTokenManager & passwordEncoder
    public function __construct(UserRepository $userRepository,
                                RouterInterface $router,
                                CsrfTokenManagerInterface $csrfTokenManager,
                                UserPasswordEncoderInterface $passwordEncoder
                                ) {

        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }


    //only apply support method if the route is app_login and the method is post
    public function supports(Request $request)
    {

        return $request->attributes->get('_route') === 'app_login'
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {

        //set credentials array...
        $credentials = [
          'email' => $request->request->get('email'),
          'password' => $request->request->get('password'),
          'csrf_token' => $request->request->get('_csrf_token'),


        ];

        //store last email/username into Security session
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        //return credentials
        return $credentials;

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //store token and check if valid... if not throw an exception...
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);

        if(!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        //find user logging in by their email address in the database...
        // returning the user object or null if not found
        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //check if the password entered on the login page is correct

        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);


    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //go to home route when logged in...
       return new RedirectResponse($this->router->generate('home'));
    }

    protected function getLoginUrl()
    {
        //if user object is not found...
        //redirect to the login page and display errors
       return $this->router->generate('app_login');
    }


}
