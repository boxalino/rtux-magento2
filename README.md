# Boxalino Real Time User Experience (RTUX) Framework - Magento2

## Introduction
For the Magento2 integration, Boxalino comes with a divided approach: framework layer and integration layer.
The current repository is used as a framework layer and includes:

1. Data Exporter
2. API bundle
3. JS tracker

By adding this package to your Magento2 setup, your store data can be exported to Boxalino.
In order to use the API for generic functionalities (search, autocomplete, recommendations, etc), please check the integration repository
https://github.com/boxalino/rtux-integration-magento2

## Documentation

The latest documentation is available upon request.

## Setup
1. Add the plugin to your project via composer
``composer require boxalino/rtux-magento2``

2. The Magento2 plugin has a dependency on the Boxalino API repository (https://github.com/boxalino/rtux-api-php).

3. Activate the plugin (Magento2 command)
``php bin/magento module:enable Boxalino_RealTimeUserExperience``
``php bin/magento cache:clean``
  
4. Log in your Magento2 admin and configure the plugin with the configurations provided for your setup
Magento2 Admin >> Stores >> Configuration >> Boxalino >> General

5. Due to the JS files in the plugin (tracker, Shopware6 CMS blocks, etc), a theme compilation might be required:
``./psh.phar administration:build ``
``./psh.phar storefront:build``

6. In order to kick off your account, a full export is required
``php bin/magento indexer:reindex``

7. Proceed with the integration features available in our guidelines suggestions https://github.com/boxalino/rtux-integration-magento2

## Contact us!

If you have any question, just contact us at support@boxalino.com