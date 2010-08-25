# Mobile Module

## Maintainer Contact

 * Sean Harvey (Nickname: halkyon, sharvey)
   <sean (at) silverstripe (dot) com>

 * Will Rossiter (Nickname: wrossiter, willr)
   <will (at) silverstripe (dot) com>

## Requirements

 * SilverStripe 2.4.1 or newer

## Installation

Unpack and copy the mobile folder into your SilverStripe project.

Run "dev/build" in your browser, for example: "http://localhost/silverstripe/dev/build?flush=all"

A new theme called "blackcandymobile" will be created in your themes folder after
invoking the database build. This theme is a good starting point which you can modify
to create your own mobile theme.

If the themes folder can't be written to by the web server during dev/build, please
manually copy "blackcandymobile" into your themes folder from the mobile folder.

## Configuring the mobile module

In the CMS, browse to the "Pages" tab and click the root node of the site tree to
access the SiteConfig settings. Once opened, there's a "Mobile" tab which exposes
the configuration options of the mobile module.

These options are quite simple. One set of radio buttons controls the behaviour
of the mobile site, and there's text input fields to enter which domain you want
to act as the mobile, and which is the full site, so that redirection can occur
when a user accesses your site on a mobile device.

There's also a dropdown of all themes on the site. The theme chosen here will be
the one that mobile users of your site will see.

Please keep in mind that the mobile domain must point to your site before it will work.

## Limitations

 * No fine grained mobile browser detection. There is only a way to detect the device
   in general, e.g. Android or iPhone, but not the browser and features of the device
 * Only tested on Android and iPhone devices. Other devices NOT tested

## Known issues

 * "Full site" link doesn't work if you're redirecting mobile devices to a mobile domain

## Future enhancements

 * Search form in the default theme
 * Integrate with subsites module to serve different content for a mobile site
 * Integrate with third-party mobile detection, e.g. WURFL or Apache Mobile Filter or browscap (see http://nz.php.net/get_browser)
 * Device feature detection e.g. "Does this device support SSL?" or "Does this device support XHTML?" for progressive enhancement
