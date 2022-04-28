<?php

/**
 * Utopia PHP Framework
 *
 * @package Analytics
 * @subpackage Tests
 *
 * @link https://github.com/utopia-php/framework
 * @author Torsten Dittmann <torsten@appwrite.io>
 * @version 1.0 RC1
 * @license The MIT License (MIT) <http://www.opensource.org/licenses/mit-license.php>
 */

namespace Utopia\Tests;

use PHPUnit\Framework\TestCase;

use Utopia\Analytics\Adapter\GoogleAnalytics;
use Utopia\Analytics\Adapter\Plausible;
use Utopia\Analytics\Event;

class AnalyticsTest extends TestCase
{
    public $ga;
    public $ac;
    public $pa;

    public function setUp(): void
    {
        $this->ga = new GoogleAnalytics(getenv("GA_TID"), getenv("GA_CID"));
        $this->pa = new Plausible(getenv("PA_DOMAIN"), getenv("PA_APIKEY"), 
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36", "192.168.0.1");

    }

    public function testGoogleAnalytics()
    {
        // Use Measurement Protocol Validation Server for testing.
        $this->ga->endpoint = "https://www.google-analytics.com/debug/collect";

        $pageviewEvent = new Event();
        $pageviewEvent
            ->setType('pageview')
            ->setName('pageview')
            ->setUrl('https://www.appwrite.io/docs/installation');

        $normalEvent = new Event();
        $normalEvent->setType('testEvent')
            ->setName('testEvent')
            ->setUrl('https://www.appwrite.io/docs/installation')
            ->setProps(['category' => 'testEvent']);;

        $this->assertTrue($this->ga->createEvent($pageviewEvent));
        $this->assertTrue($this->ga->createEvent($normalEvent));

        $this->ga->disable();
        $this->assertFalse($this->ga->createEvent($pageviewEvent));
        $this->assertFalse($this->ga->createEvent($normalEvent));
    }

    public function testPlausible()
    {
        $pageviewEvent = new Event();
        $pageviewEvent
            ->setType('pageview')
            ->setUrl('https://www.appwrite.io/docs/pageview123');

        $normalEvent = new Event();
        $normalEvent->setType('myCoolEvent')
            ->setUrl('https://www.appwrite.io/docs/myCoolEvent123')
            ->setProps(['category' => 'coolEvent']);;
    
         $this->assertTrue($this->pa->createEvent($pageviewEvent));
         $this->assertTrue($this->pa->createEvent($normalEvent));
    
         $this->pa->disable();
         $this->assertFalse($this->pa->createEvent($pageviewEvent));
         $this->assertFalse($this->pa->createEvent($normalEvent));
    }
}