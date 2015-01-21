Pilau init
====

A standalone PHP script to initialise a Pilau-flavoured WordPress site.

## How to use

1. Create your remote Git repository (don't include a `.gitignore`). Clone it into your local www directory for the site you'll be initialising. 
1. Make sure you're using a subdomain / virtual host on your local dev machine, e.g. `pilau-site.dev`.
1. Make sure you've got the www root mapped to a subdirectory named `public`.
1. Place the file `pilau-init.php` in the `public` directory.
1. Open `pilau-site.dev/pilau-init.php` (for example) in a browser.
1. Follow on-screen instructions.