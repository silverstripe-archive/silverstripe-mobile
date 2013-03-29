# Mobile Module #

[![Build Status](https://secure.travis-ci.org/silverstripe/silverstripe-mobile.png?branch=master)](http://travis-ci.org/silverstripe/silverstripe-mobile)

## Overview ##

The mobile module provides detection for mobile devices,
and can serve a different SilverStripe theme to them.
The module can either use redirection to a separate mobile
domain, or serve mobile optimised content under the same URLs.
It includes a default "blackcandymobile" theme to demonstrate the effects.

The codebase relies on server-side device detection based on user agent strings,
which is an [unreliable](http://www.brettjankord.com/2013/01/10/active-development-on-categorizr-has-come-to-an-end/) way to determine if a device is considered to be "mobile".

The W3C recommends a ["One web" approach](http://www.w3.org/TR/mobile-bp/#d0e347) 
that uses ["Responsive Design"](http://www.alistapart.com/articles/responsive-web-design/)
to adapt content, rather than create isolated mobile experiences under their own domain.
Most of the work on mobile optimization will be in client-side techniques
like JavaScript and CSS (e.g. through media queries). 

Responsive design is not covered by this module, and can be achieved with standard
SilverStripe functionality.

The module also exposes this state in SilverStripe controllers
so custom logic and CSS/JavaScript includes can be adapted to mobile usage.

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
invoking the database (dev) build. This theme is a good starting point which you can modify
to create your own mobile theme.

If the themes folder can't be written to by the web server during dev/build, please
manually copy "blackcandymobile" into your themes folder from the mobile folder.

Alternatively, the module contains a "jquerymobile" sample theme
which creates a basic navigation interface through [jQuery Mobile](http://jquerymobile.com).
To use this theme, copy it to `/themes` in the same way,
and set it in your `SiteConfig` as described below.

## Configuration

* SS2.4: In the CMS, browse to the "Pages" tab and click the root node of the site tree to
access the SiteConfig settings. 
* SS3: In the CMS, browse to the "Settings" tab on the left navigation bar to access the 
SiteConfig settings. 

Once opened, there's a "Mobile" tab which exposes the configuration options of the mobile module.

These options are quite simple. One set of radio buttons controls the behaviour
of the mobile site, and there's text input fields to enter which domain you want
to act as the mobile, and which is the full site, so that redirection can occur
when a user accesses your site on a mobile device. 

There's also a dropdown of all themes on the site. The theme chosen here will be
the one that mobile users of your site will see.

Please keep in mind that the mobile domain must point to your site before it will work.

### Search Engine Optimization ###

The module follows [Google's recommendations](http://googlewebmastercentral.blogspot.com/2011/02/making-websites-mobile-friendly.html)
by using a 301 HTTP redirection when using the module in "domain redirection" mode.
When using the same URLs, but different themes for mobile and non-mobile content,
Google will detect this change by using a different user agent, and index accordingly.

## Limitations

 * No fine grained mobile browser detection. There is only a way to detect the device
   in general, e.g. Android or iPhone, but not the browser and features of the device
 * Only tested on Android and iPhone devices. Other devices NOT tested

## Future enhancements

 * Search form in the default theme
 * Integrate with subsites module to serve different content for a mobile site
 * Device feature detection e.g. "Does this device support SSL?" or "Does this device support XHTML?" for progressive enhancement
 * Dynamic image insertion and resizing via HTML5 data attributes
