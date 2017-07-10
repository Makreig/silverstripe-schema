<?php
/**
 * LocalBusiness.php
 *
 * @author Bram de Leeuw
 * Date: 04/11/16
 */

namespace Schema\Builder;

use Page;
use Schema\Schema;
use Schema\Type\GeoCoordinatesSchema;
use Schema\Type\LocalBusinessSchema;
use Schema\Type\PostalAddressSchema;
use SilverStripe\Control\Director;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Class LocalBusiness
 */
class LocalBusiness extends SchemaBuilder {

    /**
     * Create the local business schema object
     *
     * @param Page $page
     * @return LocalBusinessSchema
     */
    public function getSchema($page)
    {
        $siteConfig = SiteConfig::current_site_config();

        $localBusiness = new LocalBusinessSchema(
            Director::absoluteBaseURL(),
            $siteConfig->Title,
            new PostalAddressSchema(
                $siteConfig->getField('Address'),
                $siteConfig->getField('Suburb'),
                $siteConfig->getField('State'),
                $siteConfig->getField('Postcode'),
                $siteConfig->getField('Country')
            ),
            Director::absoluteBaseURL()
        );


        if (SiteConfig::has_extension('Geocodable')) {
            $localBusiness->geo = new GeoCoordinatesSchema(
                $siteConfig->getField('Lat'),
                $siteConfig->getField('Lng')
            );
        }


        if ($telephone = $siteConfig->getField('Phone')) {
            $localBusiness->telephone = $telephone;
        }


        /**
         * You can set the image in your config.yml
         * Schema:
         *  logo: 'path/to/logo.png'
         */
        $localBusiness->image = Director::absoluteBaseURL() . Schema::get_config('logo');

        return $localBusiness;
    }
}