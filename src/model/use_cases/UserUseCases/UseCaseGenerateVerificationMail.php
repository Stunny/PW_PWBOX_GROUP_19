<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17/4/18
 * Time: 12:42
 */

namespace PWBox\model\use_cases\UserUseCases;


use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PWBox\model\repositories\UserRepository;

class UseCaseGenerateVerificationMail
{

    private const EMAIL_ADDR = "pwbox.g19@gmail.com";
    private const EMAIL_PSSW = "kappaANGELkappa";

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

        $msg = '
        
<h1 style="color:#42a5f5">Thanks for signing up on PWBOX!</h1>

Your account has been created successfully, but in order for you to enjoy the full experience of our service we need you to activate your account by confirming us your email address using the following URL:
<a href="http://pwbox.test/api/user/verify/'.$verificationHash.'">Verify Account Email</a>

If the link doesn\'t work, try pasting the following address on your browser: 
http://pwbox.test/api/user/verify/'.$verificationHash.'

We hope our service is to your liking. If you have any trouble when using it be sure to check out the documentation we provide in the following site:
http://pwbox.test/documentation

        ';

        try{
            $mail = new PHPMailer();
            $mail->setFrom('noreply@pwbox.test', 'PWBox Team');
            $mail->addAddress($userEmail);
            $mail->Subject = '[PWBox] Sign up | Verification';
            $mail->Body = $msg;
            $mail->isHTML(true);

            $mail->IsSMTP();
            $mail->SMTPDebug = 2;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = "smtp.gmail.com";
            $mail->Mailer = "smtp";
            $mail->Port = 587;
            $mail->Username = self::EMAIL_ADDR;
            $mail->Password = self::EMAIL_PSSW;

            if(!$mail->send()) {
                echo 'Message was not sent.';
                echo 'Mailer error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent.';
            }
        } catch (Exception $e) {
            echo $e;
        }

    }
}