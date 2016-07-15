<?php

namespace Fuel\Core;

use Fuel\Behaviour\temporaryrouter as TemporaryRouter;

class temporaryrouterTest extends TestCase
{
    private $temporaryRouter;
    public function setUp()
    {
        $this->temporaryRouter = new TemporaryRouter();
    }

    public function testConstructor()
    {
        $this->assertEquals(60 * 5, $this->temporaryRouter->getTtl());
    }

    public function testRouteHash()
    {
        $md5 = '45c82ef2b524ea2dd01da3a254e1d219';
        $timestamp = 1404396176;
        $route = 'MaRoute';
        $passphrase = 'Science sans fiction n\'est que ruine de l\'ame ..., Daniel de Roulet';

        $expectedHash = array(
            'hash' => $md5,
            'timestamp' => $timestamp,
            'route' => $route
        );

        $this->assertEquals($expectedHash, $this->temporaryRouter->routeHash($passphrase, $route, $timestamp));
    }

    public function testRouteHashWithoutTimestamp()
    {
        $route = 'MaRoute';
        $passphrase = 'Science sans fiction n\'est que ruine de l\'ame ..., Daniel de Roulet';

        $returnedHash = $this->temporaryRouter->routeHash($passphrase, $route);

        $this->assertTrue(time() + $this->temporaryRouter->getTtl() >= $returnedHash['timestamp']);
        $this->assertEquals($route, $returnedHash['route']);

        $this->assertTrue($this->temporaryRouter->checkHash(
            $returnedHash['hash'],
            $passphrase,
            $route,
            $returnedHash['timestamp']
        ));
    }

    /**
     * @dataProvider checkHashProvider
     */
    public function testCheckHash($hash, $passphrase, $route, $timestamp, $expectedValue)
    {

        $this->assertEquals($expectedValue, $this->temporaryRouter->checkHash($hash, $passphrase, $route, $timestamp));
    }

    public function checkHashProvider()
    {
        $temporaryRouter = new TemporaryRouter();
        $passphrase = 'Science sans fiction n\'est que ruine de l\'ame ..., Daniel de Roulet';
        $route = 'MaRoute';
        $routeHash = $temporaryRouter->routeHash($passphrase, $route);

        return array(
            array($routeHash['hash'], $passphrase, $route, $routeHash['timestamp'], true),
            array('45c82ef2b524ea2dd01da3a254e1d219', $passphrase, $route, $routeHash['timestamp'], false),
            array($routeHash['hash'], $passphrase, 'AutreRoute', $routeHash['timestamp'], false),
            array($routeHash['hash'], 'Autre passphrase', $route, $routeHash['timestamp'], false),
            array($routeHash['hash'], $passphrase, $route, 1404396177, false),
        );
    }
}
