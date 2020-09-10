<?php

declare(strict_types=1);

namespace spec\App\Security;

use App\Entity\User;
use App\Security\TokenAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticatorSpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): void
    {
        $this->beConstructedWith($entityManager, $passwordEncoder);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(TokenAuthenticator::class);
    }

    public function it_should_allow_to_get_credentials(Request $request): void
    {
        $inputBag         = new InputBag(['email' => 'test@test.de', 'password' => '123456']);
        $request->request = $inputBag;
        $this->getCredentials($request)->shouldReturn([
            'email'    => 'test@test.de',
            'password' => '123456',
        ]);
    }

    public function it_should_allow_to_get_user(UserProviderInterface $userProvider): void
    {
        $user        = new User('test@test.de', '123456', 'test', 0);
        $credentials = [
            'email'    => $user->getEmail(),
            'password' => $user->getPassword(),
        ];
        $userProvider->loadUserByUsername($credentials['email'])->willReturn($user);
        $this->getUser($credentials, $userProvider)->shouldReturn($user);
    }

    public function it_should_allow_to_check_credentials(UserPasswordEncoderInterface $passwordEncoder): void
    {
        $user        = new User('test@test.de', '123456', 'test', 0);
        $credentials = [
            'email'    => $user->getEmail(),
            'password' => $user->getPassword(),
        ];

        $passwordEncoder->isPasswordValid($user, $credentials['password'])->willReturn(true);
        $this->checkCredentials($credentials, $user)->shouldReturn(true);
    }

    public function it_should_allow_to_handle_authentication_failure(Request $request, AuthenticationException $exception): void
    {
        $this->onAuthenticationFailure($request, $exception)->shouldHaveContent('Unauthorized');
        $this->onAuthenticationFailure($request, $exception)->shouldHaveStatus(401);
    }

    public function it_should_allow_to_handle_authentication_success(Request $request, TokenInterface $token, $providerKey): void
    {
        $this->onAuthenticationSuccess($request, $token, $providerKey)->shouldHaveContent('Login');
        $this->onAuthenticationSuccess($request, $token, $providerKey)->shouldHaveStatus(200);
    }

    public function it_should_allow_to_start_authentication(Request $request, AuthenticationException $exception): void
    {
        $this->start($request, $exception)->shouldHaveContent('Authentication Required');
        $this->start($request, $exception)->shouldHaveStatus(401);
    }

    public function it_should_allow_to_check_if_it_supports_remember_me(): void
    {
        $this->supportsRememberMe()->shouldReturn(false);
    }

    public function getMatchers(): array
    {
        return [
            'haveContent' => function ($subject, $content) {
                return $subject->getContent() === $content;
            },
            'haveStatus'  => function ($subject, $status) {
                return $subject->getStatusCode() === $status;
            },
        ];
    }
}
