# Boxalino Real Time User Experience (RTUX) Framework - Magento2

## Introduction
For the Magento2 integration, Boxalino comes with a divided approach: framework layer, [integration layer](https://github.com/boxalino/rtux-integration-magento2/tree/master) and [data exporter layer](https://github.com/boxalino/exporter-magento2/tree/master).
The current repository is the framework layer and includes:

1. API bundle dependencies
2. API block & elements bases (declared as preferences in the integration layer)
3. JS tracker events (declared as preferences in the integration layer)
4. Base templates for JS tracker & API blocks (declared as preferences in the integration layer)

By adding this package to your Magento2 setup, you can start the integration of the Boxalino API features.
Once installed, please follow the [Configurations setup](https://github.com/boxalino/rtux-magento2/wiki/Configurations)

In order to use the API for generic functionalities (search, autocomplete, recommendations, etc), 
please check the integration repository
https://github.com/boxalino/rtux-integration-magento2

## Documentation

Check the public documentation on Framework Integrations
https://boxalino.atlassian.net/wiki/spaces/BPKB/pages/349503489/Framework+Integration

For a Magento 2 API integration - please consult with the official documentation on available options
https://boxalino.atlassian.net/wiki/spaces/BPKB/pages/392396801/Magento+2

## Setup
1. Add the plugin to your project via composer
``composer require boxalino/rtux-magento2``

2. The Magento2 plugin has a dependency on the Boxalino API repository (https://github.com/boxalino/rtux-api-php).

3. Activate the plugin (Magento2 command)
``php bin/magento module:enable Boxalino_RealTimeUserExperience``
``php bin/magento cache:clean``
  
4. Log in your Magento2 admin and configure the plugin with the configurations provided for your setup
Magento2 Admin >> Stores >> Configuration >> Boxalino >> General ([as instructed](https://github.com/boxalino/rtux-magento2/wiki/Configurations))

5. Due to the JS files in the plugin (tracker, M2 CMS blocks, etc), a theme compilation might be required:
``php bin/magento setup:static-content:deploy ``

6. Please make sure [the required setup initilization steps](https://github.com/boxalino/rtux-integration-magento2/wiki#before-you-start) have been done.

7. Proceed with the integration features available in our guidelines suggestions https://github.com/boxalino/rtux-integration-magento2/wiki

For more in-depth details on the structure of the repository, please consult the [wiki page](https://github.com/boxalino/rtux-magento2/wiki).

## Contact us!

If you have any question, just contact us at support@boxalino.com
