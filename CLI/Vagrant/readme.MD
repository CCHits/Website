# Using Vagrant to run the showmaker

Please note, this is a temporary work-around until we know exactly what is happening to the showmaker on AWS.

Essentially, this should be a template for what we do in the future (basically, so I don't need to keep paying for block storage on AWS) but this has been pushed ahead by a failure on AWS today.

If you need to run this

* copy config_local.php.EXAMPLE to config_local.php and edit it accordingly.
* copy mailconfig.EXAMPLE to mailconfig and edit it accordingly (optional: you might want to just run this using the mail output from your crontab).
* Add the following to your crontab `0 8 * * * cd /path/to/this/directory & /usr/bin/vagrant up`

The benefit to this is that you can also manually force this to run at any time by cloning the git repo, editing the config file and then performing `vagrant up`
