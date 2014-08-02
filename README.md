TwitchSubVote
=============

A simple PHP script that creates a vote system for suscribers of a channel, and allows the streamer to see the results of the poll.

It's very bare bones as of now since the true purpose was to understand and practice the usage of CURL and writting a simple REST client with some Oauth on it. 

My plan is to to make the script easier to mantain by adding Smarty support and refactoring the code a little bit, as it is kind of a mess currently.

If you want to use it, just head to the configuration.php file and the name of the variables should be self explanatory! Keep in mind that you need a mysql database called Results with the fields User and Vote.
