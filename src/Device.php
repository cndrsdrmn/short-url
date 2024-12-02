<?php

namespace Cndrsdrmn\ShortUrl;

use DeviceDetector\Cache\CacheInterface;
use DeviceDetector\ClientHints;
use DeviceDetector\Parser\AbstractBotParser;
use DeviceDetector\Parser\Client\AbstractClientParser;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use DeviceDetector\Yaml\ParserInterface as YamlParser;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void setUserAgent(string $userAgent)
 * @method static void setClientHints(?ClientHints $clientHints = null)
 * @method static void addClientParser(AbstractClientParser $parser)
 * @method static array getClientParsers()
 * @method static void addDeviceParser(AbstractDeviceParser $parser)
 * @method static array getDeviceParsers()
 * @method static void addBotParser(AbstractBotParser $parser)
 * @method static array getBotParsers()
 * @method static void discardBotInformation(bool $discard = true)
 * @method static void skipBotDetection(bool $skip = true)
 * @method static bool isBot()
 * @method static bool isTouchEnabled()
 * @method static bool isMobile()
 * @method static bool isDesktop()
 * @method static array|string|null getOs(string $attr = '')
 * @method static array|string|null getClient(string $attr = '')
 * @method static int getDevice()
 * @method static string getDeviceName()
 * @method static string getBrand()
 * @method static string getBrandName()
 * @method static string getModel()
 * @method static string getUserAgent()
 * @method static ClientHints|null getClientHints()
 * @method static array|bool|null getBot()
 * @method static bool isParsed()
 * @method static void parse()
 * @method static void setCache(CacheInterface $cache)
 * @method static CacheInterface getCache()
 * @method static void setYamlParser(YamlParser $yamlParser)
 * @method static YamlParser getYamlParser()
 *
 * Magic Device Type Methods:
 * @method static bool isSmartphone()
 * @method static bool isFeaturePhone()
 * @method static bool isTablet()
 * @method static bool isPhablet()
 * @method static bool isConsole()
 * @method static bool isPortableMediaPlayer()
 * @method static bool isCarBrowser()
 * @method static bool isTV()
 * @method static bool isSmartDisplay()
 * @method static bool isSmartSpeaker()
 * @method static bool isCamera()
 * @method static bool isWearable()
 * @method static bool isPeripheral()
 *
 * Magic Client Type Methods:
 * @method static bool isBrowser()
 * @method static bool isFeedReader()
 * @method static bool isMobileApp()
 * @method static bool isPIM()
 * @method static bool isLibrary()
 * @method static bool isMediaPlayer()
 *
 * @see  \DeviceDetector\DeviceDetector
 */
class Device extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return 'device';
    }
}
