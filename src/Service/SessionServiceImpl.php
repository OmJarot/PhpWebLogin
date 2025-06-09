<?php

namespace Php\PhpWebLogin\Service;

use Php\PhpWebLogin\Domain\Session;
use Php\PhpWebLogin\Domain\User;
use Php\PhpWebLogin\Repository\SessionRepository;
use Php\PhpWebLogin\Repository\UserRepository;

class SessionServiceImpl implements SessionService {

    public static string $COOKIE_NAME = "X-PTR-COOKIE";

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository) {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    function create(string $userID): Session {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userID;

        $this->sessionRepository->save($session);

        setcookie(self::$COOKIE_NAME, $session->id, time()+(60 * 60 * 24 * 30), "/");

        return $session;
    }

    function destroy(): void {
        $sessionId = $_COOKIE[self::$COOKIE_NAME ?? ''];
        $this->sessionRepository->deleteById($sessionId);

        setcookie(self::$COOKIE_NAME, '', 1, "/");
    }

    function current(): ?User {
        $sessionId = $_COOKIE[self::$COOKIE_NAME ?? ''];

        $session = $this->sessionRepository->findSessionById($sessionId);
        if ($session == null){
            return null;
        }
        return $this->userRepository->findById($session->userId);
    }
}