<?php
namespace Axllent\RaygunTags;

use Composer\InstalledVersions;
use Monolog\Processor\ProcessorInterface;
use SilverStripe\Control\Director;

/**
 * Adds environment tags to Raygun logging.
 */
class Processor implements ProcessorInterface
{
    /**
     * Tags.
     *
     * @var    array
     * @config
     */
    public static $tags = [];

    /**
     * Contructor
     *
     * @return void
     */
    public function __construct()
    {
        self::$tags = array_merge(
            [
                'env:' . Director::get_environment_type(),
                'php:' . phpversion(),
                'host:' . Director::host(),
                'framework:' . InstalledVersions::getVersion(
                    'silverstripe/framework'
                ),
                'ajax:' . (Director::is_ajax() ? 'true' : 'false'),
                'cli:' . (Director::is_cli() ? 'true' : 'false'),
            ],
            self::$tags
        );
    }

    /**
     * Invoke method
     *
     * @param array $record Record array
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['extra']['tags'] = self::$tags;

        return $record;
    }
}
