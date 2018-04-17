<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17/4/18
 * Time: 12:42
 */

namespace PWBox\model\use_cases\UserUseCases;


use PWBox\model\repositories\UserRepository;

class UseCaseGenerateVerificationMail
{
    private $repository;

    /**
     * UseCasePostUser constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($verificationHash, $userEmail)
    {
        $headers = 'From:noreply@pwbox.test' . "\r\n";
        $to = $userEmail;
        $subject = '[PWBOX] Signup | Verification';
        $msg = '
        
Thanks for signing up on PWBOX!

Your account has been created successfully, but in order for you to enjoy the full experience of our service we need you to activate your account by confirming us your email address using the following URL:
http://pwbox.test/api/user/verify/'.$verificationHash.'

We hope our service is to your liking. If you have any trouble when using it be sure to check out the documentation we provide in the following site:
http://pwbox.test/documentation

        ';
        echo $msg;
        echo mail($to, $subject, $msg, $headers);
    }
}