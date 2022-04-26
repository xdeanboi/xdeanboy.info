<?php

namespace xDeanBoy\Services;

use xDeanBoy\Models\Users\User;

class EmailSender
{
    /**
     * @param User $receiver
     * @param string $themeMail
     * @param string $templateMessage
     * @param array $params
     * @return void
     */
    public static function send(
        User $receiver,
        string $themeMail,
        string $templateMessage,
        array $params = []
    ): void
    {
        extract($params);

        ob_start();
        include __DIR__ . '/../../../templates/mail/' . $templateMessage;
        $buffer = ob_get_contents();
        ob_end_clean();

        mail($receiver->getEmail(), $themeMail, $buffer, 'Content-Type: text/html; charset=utf8');
    }
}