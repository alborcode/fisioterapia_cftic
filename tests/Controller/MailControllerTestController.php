<?php

namespace App\Repository;

// tests/Controller/MailControllerTest.php namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MailControllerTest extends WebTestCase
{
    public function testMailIsSentAndContentIsOk()
    {
        $client=static::createClient();
        // Habilita el profiler para la petición que vamos a realizar.
        $client->enableProfiler();
        $crawler=$client->request('POST','/path/to/above/action');
        $mailCollector=$client->getProfile()->getCollector('swiftmailer');
        // Comprueba si el email fue enviado
        $this->assertSame(1,$mailCollector->getMessageCount());
        //La clave está en acceder al data collector del mailer y revisar la información recogida 
        $collectedMessages=$mailCollector->getMessages();
        $message=$collectedMessages[0];
        // Más comprobaciones
        $this->assertInstanceOf('Swift_Message',$message);
        $this->assertSame('Hello Email',$message->getSubject());
        $this->assertSame('send@example.com', key($message->getFrom()));
        $this->assertSame('recipient@example.com', key($message->getTo()));
        $this->assertSame(
            'You should see me from the profiler!',
            $message->getBody()
        );
    }
}