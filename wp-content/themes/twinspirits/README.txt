## Theme Dev Setup ##


The stylesheets in this theme are written using SASS and compiled using
the Compass tasks defined in Gruntfile.js.


### Initial Setup ###

To make CSS changes, you'll first need to install dev dependencies:

* Run "npm install" from this directory.

Note that to run "npm" commands, you'll need to have first installed
node and npm on your machine if you don't already have these.

To test if you can compile CSS correctly, try running "grunt build"
from this directory. If you don't see any errors, you're good to go.

If this doesn't work, you may need to:

* Install Grunt
* Install the specific versions of node or nodesass libraries that
  work with the versions set in package.json.
  This stack is working currently:
    - Sass 3.4.13
    - grunt-cli v1.2.0
    - grunt v0.4.5
    - node v0.12.12    <--- voted most likely dependency to bork things

The node version is unfortunately pretty old and it's entirely possible
you have a newer version that this. Using Homebrew makes it slightly less
difficult to toggle between versions. Maybe check that out. Godspeed.


### Dev workflow ###

#### Codebase ####

Code files are stored in a Git repository over at Pantheon, where the 
production site lives. If you're reading this, you already have access,
nice work.

All changes must be committed into the Git repository to be deployed to 
the live website.

#### Compiling CSS ####

To compile CSS each time you make a change to a SASS file, run "grunt watch"
from the theme directory.

Alternatively, you can run "grunt build" at any point to compile.

SASS must be compiled down to CSS in order to show up on the site.
